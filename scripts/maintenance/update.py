#!/usr/bin/env python3
"""Weekly WordPress update run for the production network, with a smoke test and automatic rollback.

    python3 scripts/maintenance/update.py <out-dir>

Run by .github/workflows/update.yml; also runs by hand from Git Bash. In order:

  1. Smoke-test every site in sites.txt. If one is already failing, stop before touching anything.
  2. Check the newest nightly backup in SharePoint is under 36 hours old (a warning, not a stop).
  3. List what has updates: core, plugins, third-party themes. Our own property themes are never
     updated from wordpress.org, even if a slug there ever collides with one of ours.
  4. Take a pre-update backup (backup.sh, tier "pre-update"). No backup, no updates.
  5. Apply: plugin and theme updates, minor core releases, language packs. Major core releases
     are reported and held unless ALLOW_MAJOR_CORE is set.
  6. Purge Varnish, smoke-test again, run PHP on every site, and look for new fatals in the
     PHP error log that point at what was just updated.
  7. If anything fails: reinstall the previous plugin/theme/core versions, restore the
     pre-update database if the schema changed, purge, and smoke-test again.

Environment: DRY_RUN (list only), ALLOW_MAJOR_CORE, FORCE_POSTCHECK_FAIL (exercise the rollback
path), plus whatever backup.sh and graph.py need. Writes <out-dir>/report.txt (the plain log),
report.json (the same, structured, for the HTML email), result.env and step, all read by notify.py.
"""
import json
import os
import re
import shlex
import shutil
import subprocess
import sys
import time
import traceback
from datetime import datetime, timezone

HERE = os.path.dirname(os.path.abspath(__file__))
REPO = os.path.dirname(os.path.dirname(HERE))
PROD = os.path.join(HERE, "..", "prod.sh")
# On Windows, a bare "bash" can resolve to WSL's System32\bash.exe ahead of Git Bash; which() follows PATH.
BASH = shutil.which("bash") or "bash"
FRESH_HOURS = 36


def flag(name):
    return os.environ.get(name, "").lower() in ("1", "true", "yes")


def vkey(version):
    return tuple(int(n) for n in re.findall(r"\d+", version or "0"))


class Run:
    def __init__(self, out):
        self.out = out
        os.makedirs(out, exist_ok=True)
        open(self.path("report.txt"), "w").close()
        self.result = {"RESULT": "failed", "COUNT": "0"}
        # Structured copy of the report, which notify.py renders into the HTML email.
        self.data = {"started": datetime.now(timezone.utc).isoformat(timespec="seconds")}

    def path(self, name):
        return os.path.join(self.out, name)

    def step(self, name):
        with open(self.path("step"), "w") as fh:
            fh.write(name + "\n")
        print(f"== {name}", flush=True)

    def note(self, line=""):
        print(line, flush=True)
        with open(self.path("report.txt"), "a", encoding="utf-8") as fh:
            fh.write(line + "\n")

    def finish(self, result, code, **extra):
        self.result.update(RESULT=result, **{k: str(v) for k, v in extra.items()})
        with open(self.path("result.env"), "w") as fh:
            for key, value in self.result.items():
                fh.write(f"{key}={shlex.quote(value)}\n")
        with open(self.path("report.json"), "w", encoding="utf-8") as fh:
            json.dump(self.data, fh, indent=1)
        sys.exit(code)


def bash(args, env=None, check=True):
    print("$ " + " ".join([os.path.basename(args[0])] + args[1:]), flush=True)
    proc = subprocess.run([BASH] + args, capture_output=True, text=True, env=env)
    output = (proc.stdout + proc.stderr).strip()
    if output:
        print(output, flush=True)
    if check and proc.returncode != 0:
        raise RuntimeError(f"command failed ({proc.returncode}): {' '.join(args[1:])}\n{output[-1500:]}")
    return proc.returncode, proc.stdout.strip()


def remote(cmd, check=True):
    """A shell command on the server, from the WordPress root. umask 002 keeps what WP-CLI writes
    group-writable, so WordPress (running as the app user) can still update it later."""
    return bash([PROD, "sh", f"umask 002 && {cmd}"], check=check)[1]


def wp(args, check=True):
    return remote(f"wp {args}", check=check)


def wp_json(args):
    raw = wp(args + " --format=json", check=False)
    try:
        return json.loads(raw[raw.index("["):]) if "[" in raw else []
    except ValueError:
        return []


def read_list(name):
    with open(os.path.join(HERE, name), encoding="utf-8") as fh:
        rows = [line.split("#", 1)[0].split() for line in fh]
    return [row for row in rows if row]


def smoke(force_fail=False):
    code, out = bash([os.path.join(HERE, "smoke.sh")], check=False)
    if force_fail:
        out += "\nFAIL  (forced by FORCE_POSTCHECK_FAIL to exercise the rollback)"
    return code == 0 and not force_fail, out


def purge_all():
    for row in read_list("sites.txt"):
        bash([PROD, "purge", row[0]], check=False)
    time.sleep(5)


def fix_group():
    # Files WP-CLI just wrote belong to the SSH user. Hand them to the www-data group (which the SSH
    # user is in) so the app user can write them, as it can everything else under public_html.
    remote('find . -xdev -user "$(id -un)" ! -type l \\( ! -group www-data -o ! -perm -g+w \\) '
           '-exec chgrp www-data {} + -exec chmod g+w {} + 2>&1 | tail -3', check=False)


def main(run):
    dry, allow_major, force_fail = flag("DRY_RUN"), flag("ALLOW_MAJOR_CORE"), flag("FORCE_POSTCHECK_FAIL")
    run.note(f"Sterling production updates — {datetime.now(timezone.utc):%Y-%m-%d %H:%M} UTC"
             + ("  (DRY RUN: nothing will be changed)" if dry else ""))
    run.note()
    run.data["dry_run"] = dry

    run.step("preflight smoke test")
    ok, text = smoke()
    run.data["smoke_before"] = text
    if not ok:
        run.note("A site was already failing BEFORE any update, so nothing was touched:")
        run.note(text)
        run.finish("aborted", 1)

    run.step("backup freshness")
    if os.environ.get("SP_DRIVE_ID"):
        _, age = bash(["-c", f'"${{PYTHON:-python3}}" {shlex.quote(os.path.join(HERE, "graph.py"))} '
                             "latest-age daily weekly monthly"], check=False)
        age = age.splitlines()[-1] if age else "unknown"
        try:
            stale = float(age) > FRESH_HOURS
        except ValueError:
            age, stale = ("none" if age == "none" else "unknown"), True
        run.note(("WARNING: " if stale else "") + f"Newest nightly backup in SharePoint: "
                 + (f"{age} h old" if age not in ("none", "unknown") else age)
                 + (" — the nightly backup may have stopped running." if stale else ""))
        if stale:
            run.result["STALE_BACKUP"] = age
        run.data["freshness"] = {"checked": True, "stale": stale,
                                 "hours": float(age) if age not in ("none", "unknown") else None}
    else:
        run.note("Nightly backup freshness: not checked (SP_DRIVE_ID is not set).")
        run.data["freshness"] = {"checked": False}

    run.step("inventory")
    core_before = wp("core version")
    db_version_before = wp("option get db_version")
    core_updates = wp_json("core check-update")
    plugins_before = {p["name"]: p for p in wp_json("plugin list --fields=name,status,version,update,update_version")}
    themes_before = {t["name"]: t for t in wp_json("theme list --fields=name,status,version,update,update_version")}
    pins = {row[0] for row in read_list("pinned-plugins.txt")}
    ours = {name for prop in os.listdir(REPO) if os.path.isdir(os.path.join(REPO, prop, "theme"))
            for name in os.listdir(os.path.join(REPO, prop, "theme"))}

    minor = sorted((u["version"] for u in core_updates if u.get("update_type") == "minor"), key=vkey)
    major = sorted((u["version"] for u in core_updates if u.get("update_type") == "major"), key=vkey)
    core_target = (major[-1] if allow_major and major else None) or (minor[-1] if minor else None)
    if major and core_target != major[-1]:
        run.result["HELD_MAJOR"] = major[-1]

    def has_update(item):
        return item.get("update") == "available"

    plugin_todo = [p for n, p in plugins_before.items() if has_update(p) and n not in pins]
    plugin_held = [p for n, p in plugins_before.items() if has_update(p) and n in pins]
    theme_todo = [t for n, t in themes_before.items() if has_update(t) and n not in ours]
    count = len(plugin_todo) + len(theme_todo) + (1 if core_target else 0)

    run.data.update(
        core_before=core_before, plugin_count=len(plugins_before), theme_count=len(themes_before),
        available=([{"name": "WordPress core", "from": core_before, "to": core_target}] if core_target else [])
        + [{"name": i["name"], "from": i["version"], "to": i["update_version"]} for i in plugin_todo + theme_todo],
        held=([{"name": "WordPress core", "from": core_before, "to": run.result["HELD_MAJOR"],
                "reason": f"WordPress {run.result['HELD_MAJOR']} is a major release. Read its release notes, then"
                          " approve it by running Update production manually with allow_major_core ticked."}]
              if run.result.get("HELD_MAJOR") else [])
        + [{"name": i["name"], "from": i["version"], "to": i["update_version"],
            "reason": "Pinned in scripts/maintenance/pinned-plugins.txt, so it is skipped until unpinned."} for i in plugin_held],
    )
    run.note(f"WordPress {core_before}; {len(plugins_before)} plugins; {len(themes_before)} themes.")
    run.note()
    run.note("Available" if count else "Available: nothing — everything is current.")
    if core_target:
        run.note(f"  {'WordPress core':<32} {core_before} → {core_target}")
    for item in plugin_todo + theme_todo:
        run.note(f"  {item['name']:<32} {item['version']} → {item['update_version']}")
    if run.result.get("HELD_MAJOR") or plugin_held:
        run.note()
        run.note("Held (not applied)")
        if run.result.get("HELD_MAJOR"):
            run.note(f"  WordPress {run.result['HELD_MAJOR']} is a major release. To approve it, run the"
                     " 'Update production' workflow manually with allow_major_core ticked.")
        for item in plugin_held:
            run.note(f"  {item['name']:<32} {item['version']} → {item['update_version']}  (pinned in pinned-plugins.txt)")
    run.note()

    if dry:
        run.finish("dry_run", 0, COUNT=count)
    if count == 0:
        wp("language core update", check=False)
        wp("language plugin update --all", check=False)
        run.note("Smoke test (all sites passing)")
        run.note(text)
        run.finish("nothing", 0)

    run.step("pre-update backup")
    pre = os.path.join(run.out, "pre-update")
    env = dict(os.environ, BACKUP_LABEL="pre-update", BACKUP_UPLOADS="never")
    code, _ = bash([os.path.join(HERE, "backup.sh"), pre], env=env, check=False)
    if code != 0:
        run.note("The pre-update backup FAILED, so no updates were applied.")
        run.finish("failed", 1)
    with open(os.path.join(pre, "summary.env")) as fh:
        run.data["pre_backup"] = dict(w.split("=", 1) for line in fh for w in shlex.split(line) if "=" in w)
    remote_dir = run.data["pre_backup"]["REMOTE_DIR"]
    with open(os.path.join(pre, "report.txt"), encoding="utf-8") as fh:
        for line in fh:
            run.note(line.rstrip())
    run.note()

    healthy, reason, text = False, "", ""
    try:
        run.step("apply updates")
        # Cloudways writes PHP errors to the backend (Apache) error log beside the app.
        errlog = remote("ls -1 ../logs/php-app.error.log ../logs/backend_*.error.log 2>/dev/null | head -1", check=False)
        log_offset = remote(f"stat -c %s {errlog}", check=False) if errlog else ""
        if core_target:
            wp(f"core update --version={shlex.quote(core_target)}")
            wp("core update-db --network")
        if plugin_todo:
            wp("plugin update " + " ".join(shlex.quote(p["name"]) for p in plugin_todo), check=False)
        if theme_todo:
            wp("theme update " + " ".join(shlex.quote(t["name"]) for t in theme_todo), check=False)
        wp("language core update", check=False)
        wp("language plugin update --all", check=False)
        fix_group()

        run.step("verify after update")
        purge_all()
        ok, text = smoke(force_fail)
        php_ok = all("ok" in wp(f"eval 'echo \"ok\";' --url={shlex.quote(row[0])}", check=False)
                     for row in read_list("sites.txt"))
        fatals = []
        if errlog and log_offset.isdigit():
            fresh = remote(f'f={errlog}; s=$(stat -c %s "$f"); '
                           f'if [ "$s" -ge {log_offset} ]; then tail -c +$(({log_offset}+1)) "$f"; else tail -c 200000 "$f"; fi '
                           "| grep -E 'PHP (Fatal|Parse) error' | tail -20", check=False)
            markers = [f"/plugins/{p['name']}/" for p in plugin_todo] + [f"/themes/{t['name']}/" for t in theme_todo]
            if core_target:
                markers += ["/wp-includes/", "/wp-admin/"]
            fatals = [line for line in fresh.splitlines() if any(m in line for m in markers)]
        healthy = ok and php_ok and not fatals
        reason = "; ".join(r for r, bad in (("smoke test failed", not ok), ("PHP failed to run on a site", not php_ok),
                                            (f"{len(fatals)} new PHP fatal(s) in updated code", fatals)) if bad)
        if fatals:
            text += "\n\nNew PHP fatals:\n" + "\n".join(fatals)
    except Exception:
        reason = "the update step crashed:\n" + traceback.format_exc(limit=3)

    plugins_after = {p["name"]: p for p in wp_json("plugin list --fields=name,version,update")}
    themes_after = {t["name"]: t for t in wp_json("theme list --fields=name,version,update")}
    core_after = wp("core version", check=False)
    changed = [(n, plugins_before[n]["version"], p["version"]) for n, p in plugins_after.items()
               if n in plugins_before and p["version"] != plugins_before[n]["version"]]
    changed_themes = [(n, themes_before[n]["version"], t["version"]) for n, t in themes_after.items()
                      if n in themes_before and t["version"] != themes_before[n]["version"]]
    not_updated = [p["name"] for p in plugin_todo if p["name"] not in {c[0] for c in changed}]
    run.data.update(
        core_after=core_after, not_updated=not_updated, smoke_after=text, problem=reason,
        updated=([{"name": "WordPress core", "from": core_before, "to": core_after}] if core_after != core_before else [])
        + [{"name": n, "from": old, "to": new} for n, old, new in changed + changed_themes],
    )

    run.note("Updated")
    if core_after != core_before:
        run.note(f"  {'WordPress core':<32} {core_before} → {core_after}")
    for name, old, new in changed + changed_themes:
        run.note(f"  {name:<32} {old} → {new}")
    for name in not_updated:
        run.note(f"  {name:<32} DID NOT UPDATE — check the log (premium plugin without a licence?)")
    run.note()

    if healthy:
        run.note("Smoke test after updating (all sites passing)")
        run.note(text)
        run.finish("applied", 0, COUNT=len(changed) + len(changed_themes) + (core_after != core_before))

    run.step("roll back")
    run.note(f"PROBLEM AFTER UPDATING: {reason}")
    run.note(text)
    run.note()
    run.note("Rolling back")
    rolled = run.data["rollback"] = []
    for name, old, new in changed:
        wp(f"plugin install {shlex.quote(name)} --version={shlex.quote(old)} --force", check=False)
        run.note(f"  {name} → {old}")
        rolled.append({"name": name, "from": new, "to": old})
    for name, old, new in changed_themes:
        wp(f"theme install {shlex.quote(name)} --version={shlex.quote(old)} --force", check=False)
        run.note(f"  {name} → {old}")
        rolled.append({"name": name, "from": new, "to": old})
    if core_after and core_after != core_before:
        wp(f"core update --version={shlex.quote(core_before)} --force", check=False)
        run.note(f"  WordPress core → {core_before}")
        rolled.append({"name": "WordPress core", "from": core_after, "to": core_before})
    run.data["db_restored"] = False
    if wp("option get db_version", check=False) != db_version_before:
        remote(f"gunzip -c ~/{remote_dir}/db.sql.gz | wp db import - && wp cache flush", check=False)
        run.note(f"  database restored from ~/{remote_dir}/db.sql.gz (the schema had changed). Anything"
                 " edited in wp-admin during the update window was lost.")
        run.data["db_restored"] = True
    fix_group()
    purge_all()
    ok, text = smoke()
    run.data["smoke_rollback"] = text
    run.note()
    run.note("Smoke test after rollback" + (" (all sites passing)" if ok else " — STILL FAILING"))
    run.note(text)
    run.finish("rolled_back" if ok else "failed", 1)


if __name__ == "__main__":
    if len(sys.argv) != 2:
        sys.exit(__doc__)
    sys.stdout.reconfigure(encoding="utf-8", errors="replace")  # Windows consoles default to cp1252
    current = Run(sys.argv[1])
    try:
        main(current)
    except SystemExit:
        raise
    except Exception:
        current.note("The update run crashed:")
        current.note(traceback.format_exc(limit=4))
        current.finish("failed", 1)

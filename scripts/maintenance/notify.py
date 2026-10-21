#!/usr/bin/env python3
"""Email the outcome of a maintenance run (success or failure) as a branded HTML email.

    python3 scripts/maintenance/notify.py backup|update <out-dir> <job-status> [<log-file>] [--preview <file.html>]

<job-status> is the workflow's job.status (success / failure / cancelled). The content comes from
<out-dir>: summary.env (backup), or result.env + report.json (update), plus the last lines of
<log-file> when something went wrong. The layout is email/template.html; the logo and gradient
stripe in email/ are embedded as inline images.

--preview writes the HTML to a file (images linked from disk) instead of sending it. Without M365
credentials (a local run) it previews to <out-dir>/email.html automatically.
"""
import argparse
import html
import json
import os
import re
import shlex
import string
import sys
from datetime import datetime, timezone

HERE = os.path.dirname(os.path.abspath(__file__))
EMAIL = os.path.join(HERE, "email")
FONT = "font-family:Inter,'Segoe UI',Helvetica,Arial,sans-serif;"

# Pill colours per outcome: (background, text).
TONES = {
    "ok":   ("#dcfce7", "#166534"),
    "info": ("#dbeafe", "#1e40af"),
    "warn": ("#fef3c7", "#92400e"),
    "fail": ("#fee2e2", "#991b1b"),
}
CALLOUT = {  # background, left border, title colour
    "info": ("#eff6ff", "#3b82f6", "#1e3a8a"),
    "warn": ("#fffbeb", "#f59e0b", "#78350f"),
    "fail": ("#fef2f2", "#ef4444", "#7f1d1d"),
}

esc = html.escape


# ---------------------------------------------------------------- reading the run's output

def read_env(path):
    values = {}
    if os.path.exists(path):
        with open(path, encoding="utf-8", errors="replace") as fh:
            for line in fh:
                for word in shlex.split(line, comments=True):
                    if "=" in word:
                        key, value = word.split("=", 1)
                        values[key] = value
    return values


def read_json(path):
    try:
        with open(path, encoding="utf-8") as fh:
            return json.load(fh)
    except (OSError, ValueError):
        return {}


def read_text(path, tail=None):
    try:
        with open(path, encoding="utf-8", errors="replace") as fh:
            lines = fh.read().splitlines()
    except OSError:
        return ""
    # Drop ANSI colour codes GitHub's log carries.
    lines = [re.sub(r"\x1b\[[0-9;]*m", "", line) for line in lines]
    return "\n".join(lines[-tail:] if tail else lines)


def parse_smoke(text):
    rows = []
    for line in (text or "").splitlines():
        m = re.match(r"^(ok|FAIL)\s+(\S+)\s*(.*)$", line.strip())
        if m:
            rows.append({"ok": m.group(1) == "ok", "url": m.group(2), "note": m.group(3).strip()})
    return rows


def site_names(local_dir):
    """blog_id -> display name, from the backup's manifest."""
    names = {}
    for site in read_json(os.path.join(local_dir or "", "manifest", "sites.json")) or []:
        host = re.sub(r"^https?://", "", str(site.get("url", ""))).strip("/")
        names[str(site.get("blog_id"))] = "Network root" if str(site.get("blog_id")) == "1" else host
    return names


def when(iso=None):
    moment = datetime.fromisoformat(iso) if iso else datetime.now(timezone.utc)
    try:
        from zoneinfo import ZoneInfo
        local = moment.astimezone(ZoneInfo("America/New_York"))
        return f"{local:%a %d %b %Y · %I:%M %p} {local.tzname()} ({moment.astimezone(timezone.utc):%H:%M} UTC)"
    except Exception:  # no tz database (bare Windows Python)
        return f"{moment.astimezone(timezone.utc):%a %d %b %Y · %H:%M} UTC"


def size(value):
    """du -h output ("332K", "1.2G") as "332 KB"."""
    m = re.fullmatch(r"([\d.]+)([KMGT]?)", (value or "").strip())
    return f"{m.group(1)} {m.group(2)}B" if m and m.group(2) else (value or "?")


def run_url():
    if os.environ.get("GITHUB_RUN_ID"):
        return (f"{os.environ.get('GITHUB_SERVER_URL', 'https://github.com')}/"
                f"{os.environ['GITHUB_REPOSITORY']}/actions/runs/{os.environ['GITHUB_RUN_ID']}")
    return ""


# ---------------------------------------------------------------- building blocks

def tiles(items):
    """Up to four stat tiles: (label, value, note)."""
    if not items:
        return ""
    width = 100 // len(items)
    cells = "".join(
        f'<td class="tile" width="{width}%" valign="top" style="padding:0 4px;">'
        f'<div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:12px 14px;">'
        f'<div style="font-size:10px;letter-spacing:1px;text-transform:uppercase;color:#64748b;">{esc(label)}</div>'
        f'<div style="font-size:19px;font-weight:700;color:#0f172a;padding-top:4px;">{value}</div>'
        f'<div style="font-size:12px;color:#64748b;padding-top:2px;">{esc(note)}</div></div></td>'
        for label, value, note in items)
    return (f'<tr><td class="pad" style="padding:18px 24px 4px;{FONT}">'
            f'<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"><tr>{cells}</tr></table>'
            f"</td></tr>")


def section(title, inner):
    return (f'<tr><td class="pad" style="padding:22px 28px 0;{FONT}">'
            f'<div style="font-size:11px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;'
            f'color:#1346a3;padding-bottom:10px;">{esc(title)}</div>{inner}</td></tr>')


def table(rows, head=None):
    """rows: lists of cell HTML. The last column is right-aligned."""
    def cells(row, tag, style):
        return "".join(
            f'<{tag} align="{"right" if i == len(row) - 1 and len(row) > 1 else "left"}" style="{style}">{c}</{tag}>'
            for i, c in enumerate(row))
    th = "padding:8px 10px;font-size:11px;font-weight:600;color:#64748b;border-bottom:1px solid #e2e8f0;text-transform:uppercase;letter-spacing:.5px;"
    td = "padding:9px 10px;font-size:13px;color:#0f172a;border-bottom:1px solid #f1f5f9;"
    out = '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;">'
    if head:
        out += f"<tr>{cells(head, 'th', th)}</tr>"
    out += "".join(f"<tr>{cells(r, 'td', td)}</tr>" for r in rows)
    return out + "</table>"


def chip(text, tone="neutral"):
    bg, fg = {"neutral": ("#f1f5f9", "#334155"), **TONES}.get(tone, ("#f1f5f9", "#334155"))
    return (f'<span style="display:inline-block;padding:2px 8px;border-radius:6px;background:{bg};color:{fg};'
            f'font-size:12px;font-family:Consolas,Menlo,monospace;white-space:nowrap;">{esc(text)}</span>')


def change_rows(items):
    return [[esc(i["name"]), f'{chip(i["from"])} <span style="color:#94a3b8;">&rarr;</span> {chip(i["to"], "info")}']
            for i in items]


def smoke_table(text):
    rows = []
    for r in parse_smoke(text):
        path = re.sub(r"^https?://", "", r["url"])
        status = chip("200 OK", "ok") if r["ok"] else chip(r["note"] or "failed", "fail")
        rows.append([esc(path), status])
    return table(rows, ["Page", "Result"]) if rows else ""


def callout(tone, title, body_html):
    bg, border, fg = CALLOUT[tone]
    return (f'<tr><td class="pad" style="padding:18px 28px 0;{FONT}">'
            f'<div style="background:{bg};border-left:4px solid {border};border-radius:6px;padding:12px 16px;">'
            f'<div style="font-size:14px;font-weight:700;color:{fg};">{esc(title)}</div>'
            f'<div style="font-size:13px;line-height:1.55;color:#334155;padding-top:4px;">{body_html}</div></div></td></tr>')


def code(text):
    return (f'<div style="background:#0f172a;border-radius:8px;padding:14px 16px;color:#e2e8f0;'
            f'font-family:Consolas,Menlo,monospace;font-size:11.5px;line-height:1.5;white-space:pre-wrap;'
            f'word-break:break-word;">{esc(text)}</div>')


def button(label, href, primary=True):
    if not href:
        return ""
    bg, fg, border = ("#1346a3", "#ffffff", "#1346a3") if primary else ("#ffffff", "#1346a3", "#c7d2fe")
    # Bulletproof button: a table cell carries the colour, so Outlook keeps it.
    return (f'<table role="presentation" cellpadding="0" cellspacing="0" border="0" style="display:inline-table;margin:8px 8px 0 0;">'
            f'<tr><td bgcolor="{bg}" style="background:{bg};border:1px solid {border};border-radius:8px;">'
            f'<a href="{esc(href)}" style="display:inline-block;padding:11px 20px;{FONT}font-size:14px;font-weight:600;'
            f'color:{fg};text-decoration:none;">{esc(label)}</a></td></tr></table>')


def kv(rows):
    return table([[f'<span style="color:#64748b;">{esc(k)}</span>', v] for k, v in rows])


# ---------------------------------------------------------------- the two emails

def backup_email(out, status, log):
    s = read_env(os.path.join(out, "summary.env"))
    ok = status == "success"
    step = read_text(os.path.join(out, "step")).strip() or "setup"
    tier = s.get("TIER", "daily")
    kind = {"daily": "Nightly", "weekly": "Weekly", "monthly": "Monthly", "pre-update": "Pre-update"}.get(tier, tier.title())
    day = datetime.now(timezone.utc).strftime("%Y-%m-%d")
    verified = s.get("VERIFIED") == "import-verified"

    if not ok:
        subject = f"[Sterling] Backup FAILED at: {step}"
        content = dict(pill=("Failed", "fail"), title="Backup failed",
                       subtitle=f"{when()} · stopped at <b>{esc(step)}</b>",
                       preheader=f"The {kind.lower()} backup stopped at: {step}. Nothing new was stored.")
        sections = callout("fail", f"Stopped at: {step}",
                           "No new backup was stored tonight. The previous backups in SharePoint and on the "
                           "server are untouched. The log below shows the cause; once it is fixed, re-run "
                           "<b>Back up production</b> from the Actions tab.")
        tail = read_text(log, tail=40)
        if tail:
            sections += section("Last lines of the run log", code(tail))
        return subject, content, "", sections, [("View the run on GitHub", run_url(), True)]

    db, uploads = size(s.get("DB_SIZE")), size(s.get("UPLOADS_SIZE"))
    subject = f"[Sterling] Backup OK — {day} · DB {db} · uploads {uploads} · {s.get('VERIFIED', '?')}"
    content = dict(pill=("Backup complete", "ok"), title=f"{kind} backup complete",
                   subtitle=f"{when()} · {s.get('SITES', '?')} sites · stored in SharePoint",
                   preheader=f"Database {db} · uploads {uploads} · "
                             + ("restore-tested" if verified else "not restore-tested"))
    stat = tiles([
        ("Database", esc(db), "gzip, whole network"),
        ("Uploads", esc(uploads), "media library"),
        ("Restore test", "&#10003; Passed" if verified else "Skipped", "fresh MariaDB import" if verified else "no test database"),
    ])
    names = site_names(s.get("LOCAL_DIR"))
    counts = [c.split(":", 1) for c in s.get("BLOG_COUNTS", "").split() if ":" in c]
    sections = ""
    if counts:
        rows = sorted(counts, key=lambda c: (c[0] == "1", names.get(c[0], c[0])))
        sections += section("Restored and checked", table(
            [[esc(names.get(b, f"Site {b}")), f"<b>{esc(n)}</b> published"] for b, n in rows], ["Site", "Content"]))
    sections += section("Where it is", kv([
        ("SharePoint", esc(f"backups › Documents › Sterling Backups › {s.get('FOLDER', '')}")),
        ("On the server", esc(f"~/{s.get('REMOTE_DIR', '')}") + f' <span style="color:#94a3b8;">(newest two kept)</span>'),
        ("Server disk free", esc(size(s.get("DISK_FREE")))),
        ("Includes", "Database · uploads · ACF field groups · version manifest"),
    ]))
    return subject, content, stat, sections, [("Open in SharePoint", s.get("STORED_URL"), True),
                                              ("View the run", run_url(), False)]


def update_email(out, status, log):
    r = read_env(os.path.join(out, "result.env"))
    d = read_json(os.path.join(out, "report.json"))
    result = r.get("RESULT", "failed")
    if status != "success" and result not in ("aborted", "rolled_back"):
        result = "failed"
    step = read_text(os.path.join(out, "step")).strip() or "setup"
    held_major = r.get("HELD_MAJOR")
    updated, held, available = d.get("updated", []), d.get("held", []), d.get("available", [])
    smoke_after = d.get("smoke_after") or d.get("smoke_before", "")
    passing = parse_smoke(d.get("smoke_rollback") or smoke_after)
    pages = f"{sum(p['ok'] for p in passing)}/{len(passing)}" if passing else "—"
    fresh = d.get("freshness", {})
    age = f"{fresh['hours']:.0f} h ago" if fresh.get("hours") is not None else ("not checked" if not fresh.get("checked") else "none found")

    subjects = {
        "applied": f"[Sterling] Updates applied ({r.get('COUNT', '?')}) — all sites passing",
        "nothing": "[Sterling] No updates needed — all sites passing",
        "dry_run": f"[Sterling] Update check (dry run) — {r.get('COUNT', '0')} available",
        "aborted": "[Sterling] Updates SKIPPED — a site was already failing",
        "rolled_back": "[Sterling] Updates ROLLED BACK — sites restored, needs a look",
        "failed": f"[Sterling] Updates FAILED at: {step} — manual attention needed",
    }
    subject = subjects[result] + (f" · WordPress {held_major} awaiting approval" if held_major and result != "failed" else "")
    look = {
        "applied": (("Updated", "ok"), "Updates applied",
                    f"{len(updated)} update{'s' if len(updated) != 1 else ''} installed · every site passing"),
        "nothing": (("Up to date", "ok"), "Everything is up to date", "No plugin, theme or minor WordPress updates were waiting"),
        "dry_run": (("Dry run", "info"), "Update check",
                    f"{len(available)} update{'s' if len(available) != 1 else ''} available · nothing was changed"),
        "aborted": (("Skipped", "warn"), "Updates skipped", "A site was already failing, so nothing was touched"),
        "rolled_back": (("Rolled back", "warn"), "Updates rolled back", "A check failed after updating; the previous versions are back"),
        "failed": (("Failed", "fail"), "Update run failed", f"Stopped at <b>{esc(step)}</b> · needs a look"),
    }[result]
    content = dict(pill=look[0], title=look[1], subtitle=f"{when(d.get('started'))} · {look[2]}",
                   preheader=re.sub("<[^>]+>", "", look[2]))

    changes = {
        "dry_run": ("Available", str(len(available)), "waiting"),
        "rolled_back": ("Rolled back", str(len(d.get("rollback", []))), "restored"),
        "failed": ("Attempted", str(len(updated)), "this run"),
    }.get(result, ("Updated", str(len(updated)), "this run"))
    stat = tiles([
        ("WordPress", esc(d.get("core_after") or d.get("core_before") or "?"), f"{d.get('plugin_count', '?')} plugins"),
        changes,
        ("Pages passing", pages, "smoke test"),
        ("Last backup", *((f"{fresh['hours']:.0f} h", "ago, in SharePoint") if fresh.get("hours") is not None
                          else ("—", "not checked") if not fresh.get("checked") else ("None", "no nightly found"))),
    ])

    sections = ""
    if fresh.get("stale"):
        sections += callout("warn", "The nightly backup may have stopped",
                            f"The newest nightly backup in SharePoint is <b>{esc(age)}</b>. Check the "
                            "<b>Back up production</b> runs in the Actions tab.")
    if result == "aborted":
        sections += callout("warn", "A site was failing before any update",
                            "Nothing was updated. Fix the failing page first; the next weekly run (or a manual "
                            "one) will then go ahead.")
    if result == "rolled_back":
        failing = [f"{re.sub(r'^https?://', '', p['url'])} — {p['note']}"
                   for p in parse_smoke(d.get("smoke_after", "")) if not p["ok"]]
        body = (esc(d.get("problem", "").capitalize())
                + "".join(f"<br>&bull; {esc(f)}" for f in failing)
                + "<br>Every update below was reversed and the sites were checked again.")
        if d.get("db_restored"):
            body += ("<br><b>The database was restored</b> from the pre-update backup because the schema changed. "
                     "Anything edited in wp-admin during the run was lost.")
        sections += callout("warn", "What went wrong", body)
    if result == "failed":
        sections += callout("fail", f"Stopped at: {step}",
                            esc(d.get("problem") or "The run did not finish. The log below shows where it stopped.")
                            + "<br>Check the sites now; the run may have stopped part-way through updating.")

    if result == "dry_run" and available:
        sections += section("Available", table(change_rows(available), ["Item", "Version"]))
    if updated and result in ("applied", "rolled_back", "failed"):
        sections += section("Updated" if result == "applied" else "Attempted", table(change_rows(updated), ["Item", "Version"]))
    if d.get("rollback"):
        sections += section("Rolled back", table(change_rows(d["rollback"]), ["Item", "Version"]))
    if d.get("not_updated"):
        sections += callout("warn", "Did not update",
                            esc(", ".join(d["not_updated"])) + " — check the run log (a premium plugin without a licence?).")
    if held:
        rows = [[esc(h["name"]), f'{chip(h["from"])} <span style="color:#94a3b8;">&rarr;</span> {chip(h["to"], "warn")}']
                for h in held]
        notes = "".join(f'<div style="font-size:12px;color:#64748b;padding-top:6px;">{esc(h["reason"])}</div>'
                        for h in held)
        sections += section("Held for approval", table(rows, ["Item", "Version"]) + notes)
    smoke_label, smoke_text = (("After rollback", d["smoke_rollback"]) if d.get("smoke_rollback")
                               else ("After updating", d["smoke_after"]) if d.get("smoke_after")
                               else ("Before updating", d.get("smoke_before", "")))
    if smoke_text:
        sections += section(f"Smoke test — {smoke_label.lower()}", smoke_table(smoke_text))
    pre = d.get("pre_backup")
    if pre:
        sections += section("Pre-update backup", kv([
            ("SharePoint", esc(f"Sterling Backups › {pre.get('FOLDER', '')}")),
            ("Database", esc(f"{size(pre.get('DB_SIZE'))} · {pre.get('VERIFIED', '')}")),
            ("Rollback copy", esc(f"~/{pre.get('REMOTE_DIR', '')} on the server")),
        ]))
    if result in ("failed", "rolled_back"):
        tail = read_text(log, tail=40)
        if tail:
            sections += section("Last lines of the run log", code(tail))

    buttons = [("View the run on GitHub", run_url(), True)]
    if held_major and result in ("applied", "nothing", "dry_run"):
        buttons.append(("Approve WordPress " + held_major, run_url().rsplit("/runs/", 1)[0] + "/workflows/update.yml"
                        if run_url() else "", False))
    return subject, content, stat, sections, buttons


# ---------------------------------------------------------------- assembly

def render(kind, out, status, log):
    subject, c, stat, sections, buttons = (backup_email if kind == "backup" else update_email)(out, status, log)
    pill_bg, pill_fg = TONES[c["pill"][1]]
    with open(os.path.join(EMAIL, "template.html"), encoding="utf-8") as fh:
        template = string.Template(fh.read())
    footer = ("Sent by the Sterling Properties maintenance automation, run by "
              "<b style=\"color:#334155;\">Brudda's Industries</b>.<br>"
              "Nightly backups 07:15 UTC · updates Tuesdays 09:00 UTC · "
              "details in <span style=\"font-family:Consolas,Menlo,monospace;\">docs/OPERATIONS.md</span>.")
    body = template.safe_substitute(
        subject=esc(subject), preheader=esc(c["preheader"]), client="Sterling Properties",
        job="Backup" if kind == "backup" else "Weekly updates",
        pill=esc(c["pill"][0]), pill_bg=pill_bg, pill_fg=pill_fg, title=esc(c["title"]), subtitle=c["subtitle"],
        tiles=stat, sections=sections, footer=footer,
        buttons="".join(button(label, href, primary) for label, href, primary in buttons) or "&nbsp;",
    )
    return subject, body


def main():
    parser = argparse.ArgumentParser(description=__doc__, formatter_class=argparse.RawDescriptionHelpFormatter)
    parser.add_argument("kind", choices=["backup", "update"])
    parser.add_argument("out")
    parser.add_argument("status")
    parser.add_argument("log", nargs="?", default="")
    parser.add_argument("--preview", help="write the HTML here instead of sending")
    args = parser.parse_args()
    if hasattr(sys.stdout, "reconfigure"):
        sys.stdout.reconfigure(encoding="utf-8", errors="replace")

    subject, body = render(args.kind, args.out, args.status, args.log)
    inline = [("logo", os.path.join(EMAIL, "logo-white.png")), ("stripe", os.path.join(EMAIL, "stripe.png"))]
    preview = args.preview or (None if os.environ.get("M365_CLIENT_ID") else os.path.join(args.out, "email.html"))
    if preview:
        for cid, path in inline:
            body = body.replace(f"cid:{cid}", "file:///" + path.replace(os.sep, "/"))
        os.makedirs(os.path.dirname(os.path.abspath(preview)), exist_ok=True)
        with open(preview, "w", encoding="utf-8") as fh:
            fh.write(body)
        print(f"Subject: {subject}\npreview: {preview}")
        return
    sys.path.insert(0, HERE)
    import graph
    graph.send_mail(subject, body, inline=inline)


if __name__ == "__main__":
    main()

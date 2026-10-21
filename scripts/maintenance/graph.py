#!/usr/bin/env python3
"""Microsoft Graph client for the maintenance workflows: SharePoint backup storage and email.

    graph.py mail --subject "<subject>" --body-file report.txt [--to a@x.com,b@y.com]
                                                     plain-text email (the branded ones come from notify.py)
    graph.py upload <local-file> <remote-path>       e.g. daily/2026-10-20_0715/db.sql.gz
    graph.py prune <tier> <days>                     delete <tier>/<YYYY-MM-DD...> folders older than <days>
    graph.py latest-age <tier> [<tier>...]           hours since the newest backup folder was created
    graph.py weburl <remote-path>                    browser link to a folder or file

Remote paths are relative to SP_BACKUP_ROOT (default "Sterling Backups") in drive SP_DRIVE_ID.

Environment: M365_TENANT_ID, M365_CLIENT_ID, M365_CLIENT_SECRET (an Entra app with Mail.Send and
Sites.Selected, see docs/OPERATIONS.md "Backups & updates"), M365_SENDER (the mailbox mail is sent
as), MAINT_NOTIFY_TO (default recipients, comma-separated), SP_DRIVE_ID.

Standard library only, so it runs on a bare GitHub runner and on the dev machine alike.
"""
import argparse
import datetime as dt
import html
import json
import os
import sys
import time
import urllib.error
import urllib.parse
import urllib.request

GRAPH = "https://graph.microsoft.com/v1.0"
# Upload-session chunks must be multiples of 320 KiB and at most 60 MiB.
CHUNK = 320 * 1024 * 192


def env(name, default=None):
    value = os.environ.get(name, default)
    if value is None or value == "":
        sys.exit(f"graph.py: {name} is not set")
    return value


def request(method, url, data=None, headers=None, token=None, retries=4):
    headers = dict(headers or {})
    if token:
        headers["Authorization"] = f"Bearer {token}"
    if isinstance(data, (dict, list)):
        data = json.dumps(data).encode()
        headers["Content-Type"] = "application/json"
    for attempt in range(retries + 1):
        req = urllib.request.Request(url, data=data, headers=headers, method=method)
        try:
            with urllib.request.urlopen(req, timeout=300) as resp:
                raw = resp.read()
                return json.loads(raw) if raw and "json" in resp.headers.get("Content-Type", "") else {}
        except urllib.error.HTTPError as err:
            if err.code in (429, 500, 502, 503, 504) and attempt < retries:
                time.sleep(int(err.headers.get("Retry-After") or 2 ** (attempt + 1)))
                continue
            detail = err.read().decode(errors="replace")[:500]
            raise SystemExit(f"graph.py: {method} {url.split('?')[0]} -> {err.code}: {detail}")
        except urllib.error.URLError as err:
            if attempt < retries:
                time.sleep(2 ** (attempt + 1))
                continue
            raise SystemExit(f"graph.py: {method} {url.split('?')[0]} failed: {err.reason}")


def get_token():
    tenant = env("M365_TENANT_ID")
    body = urllib.parse.urlencode({
        "grant_type": "client_credentials",
        "client_id": env("M365_CLIENT_ID"),
        "client_secret": env("M365_CLIENT_SECRET"),
        "scope": "https://graph.microsoft.com/.default",
    }).encode()
    url = f"https://login.microsoftonline.com/{tenant}/oauth2/v2.0/token"
    return request("POST", url, data=body,
                   headers={"Content-Type": "application/x-www-form-urlencoded"})["access_token"]


def drive_path(remote):
    root = os.environ.get("SP_BACKUP_ROOT") or "Sterling Backups"
    full = f"{root}/{remote}".strip("/")
    return f"{GRAPH}/drives/{env('SP_DRIVE_ID')}/root:/{urllib.parse.quote(full)}:"


def children(token, folder):
    """Items directly under <root>/<folder>, or [] if the folder does not exist yet."""
    url = drive_path(folder) + "/children?$select=id,name,createdDateTime,folder&$top=200"
    items = []
    while url:
        try:
            page = request("GET", url, token=token, retries=2)
        except SystemExit as err:
            if "-> 404" in str(err):
                return []
            raise
        items += page.get("value", [])
        url = page.get("@odata.nextLink")
    return items


def cmd_upload(args):
    size = os.path.getsize(args.file)
    if size == 0:
        sys.exit(f"graph.py: refusing to upload empty file {args.file}")
    token = get_token()
    session = request("POST", drive_path(args.remote) + "/createUploadSession",
                      data={"item": {"@microsoft.graph.conflictBehavior": "replace"}}, token=token)
    upload_url = session["uploadUrl"]
    with open(args.file, "rb") as fh:
        offset = 0
        while offset < size:
            chunk = fh.read(CHUNK)
            end = offset + len(chunk) - 1
            # The pre-authenticated upload URL must NOT carry the bearer token.
            request("PUT", upload_url, data=chunk, headers={
                "Content-Length": str(len(chunk)),
                "Content-Range": f"bytes {offset}-{end}/{size}",
            })
            offset = end + 1
    print(f"uploaded {args.remote} ({size} bytes)")


def cmd_prune(args):
    token = get_token()
    cutoff = dt.date.today() - dt.timedelta(days=args.days)
    for item in children(token, args.tier):
        try:
            day = dt.date.fromisoformat(item["name"][:10])
        except ValueError:
            continue  # not one of ours; leave it alone
        if "folder" in item and day < cutoff:
            request("DELETE", f"{GRAPH}/drives/{env('SP_DRIVE_ID')}/items/{item['id']}", token=token)
            print(f"pruned {args.tier}/{item['name']}")


def cmd_latest_age(args):
    token = get_token()
    newest = None
    for tier in args.tiers:
        for item in children(token, tier):
            if "folder" in item:
                created = dt.datetime.fromisoformat(item["createdDateTime"].replace("Z", "+00:00"))
                newest = created if newest is None or created > newest else newest
    if newest is None:
        print("none")
        return
    hours = (dt.datetime.now(dt.timezone.utc) - newest).total_seconds() / 3600
    print(f"{hours:.1f}")


def cmd_weburl(args):
    """The browser link for a folder or file, for the email's "Open in SharePoint" button."""
    print(request("GET", drive_path(args.remote) + "?$select=webUrl", token=get_token())["webUrl"])


def send_mail(subject, body_html, to=None, inline=()):
    """Send an HTML email as M365_SENDER. `inline` is a list of (content-id, path) images, which the
    HTML references as src="cid:<content-id>". Embedding them (rather than linking to a URL) means
    Outlook shows them without the "download pictures" prompt."""
    import base64
    recipients = [a.strip() for a in (to or env("MAINT_NOTIFY_TO")).split(",") if a.strip()]
    attachments = []
    for cid, path in inline:
        with open(path, "rb") as fh:
            attachments.append({
                "@odata.type": "#microsoft.graph.fileAttachment",
                "name": os.path.basename(path), "contentType": "image/png",
                "contentBytes": base64.b64encode(fh.read()).decode(), "contentId": cid, "isInline": True,
            })
    message = {
        "subject": subject,
        "body": {"contentType": "HTML", "content": body_html},
        "toRecipients": [{"emailAddress": {"address": a}} for a in recipients],
    }
    if attachments:
        message["attachments"] = attachments
    sender = env("M365_SENDER")
    request("POST", f"{GRAPH}/users/{urllib.parse.quote(sender)}/sendMail", token=get_token(),
            data={"message": message, "saveToSentItems": False})
    print(f"mailed {', '.join(recipients)}: {subject}")


def cmd_mail(args):
    with open(args.body_file, encoding="utf-8", errors="replace") as fh:
        text = fh.read()
    body = (f'<pre style="font:13px/1.45 Consolas,Menlo,monospace;white-space:pre-wrap">'
            f"{html.escape(text)}</pre>")
    send_mail(args.subject, body, args.to)


def main():
    parser = argparse.ArgumentParser(description=__doc__, formatter_class=argparse.RawDescriptionHelpFormatter)
    sub = parser.add_subparsers(dest="cmd", required=True)
    p = sub.add_parser("upload"); p.add_argument("file"); p.add_argument("remote"); p.set_defaults(fn=cmd_upload)
    p = sub.add_parser("prune"); p.add_argument("tier"); p.add_argument("days", type=int); p.set_defaults(fn=cmd_prune)
    p = sub.add_parser("latest-age"); p.add_argument("tiers", nargs="+"); p.set_defaults(fn=cmd_latest_age)
    p = sub.add_parser("weburl"); p.add_argument("remote"); p.set_defaults(fn=cmd_weburl)
    p = sub.add_parser("mail")
    p.add_argument("--subject", required=True); p.add_argument("--body-file", required=True); p.add_argument("--to")
    p.set_defaults(fn=cmd_mail)
    args = parser.parse_args()
    args.fn(args)


if __name__ == "__main__":
    main()

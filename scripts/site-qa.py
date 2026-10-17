"""Browser QA for a property preview or a live site (Playwright + Edge).

    python scripts/site-qa.py bellclair-montclair/preview            # every .html in the folder
    python scripts/site-qa.py https://bellclair.com/ https://bellclair.com/gallery/
    python scripts/site-qa.py rivergate-bordentown/preview-1 --widths 390,900,1440 --shots out/

A preview folder is served from its property folder, so the ../theme/ links resolve exactly as
they do on Vercel. Each page is loaded logged-out at each width and checked for console errors,
failed or 4xx/5xx requests, and sideways scroll (the page wider than the viewport). Full-page
heights are printed so two surfaces (preview vs WordPress) can be compared. 900px is in the
default widths because it is the theme's main breakpoint (OPERATIONS trap 5).

Exits 1 if anything failed. Use --ignore REGEX for known noise, e.g. the Google Maps key
refusing localhost: --ignore "RefererNotAllowed|maps.googleapis".
"""

import argparse
import functools
import http.server
import re
import socketserver
import sys
import threading
from pathlib import Path

from playwright.sync_api import sync_playwright


class QuietHandler(http.server.SimpleHTTPRequestHandler):
    def log_message(self, *args):
        pass


def serve(root: Path) -> str:
    handler = functools.partial(QuietHandler, directory=str(root))
    httpd = socketserver.ThreadingTCPServer(("127.0.0.1", 0), handler)
    threading.Thread(target=httpd.serve_forever, daemon=True).start()
    return f"http://127.0.0.1:{httpd.server_address[1]}"


def targets(args_targets):
    urls = []
    for t in args_targets:
        p = Path(t)
        if p.is_dir():
            base = serve(p.resolve().parent)
            pages = sorted(p.glob("*.html"), key=lambda f: (f.name != "index.html", f.name))
            urls += [f"{base}/{p.name}/{f.name}" for f in pages]
        else:
            urls.append(t)
    return urls


def main():
    ap = argparse.ArgumentParser(description=__doc__, formatter_class=argparse.RawDescriptionHelpFormatter)
    ap.add_argument("targets", nargs="+", help="preview folder(s) and/or URL(s)")
    ap.add_argument("--widths", default="390,900,1440")
    ap.add_argument("--shots", help="folder for full-page screenshots")
    ap.add_argument("--ignore", help="regex; matching console messages / URLs are not counted")
    a = ap.parse_args()

    widths = [int(w) for w in a.widths.split(",")]
    ignore = re.compile(a.ignore) if a.ignore else None
    shots = Path(a.shots) if a.shots else None
    if shots:
        shots.mkdir(parents=True, exist_ok=True)

    failures = 0
    with sync_playwright() as pw:
        try:
            browser = pw.chromium.launch(channel="msedge")
        except Exception:
            browser = pw.chromium.launch()
        for url in targets(a.targets):
            print(url)
            for w in widths:
                ctx = browser.new_context(viewport={"width": w, "height": 900})
                page = ctx.new_page()
                problems = []

                def keep(text):
                    return not (ignore and ignore.search(text))

                page.on("console", lambda m: m.type == "error" and keep(m.text) and problems.append(f"console: {m.text[:200]}"))
                page.on("pageerror", lambda e: keep(str(e)) and problems.append(f"js error: {str(e)[:200]}"))
                # ERR_ABORTED is the browser cancelling a request itself (video range requests, preloads);
                # real missing files still show up through the response handler below.
                page.on("requestfailed", lambda r: keep(r.url) and "ABORTED" not in (r.failure or "")
                        and problems.append(f"failed: {r.url} ({r.failure})"))
                page.on("response", lambda r: r.status >= 400 and keep(r.url) and problems.append(f"{r.status}: {r.url}"))

                try:
                    # Not "networkidle": a looping hero video keeps the network busy forever.
                    page.goto(url, wait_until="load", timeout=60000)
                except Exception as e:
                    problems.append(f"load: {e}")
                page.wait_for_timeout(1500)
                m = page.evaluate("() => ({sw: document.documentElement.scrollWidth, iw: innerWidth, h: document.documentElement.scrollHeight})")
                if m["sw"] > m["iw"]:
                    problems.append(f"sideways scroll: page is {m['sw']}px wide in a {m['iw']}px viewport")
                if shots:
                    name = re.sub(r"[^a-z0-9]+", "-", url.lower().split("//", 1)[-1]).strip("-")
                    page.screenshot(path=str(shots / f"{name}-{w}.png"), full_page=True)

                status = "ok" if not problems else f"{len(problems)} problem(s)"
                print(f"  {w:>5}px  height {m['h']:>6}px  {status}")
                for p in dict.fromkeys(problems):
                    print(f"           - {p}")
                failures += bool(problems)
                ctx.close()
        browser.close()
    sys.exit(1 if failures else 0)


if __name__ == "__main__":
    main()

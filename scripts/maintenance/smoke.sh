#!/usr/bin/env bash
# Is every live property still serving? Used before and after the weekly update run, and safe to
# run by hand at any time (read-only).
#
#   scripts/maintenance/smoke.sh [sites-file]
#
# For each URL in sites.txt: the final status after redirects must be 200, the page must load a
# theme stylesheet from /wp-content/themes/ (a white-screen or a fallback theme would not), and it
# must not carry WordPress's fatal-error or database-error text. Exits non-zero if any URL fails.
# Requests carry a cache-busting query string so Varnish cannot answer with a stale good copy.
set -uo pipefail
cd "$(dirname "$0")"
sites="${1:-sites.txt}"
body=$(mktemp); trap 'rm -f "$body"' EXIT
stamp=$(date +%s)
fail=0

while read -r domain paths; do
  case "$domain" in ''|'#'*) continue ;; esac
  for path in $paths; do
    [ "$path" = "/" ] && path=""
    url="https://$domain/$path"
    code=$(curl -sS -L -o "$body" -w '%{http_code}' --max-time 30 "$url?smoke=$stamp" 2>/dev/null) || code="000"
    problem=""
    if [ "$code" != "200" ]; then problem="HTTP $code"
    elif grep -qiE 'There has been a critical error|Error establishing a database connection' "$body"; then problem="WordPress error page"
    elif ! grep -qE '/wp-content/themes/[a-z0-9-]+/[^"]*\.css' "$body"; then problem="no theme stylesheet in page"
    fi
    if [ -n "$problem" ]; then
      printf 'FAIL  %-45s %s\n' "$url" "$problem"; fail=1
    else
      printf 'ok    %-45s %s\n' "$url" "$code"
    fi
  done
done < "$sites"

exit "$fail"

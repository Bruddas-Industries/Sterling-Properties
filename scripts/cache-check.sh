#!/usr/bin/env bash
# What is a logged-out visitor actually being served? Varnish can hand out a page days old while
# an admin sees it fresh (OPERATIONS "Caching"), so check this before concluding a deploy failed.
#
#   scripts/cache-check.sh <url> [<url>...]
#
# For each URL: final HTTP status, x-cache (HIT = from Varnish), age in seconds, and the theme
# version the page's global.css is loaded with — compared against that theme's version on
# origin/main. A HIT with an old version means: purge (scripts/prod.sh purge <domain>).
set -uo pipefail
cd "$(dirname "$0")/.."
git fetch -q origin main 2>/dev/null || true

for url in "$@"; do
  headers=$(curl -sSI -L --max-time 30 "$url" | tr -d '\r')
  status=$(printf '%s\n' "$headers" | awk '/^HTTP\//{s=$2} END{print s}')
  xcache=$(printf '%s\n' "$headers" | awk -F': ' 'tolower($1)=="x-cache"{v=$2} END{print v ? v : "-"}')
  age=$(printf '%s\n' "$headers" | awk -F': ' 'tolower($1)=="age"{v=$2} END{print v ? v : "-"}')

  css=$(curl -sS -L --max-time 30 "$url" | grep -oE '/wp-content/themes/[a-z0-9-]+/assets/css/global\.css\?ver=[0-9.]+' | head -1)
  served="${css##*ver=}"
  slug=$(printf '%s' "$css" | sed -nE 's#/wp-content/themes/([a-z0-9-]+)/.*#\1#p')

  expected="-"
  if [ -n "$slug" ]; then
    expected=$(git show "origin/main:$slug/theme/$slug/functions.php" 2>/dev/null \
      | sed -nE "s/.*define\( *'[A-Z_]+_VERSION', *'([0-9.]+)'.*/\1/p" | head -1)
  fi

  verdict="ok"
  if [ -z "$css" ]; then verdict="no theme CSS found"
  elif [ -n "$expected" ] && [ "$served" != "$expected" ]; then
    verdict="STALE: serving $served, main is $expected"
    case "$xcache" in *HIT*|*hit*) verdict="$verdict (Varnish HIT, age ${age}s — purge)";; esac
  fi
  printf '%s\n  status %s | x-cache %s | age %s | theme %s %s | %s\n' "$url" "$status" "$xcache" "$age" "${slug:--}" "${served:--}" "$verdict"
done

#!/usr/bin/env bash
# Run things on the Cloudways production server without retyping the SSH coordinates.
#
#   scripts/prod.sh wp <args...>                     WP-CLI from the WordPress root (add --url=<domain>)
#   scripts/prod.sh sh '<shell command>'             any command, from the WordPress root
#   scripts/prod.sh purge <domain>                   purge Varnish for one hostname
#   scripts/prod.sh backup <domain> <label>          export that site's tables to ~/premigration/<label>/
#   scripts/prod.sh edit <domain> <post-id> <spec> [apply]
#                                                    safe page-content edit (scripts/safe-edit.php);
#                                                    dry run unless "apply" is given
#   scripts/prod.sh insert-page <domain> <pattern-slug> <title> [slug=<s>] [front] [draft] [apply]
#                                                    create a page from a registered pattern
#                                                    (scripts/insert-page.php); dry run unless "apply"
#
# Coordinates come from scripts/deploy.env when present (untracked), else the values documented in
# docs/OPERATIONS.md. Override any of them with STERLING_SSH_HOST / STERLING_APP_PATH / STERLING_SSH_KEY.
set -euo pipefail
export MSYS_NO_PATHCONV=1

here="$(cd "$(dirname "$0")" && pwd)"
# shellcheck disable=SC1091
[ -f "$here/deploy.env" ] && . "$here/deploy.env"
HOST="${STERLING_SSH_HOST:-master_avjztmdgms@142.93.71.107}"
APP="${STERLING_APP_PATH:-/home/master/applications/xhxwssuusk/public_html}"
KEY="${STERLING_SSH_KEY:-$HOME/.ssh/cloudways_sterling}"
TOOLS='~/premigration/tools'

ssh_opts=(-i "$KEY" -o IdentitiesOnly=yes -o BatchMode=yes -o ConnectTimeout=15)

remote() { ssh "${ssh_opts[@]}" "$HOST" "cd $APP && $1"; }
quote() { [ $# -eq 0 ] || printf '%q ' "$@"; }

# Copy a local file into ~/premigration/tools/ on the server.
push() {
  ssh "${ssh_opts[@]}" "$HOST" "mkdir -p $TOOLS"
  scp -q "${ssh_opts[@]}" "$1" "$HOST:premigration/tools/$(basename "$1")"
}

cmd="${1:-}"; shift || true
case "$cmd" in
  wp)
    remote "wp $(quote "$@")"
    ;;
  sh)
    remote "$1"
    ;;
  purge)
    domain="${1:?usage: prod.sh purge <domain>}"
    remote "curl -s -o /dev/null -w 'PURGE $domain -> %{http_code}\n' -X PURGE -H $(quote "Host: $domain") -H 'X-Purge-Method: regex' 'http://127.0.0.1:8080/.*'"
    ;;
  backup)
    domain="${1:?usage: prod.sh backup <domain> <label>}"; label="${2:?usage: prod.sh backup <domain> <label>}"
    remote "mkdir -p ~/premigration/$label && f=~/premigration/$label/\$(echo $domain | tr . -)-\$(date +%Y%m%d-%H%M%S).sql && wp db export \"\$f\" --tables=\$(wp db tables --url=$domain --format=csv) --url=$domain --quiet && ls -l \"\$f\""
    ;;
  edit)
    domain="${1:?usage: prod.sh edit <domain> <post-id> <spec> [apply]}"; id="${2:?post id}"; spec="${3:?spec file}"; shift 3
    push "$here/safe-edit.php"; push "$spec"
    # $TOOLS stays unquoted so the remote shell expands its ~.
    remote "wp eval-file $TOOLS/safe-edit.php $(quote "$id") $TOOLS/$(quote "$(basename "$spec")") $(quote "$@") --url=$domain"
    ;;
  insert-page)
    domain="${1:?usage: prod.sh insert-page <domain> <pattern-slug> <title> [...]}"; shift
    push "$here/insert-page.php"
    remote "wp eval-file $TOOLS/insert-page.php $(quote "$@") --url=$domain"
    ;;
  *)
    sed -n '2,20p' "$0"; exit 1
    ;;
esac

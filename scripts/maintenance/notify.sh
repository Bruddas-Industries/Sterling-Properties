#!/usr/bin/env bash
# Email the outcome of a maintenance run (success or failure) through Microsoft Graph.
#
#   scripts/maintenance/notify.sh backup|update <out-dir> <job-status> [<log-file>]
#
# <job-status> is the workflow's job.status (success / failure / cancelled). The subject comes
# from <out-dir>/summary.env (backup) or result.env (update); the body is <out-dir>/report.txt,
# plus the last lines of <log-file> when something went wrong. Without M365 credentials (a local
# run) it prints the email instead of sending it.
set -uo pipefail
here="$(cd "$(dirname "$0")" && pwd)"
kind="${1:?usage: notify.sh backup|update <out-dir> <job-status> [<log-file>]}"
out="${2:?out-dir}"; status="${3:?job-status}"; log="${4:-}"
day=$(date -u +%F)

# shellcheck disable=SC1090,SC1091
[ -f "$out/summary.env" ] && . "$out/summary.env"
# shellcheck disable=SC1090,SC1091
[ -f "$out/result.env" ] && . "$out/result.env"
where=$(cat "$out/step" 2>/dev/null || echo "setup")
ok=0; [ "$status" = "success" ] && ok=1

case "$kind" in
  backup)
    if [ "$ok" = 1 ]; then
      subject="[Sterling] Backup OK — $day · DB ${DB_SIZE:-?} · uploads ${UPLOADS_SIZE:-?} · ${VERIFIED:-?}"
    else
      subject="[Sterling] Backup FAILED at: $where"
    fi ;;
  update)
    case "${RESULT:-failed}" in
      applied)     subject="[Sterling] Updates applied (${COUNT:-?}) — all sites passing" ;;
      nothing)     subject="[Sterling] No updates needed — all sites passing" ;;
      dry_run)     subject="[Sterling] Update check (dry run) — ${COUNT:-0} available" ;;
      aborted)     subject="[Sterling] Updates SKIPPED — a site was already failing" ;;
      rolled_back) subject="[Sterling] Updates ROLLED BACK — sites restored, needs a look" ;;
      *)           subject="[Sterling] Updates FAILED — manual attention needed" ;;
    esac
    [ -n "${HELD_MAJOR:-}" ] && subject="$subject · WordPress $HELD_MAJOR awaiting approval"
    if [ "$ok" = 0 ] && [ "${RESULT:-}" != "aborted" ] && [ "${RESULT:-}" != "rolled_back" ]; then
      subject="[Sterling] Updates FAILED at: $where — manual attention needed"
    fi ;;
  *) echo "unknown kind: $kind" >&2; exit 2 ;;
esac

body=$(mktemp); trap 'rm -f "$body"' EXIT
{
  cat "$out/report.txt" 2>/dev/null || echo "(no report was written — the run stopped before its first step)"
  if [ "$ok" = 0 ] && [ -f "$log" ]; then
    printf '\n---- last 60 lines of the run log ----\n'
    tail -n 60 "$log"
  fi
} > "$body"

if [ -z "${M365_CLIENT_ID:-}" ]; then
  echo "Subject: $subject"; echo; cat "$body"
  exit 0
fi
"${PYTHON:-python3}" "$here/graph.py" mail --subject "$subject" --body-file "$body"

#!/usr/bin/env bash
# Back up the production network: export on the server, download, prove the dump restores, and
# keep a copy in SharePoint. Run nightly by .github/workflows/backup.yml and before every update
# run; also runs by hand from Git Bash (it then skips the import test and, without SP_DRIVE_ID,
# the upload, leaving the files in <out-dir>).
#
#   scripts/maintenance/backup.sh <out-dir>
#
# Environment:
#   BACKUP_LABEL    tier to file it under, e.g. pre-update. Default: monthly on the 1st, weekly on
#                   Sundays, otherwise daily (UTC dates)
#   BACKUP_UPLOADS  always (default; uploads were 29 MB in 2026-10) | weekly (weekly and monthly
#                   tiers only, for when the media library grows large) | never
#   VERIFY_DOCKER   id of a MariaDB container to test-import the dump into (the workflows' service)
#   VERIFY_DB_PASS  that container's root password
#   SP_DRIVE_ID     SharePoint drive to upload to (plus the M365_* variables graph.py needs)
#   REQUIRE_UPLOAD  1 = fail when SP_DRIVE_ID is unset, rather than keep the files locally
#   PYTHON          interpreter for graph.py (default python3; set PYTHON=python on Windows)
#
# Writes <out-dir>/summary.env and report.txt for the email, and <out-dir>/step naming the step in
# progress, so a failure email can say where it stopped.
set -euo pipefail
here="$(cd "$(dirname "$0")" && pwd)"
prod="$here/../prod.sh"
out="${1:?usage: backup.sh <out-dir>}"
mkdir -p "$out"
PY="${PYTHON:-python3}"

step() { echo "$1" > "$out/step"; echo "== $1"; }
note() { echo "$*" | tee -a "$out/report.txt"; }
fail() { echo "ERROR: $*" >&2; note "ERROR: $*"; exit 1; }

ts=$(date -u +%Y-%m-%d_%H%M)
tier="${BACKUP_LABEL:-}"
if [ -z "$tier" ]; then
  if [ "$(date -u +%d)" = "01" ]; then tier=monthly
  elif [ "$(date -u +%u)" = "7" ]; then tier=weekly
  else tier=daily; fi
fi
case "${BACKUP_UPLOADS:-always}" in
  never)  uploads=0 ;;
  weekly) case "$tier" in weekly|monthly) uploads=1 ;; *) uploads=0 ;; esac ;;
  *)      uploads=1 ;;
esac
name="$ts-$tier"

step "export on the server"
bash "$prod" sh "bash -s -- $name $uploads" < "$here/remote-backup.sh" | tee "$out/remote.txt"
val() { sed -n "s/^$1=//p" "$out/remote.txt" | tail -1; }
remote_dir=$(val REMOTE_DIR); sites=$(val SITES); disk_free=$(val DISK_FREE)
[ -n "$remote_dir" ] || fail "the server-side export did not finish"

step "download"
bash "$prod" pull "$remote_dir" "$out/"
dir="$out/$name"
[ -s "$dir/db.sql.gz" ] || fail "db.sql.gz did not download"

step "verify"
gzip -t "$dir/db.sql.gz" || fail "db.sql.gz is corrupt"
gzip -dc "$dir/db.sql.gz" | tail -c 400 | grep -q 'Dump completed' \
  || fail "the dump has no 'Dump completed' trailer, so the export stopped part-way"
(cd "$dir" && sha256sum db.sql.gz meta.tar.gz $( [ -f uploads.tar ] && echo uploads.tar ) > SHA256SUMS)

verified="not import-tested"
if [ -n "${VERIFY_DOCKER:-}" ]; then
  dbq() { docker exec -i -e MYSQL_PWD="${VERIFY_DB_PASS:-}" "$VERIFY_DOCKER" mariadb -uroot -N "$@"; }
  dbq -e 'DROP DATABASE IF EXISTS restore_test; CREATE DATABASE restore_test'
  gzip -dc "$dir/db.sql.gz" | dbq restore_test || fail "the dump did not import into MariaDB"
  blogs=$(dbq restore_test -e "SELECT table_name FROM information_schema.tables
            WHERE table_schema='restore_test' AND table_name LIKE '%blogs' ORDER BY LENGTH(table_name) LIMIT 1")
  [ -n "$blogs" ] || fail "restored database has no blogs table"
  prefix="${blogs%blogs}"
  ids=$(dbq restore_test -e "SELECT blog_id FROM \`$blogs\` ORDER BY blog_id")
  count=$(echo "$ids" | wc -w | tr -d ' ')
  [ "$count" = "$sites" ] || fail "restored database has $count sites; the server reports $sites"
  detail=""
  for id in $ids; do
    table="${prefix}${id}_posts"; [ "$id" = "1" ] && table="${prefix}posts"
    n=$(dbq restore_test -e "SELECT COUNT(*) FROM \`$table\` WHERE post_status='publish'")
    detail="$detail blog $id: $n published;"
    # The network root (blog 1) may legitimately be empty; a property site never is.
    [ "$id" = "1" ] || [ "$n" -gt 0 ] || fail "restored blog $id has no published posts or pages"
  done
  dbq -e 'DROP DATABASE restore_test'
  verified="import-verified"
fi

db_size=$(du -h "$dir/db.sql.gz" | cut -f1)
uploads_size="not included"; [ -f "$dir/uploads.tar" ] && uploads_size=$(du -h "$dir/uploads.tar" | cut -f1)
folder="$tier/$ts"

if [ -n "${SP_DRIVE_ID:-}" ]; then
  step "upload to SharePoint"
  for f in db.sql.gz meta.tar.gz uploads.tar SHA256SUMS; do
    if [ -f "$dir/$f" ]; then "$PY" "$here/graph.py" upload "$dir/$f" "$folder/$f"; fi
  done
  step "prune old SharePoint backups"
  "$PY" "$here/graph.py" prune daily 14
  "$PY" "$here/graph.py" prune weekly 56
  "$PY" "$here/graph.py" prune monthly 366
  "$PY" "$here/graph.py" prune pre-update 30
  stored="SharePoint: Sterling Backups/$folder/"
elif [ "${REQUIRE_UPLOAD:-0}" = "1" ]; then
  fail "SP_DRIVE_ID is not set, so the backup cannot leave the server"
else
  stored="local only: $dir"
fi

step "tidy the server"
# The uploads archive is only a transfer copy. Keep the two newest database exports on the server
# for a fast restore (the newest is what an update run rolls back to).
bash "$prod" sh "rm -f ~/$remote_dir/uploads.tar; ls -1dt ~/backups/*/ | tail -n +3 | xargs -r rm -rf --"

cat > "$out/summary.env" <<EOF
TIER=$tier
FOLDER=$folder
REMOTE_DIR=$remote_dir
DB_SIZE=$db_size
UPLOADS_SIZE=$uploads_size
VERIFIED=$verified
EOF

note "Backup $name"
note "  database      $db_size (gzip), $sites sites, $verified"
[ -n "${detail:-}" ] && note "               ${detail# }"
note "  uploads       $uploads_size"
note "  stored        $stored"
note "  on server     ~/$remote_dir (newest two kept), $disk_free free"
step "done"

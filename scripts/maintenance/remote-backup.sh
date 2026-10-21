#!/usr/bin/env bash
# Runs ON THE SERVER, from the WordPress root. backup.sh streams it over SSH; do not run it locally.
#
#   bash -s -- <name> <with-uploads 0|1> < remote-backup.sh
#
# Writes ~/backups/<name>/:
#   db.sql.gz      the whole network database (every blog's tables, plus wp_blogs/wp_site,
#                  which a multisite cannot be restored without)
#   meta.tar.gz    every theme's acf-json/ (production owns these; git does not, see OPERATIONS
#                  "ACF field groups"), mu-plugins/ if present, and manifest/
#   manifest/      core version, db_version, plugin/theme/site lists
#   uploads.tar    wp-content/uploads (only when asked; images do not compress, so no gzip)
# and prints KEY=value lines (sizes, site count) for backup.sh to collect.
set -euo pipefail
umask 077
name="$1"; with_uploads="${2:-0}"
dest="$HOME/backups/$name"
mkdir -p "$dest/manifest"

echo "STEP=database"
wp db export - --quiet | gzip -6 > "$dest/db.sql.gz"

echo "STEP=manifest"
wp core version                                          > "$dest/manifest/core-version.txt"
wp option get db_version                                 > "$dest/manifest/db-version.txt"
wp plugin list --format=json                             > "$dest/manifest/plugins.json"
wp theme list --format=json                              > "$dest/manifest/themes.json"
wp site list --fields=blog_id,url --format=json          > "$dest/manifest/sites.json"

echo "STEP=config"
extras=$(cd wp-content && ls -d themes/*/acf-json mu-plugins 2>/dev/null || true)
# shellcheck disable=SC2086
tar -czf "$dest/meta.tar.gz" -C wp-content $extras -C "$dest" manifest

if [ "$with_uploads" = "1" ]; then
  echo "STEP=uploads"
  need_kb=$(du -sk wp-content/uploads | cut -f1)
  free_kb=$(df -Pk "$dest" | awk 'NR==2 {print $4}')
  if [ "$free_kb" -lt $((need_kb + 1048576)) ]; then
    echo "Not enough disk for the uploads archive: need ${need_kb} KB + 1 GB headroom, have ${free_kb} KB" >&2
    exit 1
  fi
  tar -cf "$dest/uploads.tar" -C wp-content uploads
fi

echo "SITES=$(wp site list --format=count)"
echo "DISK_FREE=$(df -Ph "$dest" | awk 'NR==2 {print $4}')"
echo "REMOTE_DIR=backups/$name"

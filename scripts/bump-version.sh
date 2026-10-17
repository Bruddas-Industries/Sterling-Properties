#!/usr/bin/env bash
# Bump a property theme's version: the <PREFIX>_VERSION constant in functions.php (which busts
# CSS/JS caches) and the Version: header in style.css (which wp-admin displays), together.
#
#   scripts/bump-version.sh <property-dir> [patch|minor|major|X.Y.Z]    (default: patch)
#   scripts/bump-version.sh bellclair-montclair            1.0.5 -> 1.0.6
#   scripts/bump-version.sh rivergate-bordentown minor     1.17.3 -> 1.18.0
set -euo pipefail
cd "$(dirname "$0")/.."

prop="${1:?usage: bump-version.sh <property-dir> [patch|minor|major|X.Y.Z]}"
prop="${prop%/}"
level="${2:-patch}"
theme="$prop/theme/$prop"
funcs="$theme/functions.php"
style="$theme/style.css"
[ -f "$funcs" ] && [ -f "$style" ] || { echo "No theme at $theme" >&2; exit 1; }

const_re="define\( *'([A-Z_]+_VERSION)', *'([0-9]+\.[0-9]+\.[0-9]+)'"
current=$(sed -nE "s/.*$const_re.*/\2/p" "$funcs" | head -1)
name=$(sed -nE "s/.*$const_re.*/\1/p" "$funcs" | head -1)
header=$(sed -nE 's/^Version:[[:space:]]*([0-9.]+).*/\1/p' "$style" | head -1)
[ -n "$current" ] || { echo "No *_VERSION constant found in $funcs" >&2; exit 1; }
[ "$header" = "$current" ] || echo "note: style.css said $header, functions.php said $current — both will be set to the new version"

IFS=. read -r major minor patch <<<"$current"
case "$level" in
  patch) new="$major.$minor.$((patch + 1))" ;;
  minor) new="$major.$((minor + 1)).0" ;;
  major) new="$((major + 1)).0.0" ;;
  [0-9]*.[0-9]*.[0-9]*) new="$level" ;;
  *) echo "Unknown level: $level" >&2; exit 1 ;;
esac

sed -i -E "s/(define\( *'$name', *')[0-9.]+(')/\1$new\2/" "$funcs"
sed -i -E "s/^(Version:[[:space:]]*)[0-9.]+/\1$new/" "$style"

echo "$name: $current -> $new"
grep -n "$name'," "$funcs" | head -1
grep -n '^Version:' "$style"

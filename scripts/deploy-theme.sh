#!/usr/bin/env bash
#
# Deploy one property theme from this monorepo to the Cloudways multisite.
#
#   scripts/deploy-theme.sh rivergate-bordentown            # dry run (default)
#   scripts/deploy-theme.sh rivergate-bordentown --live     # actually deploy
#
# Source is `git archive HEAD:<property>/theme/<property>`, not the working
# tree. Two reasons, both learned the hard way:
#
#   1. Git on Windows checks files out with CRLF. Packing the working tree sent
#      CRLF to a server holding LF, so every single PHP and CSS file showed as
#      modified on every deploy and the endings got rewritten server-side.
#      git archive emits repository content, which is LF.
#   2. It guarantees you deploy committed code. Uncommitted local experiments
#      cannot reach production by accident.
#
# Local side needs only git + ssh (no rsync — Git Bash on Windows has none).
# The tarball is unpacked into a staging directory on the server and the
# server's rsync does the delete-aware sync, so files removed from the repo are
# also removed from the server.
#
# Known cost: the whole theme (~63MB, of which ~53MB is assets/images) is
# uploaded to staging every run, because the local side has no rsync to
# negotiate deltas with. The server-side sync is checksum-based so nothing
# unchanged is actually rewritten, but the upload itself is not incremental.
# Fine for occasional deploys; if it becomes annoying, the fix is to record the
# deployed commit on the server and archive only `git diff <deployed>..HEAD`.
#
# Connection details are NOT committed. Copy scripts/deploy.env.example to
# scripts/deploy.env (gitignored) and fill it in, or export the same variables.

set -euo pipefail

REPO_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$REPO_ROOT"

# ---------------------------------------------------------------- config ----
if [[ -f "$REPO_ROOT/scripts/deploy.env" ]]; then
	# shellcheck disable=SC1091
	source "$REPO_ROOT/scripts/deploy.env"
fi

SSH_HOST="${STERLING_SSH_HOST:-}"
APP_PATH="${STERLING_APP_PATH:-}"
SSH_KEY="${STERLING_SSH_KEY:-$HOME/.ssh/cloudways_sterling}"

die() { printf '\nerror: %s\n\n' "$1" >&2; exit 1; }

[[ -n "$SSH_HOST" ]] || die "STERLING_SSH_HOST is not set. Copy scripts/deploy.env.example to scripts/deploy.env and fill it in."
[[ -n "$APP_PATH" ]] || die "STERLING_APP_PATH is not set. Copy scripts/deploy.env.example to scripts/deploy.env and fill it in."

# ----------------------------------------------------------------- args -----
PROPERTY="${1:-}"
MODE="${2:---dry-run}"

[[ -n "$PROPERTY" ]] || die "usage: scripts/deploy-theme.sh <property> [--live]"

case "$MODE" in
	--live)    RSYNC_FLAGS="";          BANNER="LIVE DEPLOY" ;;
	--dry-run) RSYNC_FLAGS="--dry-run"; BANNER="DRY RUN (nothing will change — pass --live to apply)" ;;
	*)         die "unknown option '$MODE' — expected --live or --dry-run" ;;
esac

THEME_PATH="$PROPERTY/theme/$PROPERTY"
REMOTE_THEME="$APP_PATH/wp-content/themes/$PROPERTY"
# Absolute on purpose: this path is passed inside single quotes over ssh, so a
# $HOME here would never expand on the remote side.
REMOTE_STAGE="/tmp/sterling-deploy/$PROPERTY"

git rev-parse --git-dir >/dev/null 2>&1 || die "not a git repository"
git cat-file -e "HEAD:$THEME_PATH" 2>/dev/null || die "no theme committed at $THEME_PATH (deploy reads from git, not the working tree)"
git cat-file -e "HEAD:$THEME_PATH/style.css" 2>/dev/null || die "$THEME_PATH has no style.css — is that really a theme?"

# --------------------------------------------------- version sanity check ---
# style.css is what wp-admin displays; the constant is what busts asset caches.
# They drifted badly once (1.4.0 vs 1.16.x), so warn rather than let it recur.
HEADER_VER="$(git show "HEAD:$THEME_PATH/style.css" | grep -m1 -E '^Version:' | sed -E 's/^Version:[[:space:]]*//' | tr -d '\r')"
CONST_VER="$(git show "HEAD:$THEME_PATH/functions.php" 2>/dev/null | grep -m1 -oE "_VERSION',[[:space:]]*'[^']+'" | sed -E "s/.*'([^']+)'\$/\1/")"
REF_DESC="$(git rev-parse --short HEAD) on $(git rev-parse --abbrev-ref HEAD)"

printf '\n  %s\n\n' "$BANNER"
printf '  property    %s\n'   "$PROPERTY"
printf '  source      %s (from git, not the working tree)\n' "$REF_DESC"
printf '  target      %s\n'   "$REMOTE_THEME"
printf '  style.css   %s\n'   "${HEADER_VER:-?}"
printf '  constant    %s\n\n' "${CONST_VER:-?}"

if [[ -n "$HEADER_VER" && -n "$CONST_VER" && "$HEADER_VER" != "$CONST_VER" ]]; then
	printf '  warning: style.css (%s) and the version constant (%s) disagree.\n' "$HEADER_VER" "$CONST_VER"
	printf '           wp-admin shows the header; only the constant busts caches.\n\n'
fi

if ! git diff --quiet HEAD -- "$THEME_PATH" 2>/dev/null; then
	printf '  warning: you have uncommitted changes under %s.\n' "$THEME_PATH"
	printf '           They will NOT be deployed — this ships %s.\n\n' "$(git rev-parse --short HEAD)"
fi

# ---------------------------------------------------------------- deploy ----
SSH_OPTS=(-o ConnectTimeout=20)
[[ -f "$SSH_KEY" ]] && SSH_OPTS+=(-i "$SSH_KEY")

printf '  → packing and uploading...\n'
git archive --format=tar "HEAD:$THEME_PATH" \
| gzip \
| ssh "${SSH_OPTS[@]}" "$SSH_HOST" \
	"rm -rf '$REMOTE_STAGE' && mkdir -p '$REMOTE_STAGE' && tar xzf - -C '$REMOTE_STAGE'"

# --checksum, not the default size+mtime heuristic: git archive stamps every
# file with the commit timestamp, so on each new commit rsync would consider all
# ~700 files "changed" and rewrite the whole theme — 50MB+ of untouched images
# included. Checksumming compares content, so only genuinely changed files are
# written. (In --dry-run rsync still prints a 'c' for files it would consider;
# that column is not reliable in dry-run mode, so expect noisier output here
# than a live run actually produces.)
printf '  → syncing into wp-content/themes...\n\n'
ssh "${SSH_OPTS[@]}" "$SSH_HOST" \
	"rsync -a --checksum --delete $RSYNC_FLAGS --itemize-changes '$REMOTE_STAGE/' '$REMOTE_THEME/'"

ssh "${SSH_OPTS[@]}" "$SSH_HOST" "rm -rf '$REMOTE_STAGE'"

# ----------------------------------------------------------------- after ----
if [[ "$MODE" == "--live" ]]; then
	printf '\n  → deployed. Version now installed:\n'
	ssh "${SSH_OPTS[@]}" "$SSH_HOST" \
		"wp theme get '$PROPERTY' --field=version --path='$APP_PATH' 2>/dev/null || echo '(could not read)'" \
		| sed 's/^/     /'
	cat <<-'EOF'

	  Two things this script deliberately does NOT do:

	    1. Purge Varnish. Logged-out visitors keep seeing the cached page until
	       you purge it from the Cloudways panel, so a change can look like it
	       never deployed. Purge before judging the result.

	    2. Touch page content. Block patterns are copy-on-insert — pages that
	       already exist in the database keep their old markup. Pattern edits
	       need a matching change in wp-admin or a scoped search-replace.

	EOF
else
	printf '\n  Dry run only. Re-run with --live to apply.\n\n'
fi

---
name: edit-site-prod
description: Make changes to a Sterling Properties site that is running on WordPress — rivergatenj.com, bellclair.com, any later property on the Cloudways multisite, or its local copy at sterling-network.local. Covers client edit requests on a launched site (copy, photos, hours, emails, links), theme code (CSS, blocks, patterns, PHP, SEO), version bumps and deploys, block attributes, settings, menus, ACF, Varnish purges, and checking what visitors actually see. Use this whenever the user asks to change, fix, deploy, purge or verify anything on a live property or its WordPress theme, even if they never say "production" — e.g. "Nina wants the office hours changed on Bellclair", "the new CSS isn't showing on rivergatenj.com", "ship this fix". For a property that only exists as a static preview, use edit-site-dev instead.
---

# Editing a live property site

All the property sites run as one WordPress multisite on Cloudways, and each site's theme lives
in this repo. A change can live in one of three places: page content in the database, theme code
in the repo, or site settings. Each place reaches visitors by a different route. Most of the
expensive mistakes on this project came from fixing the right thing in the wrong place. The
classic case is editing a pattern file and expecting a live page to change. So classify first,
then follow that path.

`docs/OPERATIONS.md` and `docs/STATUS.md` are authoritative for facts such as hosts, blog IDs,
the traps and the current state. This skill is the procedure. Where it says "trap N", that is
the numbered list in OPERATIONS → Traps.

## Before you start

1. **Read the property's section of `docs/STATUS.md`.** Note its theme version, its live URL,
   the "Changes made directly in the database" table, its open items and the
   **client-verified copy constraints**. Any copy you write has to respect those constraints.
2. **Identify the target.** The folder name, the theme slug and the Local path are the same
   string (e.g. `bellclair-montclair`). The function, constant and CPT prefixes are in that
   theme's `functions.php`. Blog IDs and domains are in OPERATIONS → "The network". The deploy
   workflow is `.github/workflows/deploy-<name>.yml`.
3. **Scope everything to one site.** This is a single multisite, so every WP-CLI command gets
   `--url=<domain>`. Without it a command acts on the network root (blog 1).

## Step 1 — Classify the change

| What is changing | Where it lives | How it reaches visitors | Path |
|---|---|---|---|
| Words, images, buttons or links in a section built from a **pattern** | `post_content` in the database | Only through a database edit. Editing the pattern file changes nothing on existing pages (trap 1) | **A** |
| An **attribute** of a dynamic block (contact-form recipient, hero video, sidebar fields) | The block comment's JSON in `post_content` | Database edit | **A** |
| The **output** of a dynamic block (amenities, floor plans, neighborhood explorer, hero, contact form), including the fallback data in `blocks/_shared.php` and the explorer's `render.php` | Theme PHP | Deploy | **B** |
| CSS, JS, header, footer, `inc/seo.php`, `functions.php` | Theme | Deploy | **B** |
| A theme image that pages reference | `theme/<slug>/assets/images/` | Deploy. Replace it **in place under the same filename**; never rename or delete it (trap 9) | **B** |
| ACF field groups | `acf-json/`, which the deploy excludes | Copied to the server by hand | **C** |
| Menus, settings, options, users, plugins, amenity/floor-plan CPT entries | Database | wp-admin or WP-CLI | **C** |

**Pattern content vs dynamic block.** In `patterns/parts/*.php`, core blocks
(`<!-- wp:group`, `wp:paragraph`, `wp:image`) are pattern content that was copied into the
database, so they are path A. A self-closing `<!-- wp:<prefix>/<name> … /-->` is a dynamic
block rendered by `blocks/<name>/render.php`, so it is path B. When unsure, fetch the live
content and search it for the text. If the text is there, it is path A. If it is not, it comes
from PHP and it is path B.

**Amenities and floor plans** come from the fallback lists in `_shared.php` only while that
site's CPT is empty. Check with
`scripts/prod.sh wp post list --post_type=<cpt> --url=<domain>`. If entries exist, the data is in
the database (path C). Creating the first entry switches the whole section over (trap 8).

**Defaults in `block.json`** apply only to blocks that never saved that attribute. If an
existing block stored a value, changing the default in code will not change that page.

Many requests need more than one path. A copy change on a live page is path A on the server,
plus the same edit to the pattern file in the repo (so a new page or the next fork inherits it).
Say which paths you are taking before starting.

## Path A — page content (database)

The client edits these pages too. Always work from the **live** content, fetched fresh. Never
work from the pattern file, and never re-insert a pattern over an existing page: that replaces
everything the client wrote.

1. **Find every post that holds the text.** It may be on more than one page:
   `scripts/prod.sh wp db search '<distinctive text>' --url=<domain> --stats`, and to map IDs to
   titles, `scripts/prod.sh wp post list --post_type=page --fields=ID,post_title,post_name --url=<domain>`.
   Also grep the theme (`inc/seo.php` JSON-LD and the render files) for the same fact.
2. **Fetch it into the scratchpad:**
   `scripts/prod.sh wp post get <id> --field=post_content --url=<domain> > <scratch>/post-<id>.html`
3. **Write the edit as a spec**, then dry-run it:
   - Small edits use a JSON file of exact find/replace pairs copied from the fetched content:
     `{"replacements":[{"find":"…","replace":"…"}]}`. Match entities (`&amp;`), curly quotes and
     any `<br>` exactly. Each `find` must occur exactly once, unless you add `"count": n`.
   - Larger edits (a section's cards, a block of text) use an edited copy of the fetched HTML,
     passed as an `.html` spec. Add `allow-structure-change` only when adding or removing
     top-level sections is the point of the edit.
   - Run the dry run: `scripts/prod.sh edit <domain> <id> <spec>`. Show the user what it reports.
4. **Apply after the user agrees:** `scripts/prod.sh edit <domain> <id> <spec> apply`.
   - The script backs up to `~/premigration/edits/`, saves with `wp_slash()` (trap 10), re-reads
     the post and refuses anything that changes the block structure, unbalances the block
     comments or strips an escape.
   - Those checks exist because a hand edit on 2026-10-15 dropped a closing tag and blanked half
     of the Bellclair homepage, and a script on 2026-10-14 turned "&" into "u0026" on a live hero.
5. **Purge and verify.** Run `scripts/prod.sh purge <domain>`, then
   `curl -s https://<domain>/<page>/ | grep -c '<new text>'`, then
   `python scripts/site-qa.py https://<domain>/<page>/`.
6. **Mirror the edit** in `patterns/parts/…`, and in the preview if the client still reviews
   there. Commit it as a normal path B change.
7. **Record it** in STATUS → "Changes made directly in the database": the post, the change and
   the backup path. No deploy reproduces it, so if the site is ever rebuilt from patterns,
   that table is the only record.

**Watch for these:**
- **Escapes in spec files.** The Write and Edit tools decode literal backslash-u escapes. If a
  find or replace string must contain a block-comment escape such as backslash-u0026, build
  the file with Python using `chr(92)`, then grep it.
- **Editor validity.** The block editor flags a core block as invalid if its saved HTML stops
  matching what the block would generate. HTML comments inside block markup cause this, and so
  does changing a class in the comment JSON but not in the markup. Keep edits to text inside
  existing elements, or change the attribute and its markup together. Ask the user to open the
  page in wp-admin and confirm there is no "unexpected or invalid content" warning.
- **Site-wide rewrites.** For something like a URL that appears on many pages, use
  `wp search-replace` instead.
  1. Back up first: `scripts/prod.sh backup <domain> <label>`.
  2. Dry-run with `--url=<domain> --skip-columns=guid --precise --report-changed-only --dry-run`.
  3. Dry-run the JSON-escaped form too (`https:\/\/…`).
  4. Never use `--network`.

## Path B — theme code (deploy)

1. **Branch from `main`:** `git switch -c fix/<property>-<thing> origin/main`. The practice here
   is short-lived branches PR'd straight to `main` and deleted on merge. A PR merged into a
   kept branch never deploys (trap 4).
2. **Make the change** following CLAUDE.md's conventions:
   - `global.css` is the only stylesheet. Use `clamp()` type, spacing tokens and the 900px
     breakpoint.
   - Core-block adjustments go in the bridges section.
   - Render files use the `_shared.php` helpers and the prefix's `*_anim()`.
   - New blocks are registered in `inc/blocks.php` and given a panel in `blocks-editor.js`.
   - Check the property's scheme. Rivergate is blue-dominant, so a new light panel goes in both
     white-surface lists (trap 6). Bellclair is inverted, so a new dark band goes in both lists
     in section 4.
3. **Port fixes deliberately.** The themes share Rivergate-derived code. If the fix applies to
   another property, say so and offer to port it; the mobile-menu fix has already had to be done
   twice.
4. **Test locally if Local is running.** Each theme is a junction into this repo, so
   `http://sterling-network.local/<slug>/` shows the change on refresh. Run
   `python scripts/site-qa.py <local urls>`. If Local is not running, ask the user to start it
   rather than skipping the test (if Local's router won't start, see OPERATIONS for the XAMPP
   port-80 note). For CSS the preview shares, `site-qa.py <slug>/preview` is a reasonable
   stand-in.
5. **Bump the version:** `scripts/bump-version.sh <property>` (patch by default, or `minor` for
   a feature). This bumps the constant and the `style.css` header together.
6. **Commit and open the PR.** Commit with the version in the subject, as the history does
   (e.g. "Bellclair 1.0.6: …"), push, and open a PR to `main` with `gh pr create`. **Merging
   deploys to production**, so leave the merge to the user, or merge only when they say so.
7. **Watch the deploy after the merge:**
   `gh run watch $(gh run list --workflow=deploy-<name>.yml -L1 --json databaseId -q '.[0].databaseId')`.
   The run fails if any smoke-test page stops returning 200. Delete the branch afterwards.
8. **Verify what visitors get.** Run `scripts/prod.sh purge <domain>`, then
   `scripts/cache-check.sh <urls>`, which must report the new version. Then run
   `python scripts/site-qa.py <urls>`.
9. **Update STATUS** with the property's version line and what changed.

## Path C — settings, CPT entries, ACF

- wp-admin is often the right tool, and the user or John may prefer to make these changes
  themselves. If you use WP-CLI, run `scripts/prod.sh wp option get|update … --url=<domain>`.
- **ACF field groups:** follow OPERATIONS → "Copying a field group to the server safely". Write
  the file in place, back it up, and verify with `acf_get_fields`. Do not click Import.
- Network-activated plugins (ACF, FluentSMTP) affect every site. Core and plugin updates are a
  separate job: take a Cloudways backup first, and handle them only when asked.
- Record anything you change in the STATUS table.

## Rules that protect the live sites

- **Writing to production is outward-facing.** Show the dry run, get the user's go-ahead, and
  back up first into `~/premigration/<topic>/` on the server (outside the web root). Reads are
  free.
- **Never edit theme files on the server.** The next deploy's `rsync --delete` overwrites them.
- **Never copy a local database up.** Local's blog IDs and paths differ from production.
- **Forms.** Check STATUS → Mail before telling anyone a form works. No form delivered anything
  until SMTP was configured.

## When something looks wrong

- **"The change didn't deploy."** Run `scripts/cache-check.sh`. A HIT with an old version means
  purge. If the theme version is current but the text is old, the text is page content, so it is
  path A, not a deploy problem.
- **A swapped theme image isn't showing.** The client probably replaced it in the editor, so
  that block's `src` is now an uploads URL and the theme file is ignored. Check the live
  content.
- **The map is blank on a new domain.** The Maps key needs the domain added to its allowed
  referrers (trap 7).
- **The deploy went green, but the smoke test fails.** Check the `<PREFIX>_SITE_URL` secret, and
  that every page in the workflow's list exists.
- **Merged, but nothing deployed.** The PR's base was not `main` (trap 4), or the change was
  outside `theme/<slug>/`, or it was only in `acf-json/`.

## Helpers in `scripts/`

| Script | Does |
|---|---|
| `prod.sh wp …` / `prod.sh sh '…'` | WP-CLI or a shell command on the server, run from the WordPress root |
| `prod.sh purge <domain>` | Purge Varnish for one hostname. Purge `www.` too if the site serves it |
| `prod.sh backup <domain> <label>` | Export that site's tables to `~/premigration/<label>/` |
| `prod.sh edit <domain> <id> <spec> [apply]` | Safe page-content edit (`safe-edit.php`) |
| `bump-version.sh <property> [patch\|minor\|X.Y.Z]` | Bump the version constant and `style.css` header together |
| `cache-check.sh <urls>` | Status, `x-cache` and age, plus the served theme version vs `main` |
| `site-qa.py <urls or preview dir>` | Playwright and Edge at 390/900/1440: console errors, 4xx, sideways scroll, page heights |

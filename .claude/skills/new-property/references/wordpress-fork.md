# Stage 4 — WordPress fork (local)

Turn the approved preview into a block theme running in the Local multisite. The fork should
reproduce the preview so closely that page heights match. Bellclair's landed within 0.5% at
desktop and phone widths.

## 1. Fork from Bellclair

**`bellclair-montclair` is the default base** (decided 2026-10-16). It was forked from Rivergate
1.17.2 and carries fixes Rivergate does not have:

- `global.css` section 2 cancels WordPress's default 24px block gap
  (`:root :where(.is-layout-flow) > *`). Without it, pages run up to ~100px taller per section
  than the preview.
- `patterns/parts/communities.php` has no HTML comment inside the block markup, so the editor
  does not flag it as invalid. Rivergate's copy still has the comment.
- The neighborhood map has an OpenStreetMap fallback and walking directions.
- It adds the film-frame block, the film modal, and a phone-sized hero video.

Fork Bellclair without asking. Drop what the new property doesn't use (film block and modal,
phone hero video, the Duplex placeholder), and list the drops in the fork commit. Two cases
deserve a word with the user first:
- The new property needs Rivergate's multi-page layout. Rivergate has the standalone Amenities,
  Floor Plans, Contact and Location patterns that Bellclair dropped; copy those patterns across
  rather than forking Rivergate.
- A fix has landed in Rivergate since 1.17.2 that Bellclair lacks. Check the commit history
  (`git log rivergate-bordentown/theme`) and port it.

## 2. Copy without overwriting the preview's assets

The theme folder already holds the preview's `assets/css/global.css` and images, and those are
the approved design. Copy the base theme's PHP, JS, `blocks/`, `inc/`, `patterns/`,
`templates/`, `theme.json`, `acf-json/`, `header.php`, `footer.php`, `index.php`,
`functions.php` and `style.css` in **around** them. Do not copy over them. Bring across only the
`global.css` sections the blocks need, such as the bridges and the block-gap fix, by merging
them in.

## 3. Re-prefix with a script

Use exact-match replacements in Python, longest token first, so nothing half-renames. From a
Bellclair base, the order is:
1. `bellclair-montclair` (slug, text domain, pattern slugs) before `BELLCLAIR_`, `Bellclair_`
   and `bellclair`
2. `bc_amenity` / `bc_floor_plan` / `group_bc_` before a bare `bc_`
3. Display strings such as "Bellclair at Montclair" separately, by hand, since they are copy

Watch two traps. `bellclaire@spgnj.com` (spelled with an e) contains "bellclair", so replace the
email address before the bare name. And Bellclair's sister-community cards list other Sterling
properties, which should not be renamed. Python `str.replace` on file text is
safe for patterns that contain block-comment escapes. The Write and Edit tools are not, because
they decode backslash-u sequences.

What has to change:
- function prefix, constants, the nav-walker class name, text domain
- block namespace, in all four places at once: `blocks/*/block.json`, `inc/blocks.php`,
  `assets/js/blocks-editor.js` and `patterns/`
- `is-style-<old>-*` button styles, together with their `global.css` bridge selectors
- CPT slugs, plus the ACF JSON files' names, `key`s and location rules
  (`group_rg_amenity.json` → `group_xx_amenity.json`)
- pattern slugs (`<old-slug>/…`) and the pattern category
- `style.css` header: Theme Name, Text Domain, Description, and Version reset to 1.0.0
- the `_VERSION` constant (1.0.0), the Google Fonts URL constant, the Maps key constant's name

Verify: `grep -rniE '<old-name>|<old-cpt-prefix>_|<old-slug>' <slug>/theme/<slug>/`. Every hit
must be deliberate. `php -l` every PHP file using Local's PHP
(`$APPDATA/Local/lightning-services/php-*/bin/win64/php.exe`).

## 4. Replace the identity

- **`inc/seo.php`:** page titles and descriptions; the JSON-LD (address, geo, phone,
  `openingHoursSpecification`, amenities); the share image.
- **Remove the base property's `google-site-verification` token.** A fork must never ship
  another property's token. The new property gets its own at go-live.
- Header and footer logos, address and phone; `assets/images/site-icon/` PNGs.
- **Default recipients:** the inquiry pop-up (`<prefix>_inquiry_recipient`, in
  `inc/inquiry-popup.php`) and the contact form's fallback. A blank recipient falls back to
  `admin_email`, which is a developer address.

## 5. Build the patterns from the preview

Follow CLAUDE.md → "Block Editor Architecture".
- **One section at a time.** For each preview section, choose a pattern (core blocks plus
  `global.css` classes) or a dynamic block (interactive, data-driven or structurally custom).
- **Page patterns.** Write a composite page pattern for each page, then lock the sections.
- **Data goes in the fallbacks.** The amenities and floor plans go in the `_shared.php` fallback
  lists, and the neighborhood places and times in the explorer's `render.php`. CPT entries stay
  empty (trap 8).
- **Images.** Reference theme images through `get_theme_file_uri()` in the pattern PHP. This
  bakes absolute URLs into pages at insert time (trap 9), which is why the domain cutover
  rewrites them later.
- **No HTML comments inside block markup.**
- Drop blocks and patterns the property doesn't use, as Bellclair dropped `amenity-cards`,
  `plan-cards` and four page patterns.

## 6. Run it in Local

1. Ask the user to start Local. In Network Admin → Sites, add `/<slug>/`.
2. Junction the theme into Local (PowerShell):
   `New-Item -ItemType Junction -Path "C:\Users\heatw\Local Sites\sterling-network\app\public\wp-content\themes\<slug>" -Target "D:\Bruddas Industries\Sterling-Properties\<slug>\theme\<slug>"`
3. Enable the theme **for that site only** (Network Admin → Sites → Edit → Themes) and activate
   it. Go to Settings → Permalinks and click Save.
4. Insert each page from its composite pattern with `scripts/insert-page.php`, using Local's
   WP-CLI (the recipe is in OPERATIONS → "Local development"):
   `… wp-cli.phar eval-file scripts/insert-page.php <slug>/homepage "Home" slug=home front apply --url=http://sterling-network.local/<slug>/`
   Do the same for each page. The script sets the Block Canvas template, slashes the content and
   verifies it.

## 7. QA against the preview

- Run `python scripts/site-qa.py <slug>/preview http://sterling-network.local/<slug>/ …` at the
  same widths, and compare page heights. Expect them within about 1%. A larger gap usually means
  block-gap spacing or a pattern that is missing a class.
- Every page must open in the block editor with zero invalid blocks. Ask the user to check, or
  log in with Playwright.
- Forms deliver to Mailpit (`http://localhost:10000`). The map shows the OSM fallback, because
  the key refuses `.local`.
- Open a PR (`feature/<slug>-wordpress`). Nothing deploys yet: there is no workflow for this
  theme until stage 5.
- Update STATUS: the local blog ID, the pages, the theme version, and what differs from the base.

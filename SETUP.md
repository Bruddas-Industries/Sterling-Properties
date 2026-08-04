# Local Development Setup

This repo runs all property sites as one **WordPress Multisite network** (`sterling-network`)
in **Local by Flywheel**. The active base theme is **`rivergate-bordentown`**; every property
is a fork of it. Pages are built in the **native block editor** (dynamic blocks + patterns) —
see the [Block Editor Architecture](CLAUDE.md#block-editor-architecture--how-every-page-is-built)
section of CLAUDE.md for how pages are constructed.

## Prerequisites

- [Local by Flywheel](https://localwp.com/) — set up in **multisite** mode
- WordPress **6.3+** (required for the block.json `apiVersion:3` / `render: file:` blocks)
- [Advanced Custom Fields](https://wordpress.org/plugins/advanced-custom-fields/) (free) — powers the `rg_amenity` / `rg_floor_plan` CPT fields (not page editing)
- [Git](https://git-scm.com/) and a code editor (VS Code recommended)

## First-Time Setup

### 1. Clone the repo

```bash
git clone https://github.com/Bruddas-Industries/Sterling-Properties.git
cd Sterling-Properties
```

### 2. Create the Local multisite

1. Open **Local** → **+** to create a site (e.g. `sterling-network`), Preferred environment (PHP 8.x).
2. Enable **Multisite** (Local has a multisite toggle when creating the site, or convert an existing site).
3. In the network admin, add a site for the property (e.g. `rivergate-bordentown`).

### 3. Link the theme

Copy or symlink the theme into the site's `wp-content/themes/` directory.

**Symlink (recommended — changes reflect instantly):**
```bash
ln -s /path/to/repo/rivergate-bordentown/theme/rivergate-bordentown \
  ~/Local\ Sites/sterling-network/app/public/wp-content/themes/rivergate-bordentown
```

Adjust paths to your Local sites directory.

### 4. Activate the theme + ACF

1. **Network Admin → Themes** → network-enable **Rivergate Bordentown**, then activate it on the property site.
2. Install + network-activate **Advanced Custom Fields** (the CPT field groups load from `acf-json/` automatically).

### 5. Wire up the homepage (block editor)

1. **Settings → Permalinks → Save** (flush rewrites so the CPTs/pages route).
2. **Pages → Add New → "Home"**:
   - In the inserter, add the **Rivergate Homepage** pattern (category "Rivergate"), or build from the individual Rivergate blocks/patterns.
   - In the page sidebar set **Template → Block Canvas (Full Width)**.
   - Click the hero block → pick the background video (MP4) + poster image from the Media Library.
   - **Publish.**
3. **Settings → Reading → Front page displays → A static page → Home.**
4. *(Optional)* Add entries under **Amenities** and **Floor Plans** so those sections show real content (built-in examples render until you do).

### 6. Editing pages

Open any page in the block editor:
- **Static sections** (patterns) — click and type directly on the page.
- **Interactive sections** (dynamic blocks — hero, amenities, floor plans, location, contact form) — edit fields in the right sidebar; the canvas shows a live preview.
- Sections are **move/remove-locked** so content edits can't break the layout (use the block toolbar's lock control to override if needed).

---

## Building Out the Rest of the Site

Every page already has a ready-made composite pattern. Create each page the same way as
the homepage — **Pages → Add New**, set **Template = Block Canvas (Full Width)**, insert
the matching pattern (category "Rivergate"), then **Publish**. Give each page the slug the
nav links expect (the slug in the table below).

| Page | Slug | Insert this pattern |
|------|------|---------------------|
| Home | (front page) | Rivergate Homepage |
| Amenities | `amenities` | Rivergate Page — Amenities |
| Floor Plans | `floor-plans` | Rivergate Page — Floor Plans |
| Gallery | `gallery` | Rivergate Page — Gallery |
| Location | `location` | Rivergate Page — Location |
| Contact | `contact` | Rivergate Page — Contact |
| Availability | `availability` | Rivergate Page — Availability |
| Residents | `residents` | Rivergate Page — Residents Portal |

Notes:
- The inner pages share the **Page Hero** block; interactive/data sections are dynamic
  blocks (`amenity-cards`, `plan-cards`, `neighborhood-explorer`, `availability-embed`,
  `contact-form`). Amenities/floor-plan content comes from the CPTs.
- The **Gallery** uses the core **Gallery** block — replace the placeholder images with
  your own from the Media Library (the old category filter was dropped in favor of native
  client image control).
- For the **Location** map, set `RIVERGATE_MAPS_API_KEY` in `functions.php`.

## Adding a New Page Type or New Property

- **New page type**: follow the *Recipe to build or convert a page* in
  [CLAUDE.md → Block Editor Architecture](CLAUDE.md#block-editor-architecture--how-every-page-is-built).
- **New property**: see *Adding a New Property Site* in CLAUDE.md. The `blocks/`, `patterns/`,
  `inc/blocks.php`, `theme.json`, and `templates/` are inherited unchanged — only `global.css`
  tokens, `style.css`, and the theme constants/prefixes change.

---

## Working on Static Previews

The `preview-1/` folders are plain HTML/CSS — no build step.

```bash
open rivergate-bordentown/preview-1/index.html   # or VS Code Live Server
```

Previews reference the theme CSS via a relative path — keep it intact when moving files:
```
../theme/rivergate-bordentown/assets/css/global.css
```

---

## Deploying to Production

See [CONTRIBUTING.md](CONTRIBUTING.md) for the full workflow. Short version:
1. Merge to `main` (PR from `dev`).
2. Push Local → Cloudways with **All-in-One WP Migration** or the Cloudways Migrator.
3. URL search-replace: `wp search-replace 'sterling-network.local' 'yourdomain.com'`.
4. After deploy: **Settings → Permalinks → Save**, and re-select any media-library images baked into patterns (default pattern images point at the dev theme URL).

# CLAUDE.md — Sterling Properties

## What This Project Is

This repo is the master development directory for **10 luxury property marketing websites** built by [Brudda's Industries](https://bruddaindustries.com) for Sterling Properties. All 10 sites share a single WordPress base theme (`sterling-properties`) and run as a **WordPress Multisite** network. Each property gets its own subdirectory in this repo with its own theme (forked from the base) and its own static HTML previews.

The goal is for Claude to handle the majority of site generation — layout, copy structure, CSS, PHP templates — while the client retains easy control over their own content through the WordPress admin.

---

## Repo Structure

```
Sterling Properties/
├── sterling-properties/              ← Base brand / parent theme
│   ├── theme/sterling-properties/   ← WordPress theme (PHP + CSS)
│   └── elementor-kit/               ← Elementor JSON template kit
│
├── rivergate-bordentown/             ← Property #1
│   ├── theme/rivergate-bordentown/  ← Forked theme with property-specific tokens
│   └── preview-1/                   ← Current multi-page static HTML preview
│
└── [property-name]/                  ← Future properties follow same pattern
    ├── theme/[property-name]/
    └── preview-1/
```

---

## Architecture

### WordPress Multisite
- One WordPress install running all 10 property sites as a network
- Each site gets its own theme (forked from `sterling-properties`)
- Developed locally via **Local by Flywheel** in multisite mode
- Production host: TBD (Cloudways recommended)

### Theme Architecture
- **Base theme** (`sterling-properties`): Contains all shared PHP structure, nav walker, Elementor support, head cleanup, scroll detection
- **Property themes**: Fork of the base — only design tokens (`global.css`) and theme identity constants (`functions.php`) change per property
- **Design system**: All visual tokens live in `assets/css/global.css` as CSS custom properties — changing ~8 values re-skins the entire site
- **Page builder**: Elementor handles all visual layouts — PHP templates are intentionally minimal shells

### Client Content Control
The client manages their own content through the WordPress admin. Areas designed for client control:
- **Gallery images** — managed via WordPress media library + Elementor gallery widgets or ACF repeater fields
- **Homepage hero** — featured image or ACF image field
- **Contact / leasing info** — ACF text fields editable in the admin
- **Navigation menus** — via Appearance → Menus
- Everything else (layout, styling, structure) is locked in theme/Elementor templates

---

## Design Tokens (how to re-skin for a new property)

All tokens are in `[property]/theme/[property]/assets/css/global.css`:

```css
/* Change these 8 values to fully re-skin a site */
--color-primary        /* Accent color (buttons, highlights) */
--color-primary-dark   /* Hover state */
--color-bg             /* Page background */
--color-bg-alt         /* Alternate section background */
--color-surface        /* Card/panel backgrounds */
--color-text           /* Body text */
--color-text-muted     /* Secondary/caption text */
--color-border         /* Dividers */

--font-heading         /* Heading typeface */
--font-body            /* Body typeface */
```

Also update the Google Fonts URL constant in `functions.php` when changing typefaces.

---

## Adding a New Property Site

1. **Copy the base theme:**
   ```bash
   cp -r sterling-properties/theme/sterling-properties [property-name]/theme/[property-name]
   ```

2. **Update `style.css`** — `Theme Name`, `Text Domain`, `Description`

3. **Update `functions.php`** — rename all `STERLING_` constants and `sterling_` function prefixes to the property prefix; update text domain

4. **Update design tokens** in `assets/css/global.css` — colors, fonts

5. **Create a static preview** in `[property-name]/preview-1/` — HTML pages that reference the theme CSS at `../theme/[property-name]/assets/css/global.css`

6. **Deploy preview to Vercel** by connecting the GitHub repo (Vercel serves the static `preview-1/` folders for client review)

---

## Development Conventions

### PHP / WordPress
- All themes are Elementor-first — PHP templates are minimal semantic shells (`header.php`, `footer.php`, `index.php`)
- No Gutenberg / block editor support — `should_load_separate_core_block_assets` is disabled
- Nav walker (`[Theme]_Nav_Walker`) strips `<ul>/<li>` wrappers — nav items are bare `<a>` tags in a flex container
- Elementor Theme Builder handles the visual header and footer in production; PHP fallbacks remain for graceful degradation
- Bump the `_VERSION` constant in `functions.php` on every deploy to bust CSS/JS caches

### CSS
- 8-point spacing grid via `--space-1` through `--space-10` custom properties
- Fluid typography via `clamp()` — never hardcode `px` font sizes
- All component styles live in `global.css` — no inline styles in PHP except placeholder logo text

### Static Previews
- Multi-page HTML files in `preview-1/` — no build step, no dependencies
- CSS referenced via relative path to the theme: `../theme/[property]/assets/css/global.css`
- Include a `.preview-banner` strip so clients know they're viewing a preview, not the live site

### Git
- `main` — production-ready only
- `dev` — integration branch for active work
- `feature/[name]` — individual features or new property builds
- Never commit directly to `main` — open a PR from `dev`

---

## Claude's Role

Claude handles:
- Generating new property themes (PHP, CSS tokens, page templates)
- Building out static HTML previews for client review
- Writing Elementor-compatible page structure
- Copy and content structure for property pages (homepage, amenities, gallery, floor plans, contact, location, residents portal)
- Suggesting and implementing ACF field structures for client-editable content areas

Claude does not need to:
- Handle WordPress core files, plugins, or database configuration
- Write wp-config.php or server config
- Manage media uploads or assets (client-controlled)

---

## Key Files Reference

| File | Purpose |
|------|---------|
| `[property]/theme/[property]/assets/css/global.css` | All design tokens and component styles |
| `[property]/theme/[property]/functions.php` | Theme setup, asset enqueue, Elementor support |
| `[property]/theme/[property]/header.php` | Nav shell + mobile toggle |
| `[property]/theme/[property]/footer.php` | Footer shell + copyright |
| `[property]/theme/[property]/style.css` | Theme registration only — no styles |
| `[property]/theme/[property]/templates/full-width.php` | Elementor full-width page template |
| `sterling-properties/elementor-kit/*.json` | Elementor template kit — import into each site |
| `[property]/preview-1/` | Static multi-page client preview |

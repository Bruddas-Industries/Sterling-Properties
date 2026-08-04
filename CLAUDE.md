# CLAUDE.md — Sterling Properties

## What This Project Is

This repo is the master development directory for **10 luxury property marketing websites** built by [Brudda's Industries](https://bruddaindustries.com) for Sterling Properties. All 10 sites run as a **WordPress Multisite** network. Each property gets its own subdirectory in this repo with its own theme (forked from `rivergate-bordentown`) and its own static HTML previews.

The goal is for Claude to handle the majority of site generation — layout, copy structure, CSS, PHP templates — while the client retains easy control over their own content through the WordPress admin.

---

## Repo Structure

```
Sterling Properties/
├── rivergate-bordentown/             ← Property #1 and template baseline
│   ├── theme/rivergate-bordentown/  ← Fork this for every new property
│   └── preview-1/                   ← Current multi-page static HTML preview
│
├── sterling-properties/              ← Legacy base theme (no longer active)
│
└── [property-name]/                  ← Future properties follow same pattern
    ├── theme/[property-name]/
    └── preview-1/
```

---

## Architecture

### WordPress Multisite
- One WordPress install running all 10 property sites as a network (`sterling-network.local` locally, Cloudways in production)
- Each site gets its own theme (forked from `rivergate-bordentown`)
- Developed locally via **Local by Flywheel** in multisite mode
- Production host: Cloudways (domain mapping per property — each site gets its own domain)

### Theme Architecture
- **Template theme** (`rivergate-bordentown`): The active baseline — contains all shared PHP structure, nav walker, block editor support, head cleanup, scroll detection
- **Property themes**: Fork of `rivergate-bordentown` — only design tokens (`global.css`) and theme identity constants (`functions.php`) change per property
- **Design system**: All visual tokens live in `assets/css/global.css` as CSS custom properties — changing ~8 values re-skins the entire site
- **Page builder**: Native WordPress block editor (Gutenberg) — **no Elementor, no ACF meta-box editing**. Pages are built from custom **dynamic blocks** (interactive/data-driven sections) + **block patterns** (static text sections). See [Block Editor Architecture](#block-editor-architecture--how-every-page-is-built) — this is the standard for every page.

### Client Content Control
The client manages their own content visually in the block editor (live previews), plus a few admin areas. Everything is designed so the client edits **content** but cannot break **layout** (sections are move/remove-locked):
- **Page content** — edited directly in the block editor: static text/images via patterns (click-to-type on the page), interactive sections via dynamic blocks (edited in the sidebar with a live preview)
- **Amenities & floor plans** — managed as the `rg_amenity` / `rg_floor_plan` custom post types (the homepage/inner blocks read from these; built-in fallbacks show until entries exist)
- **Gallery images** — WordPress media library + Gallery block
- **Navigation menus** — via Appearance → Menus
- Layout, styling, and section structure are locked in theme blocks/patterns

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

## Block Editor Architecture — How Every Page Is Built

Pages are built and edited in the **native WordPress block editor**, using two
mechanisms chosen per section. This is how the homepage is built and **the standard for
every other page and every property fork going forward**. The `global.css` design system
is preserved as-is and reused by mapping its classes onto blocks.

### Decision rule: pattern vs dynamic block

**Block PATTERN (core blocks)** — when the section is text / images / buttons that core
blocks can reproduce. The client clicks and types directly on the page. Patterns assign
existing `global.css` classes to core blocks via the block `className` (e.g. a Group
with `className:"section--alt"`, a Paragraph with `className:"eyebrow"`, a Separator with
`className:"divider"`). Buttons use the registered block styles
(`is-style-rivergate-primary/secondary/ghost`).

**Dynamic BLOCK (PHP-rendered)** — when the section is:
- **interactive** (needs `main.js`: accordions, selectors, tap-to-reveal toggles),
- **data-driven** (reads a CPT or option), or
- **structurally custom** (forms, bespoke card/grid markup core blocks can't emit).

Dynamic blocks render the real PHP markup (so the design + `main.js` hooks are identical
front-end and in-editor) and show a live **`ServerSideRender`** preview in the editor;
their content is edited via Inspector (sidebar) controls. **No build step** — the editor
UI is plain JS using the `wp.*` globals.

### File layout (inherited by every fork)

```
theme/[property]/
├── theme.json                      ← editor presets (palette, fonts, spacing) + layout widths
├── inc/blocks.php                  ← registers blocks, button/eyebrow block styles,
│                                      pattern category, editor script, has-video-hero body class
├── blocks/
│   ├── _shared.php                 ← shared render helpers (CPT queries, icon map,
│   │                                  rivergate_anim(), editor-context guard)
│   └── [block]/{block.json,render.php}   ← one folder per dynamic block (render = file:./render.php)
├── assets/js/blocks-editor.js      ← no-build editor UI (ServerSideRender + InspectorControls)
├── patterns/
│   ├── parts/[section].php          ← block markup, shared (NOT auto-registered — subfolder)
│   └── [page-or-section].php        ← top-level = auto-registered pattern (has Title/Slug header)
└── templates/page-canvas.php       ← full-width template: get_header → the_content → get_footer
```

WP auto-registers only **top-level** `patterns/*.php` (those need a `Title:`/`Slug:`/
`Categories:` header comment). `patterns/parts/*.php` are plain includes shared between a
composite page pattern and the individual section patterns (keeps markup DRY).

### Recipe to build or convert a page

1. **Split the page into sections** and label each pattern vs dynamic block (rule above).
2. **Dynamic blocks**: create `blocks/[name]/block.json` (`apiVersion:3`, attributes,
   `"render":"file:./render.php"`) + `render.php` (reuse helpers in `blocks/_shared.php`;
   emit the exact classes `main.js` targets; call `rivergate_anim()` so `data-animate` is
   dropped in the editor, else the preview is blank). Add the folder name to the
   `$blocks` array in `inc/blocks.php`, and an `edit` panel in `assets/js/blocks-editor.js`.
3. **Patterns**: write the section markup in `patterns/parts/[section].php` using core
   blocks + `global.css` classes; lock the outer section block
   (`"lock":{"move":true,"remove":true}`) for safe handoff. Add a top-level wrapper
   `patterns/[section].php` (header + `include __DIR__.'/parts/[section].php';`) and add
   the section to the page's composite pattern.
4. **CSS bridges**: if core-block wrappers need adapting to the design, add scoped rules
   to the **BLOCK PATTERN BRIDGES** section at the end of `global.css` (specificity-bump
   like `.wp-block-group.container {…}` to beat the generic group rule). Never restyle in
   `theme.json` — `global.css` stays the single source of visual truth.
5. **Wire the page in WP admin**: create the Page, insert its composite pattern, set
   **Template = Block Canvas (Full Width)**, publish. Bump `[PREFIX]_VERSION` in
   `functions.php`.

### Conventions
- **No build step** — block.json + `render.php` + one shared plain-JS editor file. Editor
  script deps are declared explicitly in `inc/blocks.php`
  (`wp-blocks, wp-element, wp-block-editor, wp-components, wp-server-side-render, wp-i18n`).
- **Editor-context guard**: in `render.php` use `rivergate_in_editor()` /
  `rivergate_anim()` to drop `data-animate` (and skip form POST / inline-script
  injection) during the `ServerSideRender` REST request.
- **Locking for handoff**: section blocks (and the interactive blocks inside them) carry
  `lock:{move,remove}` so the client edits content but can't reorder/delete sections.
- **Requires WordPress 6.3+** (block.json `apiVersion:3` + `render: file:` support).
  ACF (free) is still required for the CPT meta fields — not for page editing.

---

## Adding a New Property Site

1. **Copy the base theme:**
   ```bash
   cp -r rivergate-bordentown/theme/rivergate-bordentown [property-name]/theme/[property-name]
   ```

2. **Update `style.css`** — `Theme Name`, `Text Domain`, `Description`

3. **Re-prefix the theme** — rename `RIVERGATE_` constants and `rivergate_` function prefixes to the property prefix across `functions.php`, `inc/blocks.php`, and `blocks/_shared.php`; update the text domain. The block namespace (`rivergate/*` in `blocks/*/block.json`, `inc/blocks.php`, `assets/js/blocks-editor.js`, and `patterns/`) and the `is-style-rivergate-*` button styles can stay or be re-prefixed — if you rename them, update all four places **and** the `global.css` bridge selectors together.

4. **Update design tokens** in `assets/css/global.css` — colors, fonts (the blocks + patterns are inherited unchanged; only tokens differ per property)

5. **Create a static preview** in `[property-name]/preview-1/` — HTML pages that reference the theme CSS at `../theme/[property-name]/assets/css/global.css`

6. **Deploy preview to Vercel** by connecting the GitHub repo (Vercel serves the static `preview-1/` folders for client review)

---

## Development Conventions

### PHP / WordPress
- PHP templates are minimal semantic shells (`header.php`, `footer.php`, `index.php`, `templates/page-canvas.php`) — page content is built in the block editor from dynamic blocks + patterns (see [Block Editor Architecture](#block-editor-architecture--how-every-page-is-built))
- Block editor support: wide/full alignment, editor styles (design tokens load inside the editor via `add_editor_style` + `theme.json`), responsive embeds
- Dynamic block render files reuse helpers in `blocks/_shared.php`; register new blocks in the `$blocks` array in `inc/blocks.php`
- Nav walker (`[Theme]_Nav_Walker`) strips `<ul>/<li>` wrappers — nav items are bare `<a>` tags in a flex container
- Bump the `_VERSION` constant in `functions.php` on every deploy to bust CSS/JS caches (also re-versions the editor script)

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
- Copy and content structure for property pages (homepage, amenities, gallery, floor plans, contact, location, residents portal)
- Building block patterns and page templates for the WordPress block editor

Claude does not need to:
- Handle WordPress core files, plugins, or database configuration
- Write wp-config.php or server config
- Manage media uploads or assets (client-controlled)

---

## Key Files Reference

| File | Purpose |
|------|---------|
| `[property]/theme/[property]/assets/css/global.css` | All design tokens + component styles; block-pattern bridges at the end |
| `[property]/theme/[property]/theme.json` | Block editor presets (palette, fonts, spacing) + layout widths |
| `[property]/theme/[property]/functions.php` | Theme setup, asset enqueue, page templates, version constant |
| `[property]/theme/[property]/inc/blocks.php` | Registers dynamic blocks, block styles, pattern category, editor script, body class |
| `[property]/theme/[property]/blocks/_shared.php` | Shared render helpers (CPT queries, icon map, editor guard) |
| `[property]/theme/[property]/blocks/[name]/{block.json,render.php}` | One dynamic block (interactive/data-driven section) |
| `[property]/theme/[property]/assets/js/blocks-editor.js` | No-build block editor UI (ServerSideRender + inspector) |
| `[property]/theme/[property]/patterns/` | Block patterns (`parts/` = shared includes; top-level = auto-registered) |
| `[property]/theme/[property]/header.php` / `footer.php` | Nav + footer shells (locked) |
| `[property]/theme/[property]/style.css` | Theme registration only — no styles |
| `[property]/theme/[property]/templates/page-canvas.php` | Full-width block-canvas template (homepage + block-driven pages) |
| `[property]/preview-1/` | Static multi-page client preview |

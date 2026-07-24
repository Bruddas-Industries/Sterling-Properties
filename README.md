# Sterling Properties

WordPress themes and static HTML previews for Sterling Properties and its sub-brands, built by [Brudda's Industries](https://bruddaindustries.com).

## Repo Structure

```
Sterling Properties/
├── sterling-properties/
│   ├── theme/sterling-properties/   ← Base WordPress theme
│   └── elementor-kit/               ← Elementor JSON templates
│
└── rivergate-bordentown/
    ├── theme/rivergate-bordentown/  ← Child-style theme (forked from base)
    ├── preview-1/                   ← Current multi-page static preview
    └── preview/                     ← Earlier preview iteration
```

## Sites

| Site | Theme | Status |
|------|-------|--------|
| Sterling Properties | `sterling-properties` | In development |
| Rivergate Bordentown | `rivergate-bordentown` | In development |

## Quick Links

- [Local dev setup](SETUP.md)
- [Adding a new property](CONTRIBUTING.md)
```

## Tech Stack

- **WordPress Multisite** — CMS (one network, `rivergate-bordentown` is the base theme)
- **Native block editor (Gutenberg)** — pages built from custom dynamic blocks + block patterns ([architecture](CLAUDE.md#block-editor-architecture--how-every-page-is-built)); no Elementor
- **Custom PHP themes** — minimal shells; design lives in blocks/patterns + CSS tokens
- **CSS custom properties** — All design tokens in `assets/css/global.css`
- **Static HTML previews** — Client-facing previews hosted on Vercel

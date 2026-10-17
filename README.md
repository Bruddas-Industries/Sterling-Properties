# Sterling Properties

WordPress themes and static HTML previews for Sterling Properties and its sub-brands, built by [Brudda's Industries](https://bruddaindustries.com).

## Repo Structure

```
Sterling Properties/
├── sterling-properties/
│   ├── theme/sterling-properties/   ← Base WordPress theme
│   └── elementor-kit/               ← Elementor JSON templates
│
├── rivergate-bordentown/
│   ├── theme/rivergate-bordentown/  ← Property #1 theme (live)
│   ├── preview-1/                   ← Multi-page static preview (Vercel)
│   └── preview/                     ← Earlier preview iteration
│
└── bellclair-montclair/
    ├── theme/bellclair-montclair/   ← Property #2 theme (live) — the fork base for new properties
    ├── preview/                     ← Static client preview (Vercel)
    └── vercel.json                  ← Vercel project root is this folder
```

## Sites

| Site | Theme | Status |
|------|-------|--------|
| Sterling Properties | `sterling-properties` | Legacy, inactive |
| Rivergate Bordentown | `rivergate-bordentown` | Live — rivergatenj.com |
| Bellclair at Montclair | `bellclair-montclair` | Live — bellclair.com |

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

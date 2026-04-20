# Contributing

## Branching

| Branch | Purpose |
|--------|---------|
| `main` | Production-ready code only |
| `dev` | Active development, integration branch |
| `feature/your-feature` | Individual features or fixes |

**Never commit directly to `main`.** Open a PR from `dev` or a feature branch.

```bash
git checkout -b feature/rivergate-gallery-update
```

## Adding a New Property Site

1. **Copy the base theme:**
   ```bash
   cp -r sterling-properties/theme/sterling-properties \
     new-property/theme/new-property
   ```

2. **Update theme identity** in `new-property/theme/new-property/style.css`:
   - `Theme Name`
   - `Text Domain`
   - `Description`

3. **Replace constants** in `functions.php`:
   - Rename `STERLING_` prefixes to your property prefix (e.g. `BAYVIEW_`)
   - Rename function names and class names to match
   - Update `register_nav_menus` text domain

4. **Update design tokens** in `assets/css/global.css`:
   - `--color-primary` — brand accent color
   - `--font-heading` / `--font-body` — typefaces
   - Update the Google Fonts URL in `functions.php`

5. **Create a preview folder:**
   ```
   new-property/
   ├── preview-1/       ← static HTML pages
   └── theme/new-property/
   ```

## Commit Style

```
Short summary in present tense (50 chars max)

Optional body explaining the why, not the what.
```

Examples:
- `Add gallery page to Rivergate preview`
- `Fix nav scroll behavior on mobile`
- `Update Rivergate brand colors to final palette`

## Deployment Checklist

Before pushing a theme to production:

- [ ] Bump `THEME_VERSION` constant in `functions.php`
- [ ] Test in Local on PHP 8.x
- [ ] Confirm Elementor templates load correctly
- [ ] Check mobile breakpoints
- [ ] Run `wp search-replace` after migrating to update URLs

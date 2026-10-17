# Contributing

## Branching

| Branch | Purpose |
|--------|---------|
| `main` | Production. Merging deploys the theme and rebuilds the Vercel previews |
| `feature/…`, `fix/…`, `docs/…` | Short-lived branches cut from `main` |

**Never commit directly to `main`.** Open a PR from your branch straight to `main`, and delete
the branch on merge. There is no `dev` integration branch.

```bash
git checkout -b feature/rivergate-gallery-update
```

## Adding a New Property Site

Follow [`.claude/skills/new-property/SKILL.md`](.claude/skills/new-property/SKILL.md). It is written
for Claude, but it reads fine as a checklist. Fork from `bellclair-montclair` (the default
base since 2026-10-16), never the legacy `sterling-properties` theme.
Day-to-day edits are covered by `edit-site-dev` (previews) and `edit-site-prod` (WordPress sites)
in the same folder.

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

- [ ] Bump the `_VERSION` constant in `functions.php` (busts CSS/JS + editor-script caches)
- [ ] Test in Local on PHP 8.x (WordPress 6.3+)
- [ ] Confirm the homepage pattern + dynamic blocks render correctly (front end + editor)
- [ ] Check mobile breakpoints
- [ ] Run `wp search-replace` after migrating to update URLs, then re-save Permalinks

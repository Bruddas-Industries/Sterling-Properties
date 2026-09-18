# Operations — Sterling Properties Multisite

How the live site is hosted, deployed and cached, plus the traps that have cost real time on
this project. Architecture and coding conventions live in [`CLAUDE.md`](../CLAUDE.md); current
state and open work live in [`STATUS.md`](./STATUS.md).

**No secrets are committed here.** SSH keys live on developer machines, deploy credentials in
GitHub repo secrets, and hosting logins in the Cloudways panel.

---

## Production environment

| | |
|---|---|
| Host | **Cloudways** (DigitalOcean) |
| App URL | `https://wordpress-1661870-6622382.cloudwaysapps.com` — temporary, no domains mapped yet |
| SSH | `master_avjztmdgms@142.93.71.107` |
| App root | `/home/master/applications/xhxwssuusk/public_html` |
| Stack | WordPress 7.0.5, PHP 8.2.33, MariaDB 10.11, nginx |
| WP-CLI | `/usr/local/bin/wp` — available, use it |
| File ownership | `xhxwssuusk:www-data` — correct; WordPress can write |
| Backups | `~/premigration/` (outside the web root) |

Cloudways names the application folder after its database, so `xhxwssuusk` is not guessable —
copy it from the panel if it ever changes.

### The network

| blog_id | Path | Purpose |
|---|---|---|
| 1 | `/` | Network root |
| 2 | `/rivergate-bordentown/` | Rivergate Bordentown |

Subdirectory multisite (`SUBDOMAIN_INSTALL` is `false`). `DOMAIN_CURRENT_SITE` is defined in
`wp-config.php`, which is what makes core domain mapping work without `sunrise.php` — see the
domain cutover runbook before moving a site onto its own domain.

> **It is not WP Engine.** The WP Engine logo in the ACF admin header is vendor branding — WP
> Engine owns ACF. This has caused confusion; the host is Cloudways.

---

## Deploying

`.github/workflows/deploy-rivergate.yml` deploys automatically. There is no manual step and
**theme files must never be edited on the server** — the next deploy overwrites them.

- **Triggers** on push to `main` touching `rivergate-bordentown/theme/rivergate-bordentown/**`
- **Also** available as a manual run (`workflow_dispatch`) with a dry-run checkbox
- `rsync --delete` with `--chmod=D775,F664` so the app user keeps write access
- Warns if theme files changed but `RIVERGATE_VERSION` did not
- Smoke-tests four URLs afterward and **fails the run if any stops returning 200**

Repo secrets it depends on: `CLOUDWAYS_SSH_KEY`, `CLOUDWAYS_HOST`, `CLOUDWAYS_USER`,
`CLOUDWAYS_THEME_PATH`, `SITE_BASE_URL`.

> `SITE_BASE_URL` is used by the smoke test. **It must be updated when a site moves to its own
> domain**, or every future deploy will rsync successfully and then fail at the verify step.

### Versioning

Bump `RIVERGATE_VERSION` in `functions.php` on every deploy — it is what busts CSS/JS caches.
Keep the `Version:` header in `style.css` in step with it: the two drifted (1.4.0 vs 1.16.x) for
weeks, and because wp-admin displays the header, the installed theme looked badly out of date.

---

## ACF field groups: production owns them

The deploy workflow **deliberately excludes `acf-json/`**. ACF writes field-group definitions
into the theme directory when they are edited in wp-admin, so deploying that folder from git
would silently revert the client's work.

Two consequences, both counter-intuitive:

1. **Field groups are not in the database.** The Field Groups screen reads `All (0)` and both
   groups load from the theme's `acf-json/` files. Fields render correctly on the CPT editors
   with **no Sync or Import needed** — do not click Import, it only copies the group into the
   database and costs the repo its place as the reference copy.
2. **A repo change to `acf-json/` does not deploy.** Merging it will run the Action, report
   success, and skip the only file that matters. The JSON has to be placed on the server by hand.

### Copying a field group to the server safely

Write **in place**. The file is owned by `xhxwssuusk` and you connect as `master`, but master is
in the `www-data` group and the file is group-writable, so truncating and rewriting preserves the
owner. Replacing the file (upload, `mv`, `rm` + create) does not, and ACF then cannot write to it.

```bash
git show main:rivergate-bordentown/theme/rivergate-bordentown/acf-json/group_rg_amenity.json \
| ssh <host> 'cat > /home/master/applications/xhxwssuusk/public_html/wp-content/themes/rivergate-bordentown/acf-json/group_rg_amenity.json'
```

Back up first, and verify afterward that ACF actually registers the fields rather than just that
the file parses:

```bash
wp eval '$g = acf_get_field_group("group_rg_amenity");
         foreach (acf_get_fields($g) as $f) { printf("%s (%s)\n", $f["name"], $f["type"]); }' \
  --url=<site url>
```

> **Open decision.** Field group definitions are arguably code — they are what the render
> functions read, and a client editing them is changing the data model, not their content. Worth
> deciding whether git should own them *before* the remaining nine property forks inherit this
> arrangement.

---

## Caching

**Varnish runs at the server level and will mislead you.** Logged-out visitors can be served a
page days old while an admin sees it fresh. A change that "did not deploy" is usually Varnish.

- Check with `curl -sSI <url> | grep -iE 'x-cache|^age'` before concluding anything
- Purge from the Cloudways panel after every change
- Breeze and Object Cache Pro are both **inactive**, their drop-ins parked as
  `wp-content/*.premigration-bak`. This is deliberate and consistent — nothing is half-wired.
  Breeze would duplicate what Varnish already does; Object Cache Pro is worth revisiting once
  more of the ten sites are live and the admin starts feeling slow.

---

## Mail

Nothing is configured yet, so **no form on the site currently delivers anything**.

- FluentSMTP is installed and **network-activated** — expect one shared configuration across all
  sites, not per-property. Use a neutral sender (`website@spgnj.com`, `noreply@spgnj.com`) rather
  than a property-specific address.
- **The theme sets no `From` header anywhere.** Without "Force From Email" turned on, WordPress
  sends as `wordpress@<site domain>` — an address that will not exist and will fail SPF/DKIM.
- Sending through Google Workspace as an `@spgnj.com` mailbox means SPF and DKIM are already
  satisfied by Sterling's existing records. A property domain needs **no mail DNS at all**.
- Prefer an app password over OAuth while domains are still moving: OAuth binds its authorization
  to a redirect URI containing the site domain, so a cutover breaks it.

### Where submissions go

| Form | Recipient |
|---|---|
| Inquiry pop-up | `rivergate@spgnj.com`, hardcoded default, overridable via the `rivergate_inquiry_recipient` filter |
| Contact / tour form | the block's `recipientEmail` attribute — **falls back to `admin_email` when blank** |

`admin_email` is currently a developer address on both blogs. Hand it to the client after launch,
but check every contact block has an explicit recipient first.

---

## Local development

Local by Flywheel, multisite, at `sterling-network.local`.

XAMPP's `Apache2.4` service claims ports 80/443 on every reboot and blocks Local's router. Set
that service to **Manual** rather than stopping it each time.

---

## Traps

Every one of these has cost time on this project at least once.

**1. Block patterns are copy-on-insert.** The single most expensive misunderstanding here. Editing
a pattern in `patterns/` does **not** change a page that already exists — that page's markup was
copied into the database when the pattern was first inserted. Pattern edits need a matching change
in wp-admin or a scoped `wp search-replace`. This has bitten three separate fixes.

**2. Varnish masks changes.** See Caching above.

**3. Never deploy from the working tree on Windows.** Git checks files out with CRLF while the
server holds LF, so packing the working tree rewrites the line endings of every PHP and CSS file.
Deploy from `git archive` or rsync from a CI checkout — which is what the Action does.

**4. A PR's base does not auto-retarget.** GitHub only moves an open PR's base to `main` when the
original base branch is **deleted** on merge. Stacked PRs merged into a kept branch land on that
branch, trigger no deploy, and look merged. Delete branches on merge.

**5. Preview and theme breakpoints must match.** The static previews carry inline base rules that
silently beat `global.css` media queries. Keep both at **900px**.

**6. `global.css` has two white-surface selector lists.** A new light panel must be registered in
both or it renders white-on-white — the themes are blue-dominant and light panels are the
exception.

**7. The Google Maps API key is hardcoded** as `RIVERGATE_MAPS_API_KEY` in `functions.php` and
used by the neighborhood-explorer block. If it is referrer-restricted in Google Cloud Console, any
new domain must be added or the map renders empty with the error only in the browser console. It
is published in page source by design, so it **must** be referrer-restricted — an open Maps key is
billable by anyone who finds it.

**8. Creating one amenity switches the whole section.** `rivergate_get_amenities()` falls back to a
hardcoded list only while the `rg_amenity` CPT is empty. Publishing a single entry drops the
homepage from ten amenities to one, so all of them must be created in a single pass. The same is
true of `rg_floor_plan`.

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
| App URL | `https://wordpress-1661870-6622382.cloudwaysapps.com` — network root + Network Admin only; its TLS cert no longer matches (see Domain cutover) |
| TLS | One Let's Encrypt cert for the app, listing every property domain (rivergatenj.com, bellclair.com, each with www). Adding a domain = add it to that list in Cloudways → SSL Certificate and save; never drop the others |
| SSH | `master_avjztmdgms@142.93.71.107` |
| App root | `/home/master/applications/xhxwssuusk/public_html` |
| Stack | WordPress 7.0.5, PHP 8.2.33, MariaDB 10.11, nginx |
| WP-CLI | `/usr/local/bin/wp` — available, use it |
| File ownership | `xhxwssuusk:www-data` — correct; WordPress can write |
| Backups | Nightly to SharePoint, plus the newest two in `~/backups/` (see Backups & updates). Ad-hoc pre-change exports in `~/premigration/` |

Cloudways names the application folder after its database, so `xhxwssuusk` is not guessable —
copy it from the panel if it ever changes.

### The network

| blog_id | Path | Purpose |
|---|---|---|
| 1 | `/` | Network root |
| 2 | `rivergatenj.com` `/` (was `/rivergate-bordentown/`) | Rivergate Bordentown |
| 3 | `bellclair.com` `/` (was staging host `/bellclair-montclair/`) | Bellclair at Montclair |

Subdirectory multisite (`SUBDOMAIN_INSTALL` is `false`). `DOMAIN_CURRENT_SITE` is defined in
`wp-config.php`, which is what makes core domain mapping work without `sunrise.php` — see the
domain cutover runbook before moving a site onto its own domain.

> **It is not WP Engine.** The WP Engine logo in the ACF admin header is vendor branding — WP
> Engine owns ACF. This has caused confusion; the host is Cloudways.

---

## Deploying

`.github/workflows/deploy-rivergate.yml` and `deploy-bellclair.yml` deploy automatically, each
watching only its own theme folder. There is no manual step and
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
- Purge from the Cloudways panel after every change, or from SSH (Varnish is on port 8080):
  `curl -X PURGE -H 'Host: <domain>' -H 'X-Purge-Method: regex' 'http://127.0.0.1:8080/.*'`
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

### Leads — the saved copy of every submission

There is no form plugin. Both forms are theme code, and since Rivergate 1.18.0 / Bellclair 1.1.0
each submission is **saved before it is emailed**, as a private post of the `<prefix>_lead` type
(`inc/leads.php`). wp-admin → **Leads** lists them with the form, the contact details and the
email result: *Emailed to …*, *FAILED to email …* (with the mailer's error on the lead), or
*Caught as spam*. So a lead survives a failed, bounced or spam-filtered email. Only editors and
admins can see Leads, and they cannot be created by hand.

- **Spam checks** replace the nonce, which expired inside pages Varnish had cached for more than a
  day: a hidden honeypot field (`.form-hp`) and a minimum of 3 seconds on the page (`main.js`
  section 15; an empty value, meaning no JavaScript, is allowed). A caught submission is shown the
  normal thank-you, is not emailed, and goes **straight to the Trash**, where it can be restored
  until WordPress empties the Trash after 30 days.
- **Retention**: real leads are kept until someone deletes them. They are personal data, so agree
  a retention period with Sterling before the list gets long.
- **Testing**: on Local, Mailpit catches the email and the lead appears under Leads. A submission
  made less than 3 seconds after page load (e.g. a scripted POST with `<prefix>_elapsed=1`) is
  treated as spam by design.

---

## Backups & updates

Two scheduled GitHub Actions workflows, sharing one concurrency group so they never overlap.
They are scripts rather than an AI task: `scripts/maintenance/` does the work, and every run
emails its result, success or failure, from Microsoft 365 through Graph.

| Workflow | When (UTC; New York is 4–5 h behind) | Does |
|---|---|---|
| `backup.yml` — Back up production | Daily 07:15 | Whole network DB, uploads, every theme's `acf-json/`, version manifest → test-import → SharePoint |
| `update.yml` — Update production | Tuesdays 09:00 | Smoke test → pre-update backup → updates → purge → smoke + PHP + error-log check → automatic rollback |

Both can be started by hand from the Actions tab. `update.yml` takes `dry_run` (list and email
only), `allow_major_core`, and `force_postcheck_fail` (test only: fails the post-check to
exercise the rollback).

### Layers

1. **Cloudways server backups**, which you turn on in the panel (Server → Backups). They restore
   the whole server in one click, and are independent of everything below.
2. **The nightly backup** writes to SharePoint, in `Sterling Backups/<tier>/<YYYY-MM-DD_HHMM>/`:
   - `daily` is kept for 14 days, `weekly` (Sundays) for 8 weeks, `monthly` (the 1st) for 12
     months, and `pre-update` for 30 days;
   - `db.sql.gz` is the whole network database. A multisite cannot be restored a site at a time
     without `wp_blogs` and the shared tables, so it is never split;
   - `meta.tar.gz` holds each theme's `acf-json/` (production owns these, see above), plus
     `mu-plugins/` if one exists, and `manifest/` (core and DB version, the plugin, theme and
     site lists);
   - `uploads.tar` is `wp-content/uploads`, about 29 MB in 2026-10. If the media library grows
     large, set `BACKUP_UPLOADS=weekly`;
   - `SHA256SUMS`.

   **A backup only counts once it has been restored.** Each dump is imported into a MariaDB 10.11
   container on the runner, and the run fails unless the site count matches the server and every
   property has published content. The newest two database exports also stay on the server in
   `~/backups/`, which is what an update rollback uses.
3. **`wp-config.php` is deliberately not backed up.** It holds the DB password and salts. Rebuild
   it from the Cloudways panel (Access Details) if it is ever lost.

### What the update run will and won't do

- It applies plugin updates, wordpress.org theme updates, minor core releases and language packs.
  Updated files are handed to the `www-data` group, so WordPress can still update them itself.
- It **holds major core releases**: the email says "WordPress X awaiting approval". Read the
  release notes, then run the workflow manually with `allow_major_core`.
- It **never updates our own property themes** from wordpress.org, even if a slug collides. Those
  ship only through the deploy workflows.
- It skips any plugin listed in `scripts/maintenance/pinned-plugins.txt`. Give the reason and the
  date in the file.
- WordPress's own background minor-core auto-updates stay on. They are the fastest route to
  security releases, which is why core was already at 7.0.7 when this started.
- **It rolls back when the post-check fails:**
  - each plugin and theme is reinstalled at its previous version, and core goes back to its
    previous version;
  - the pre-update database is re-imported **only if the update changed the schema**
    (`db_version`). Anything edited in wp-admin during the few minutes of the run is lost then,
    and the email says so;
  - Varnish is purged and the sites are smoke-tested again. Either result is a red run and an
    email.

Emails arrive with one of these subjects: `Backup OK` / `Backup FAILED at: <step>` /
`Updates applied (N)` / `No updates needed` / `Update check (dry run)` / `Updates SKIPPED — a
site was already failing` / `Updates ROLLED BACK` / `Updates FAILED — manual attention`. The
update email also warns when the newest nightly backup is more than 36 hours old, which is how a
silently skipped schedule gets noticed. If the Graph email step itself breaks (an expired secret,
for example), GitHub still sends its own failure email to whoever last edited the workflow's cron
line.

The emails are Brudda's-branded HTML. `scripts/maintenance/notify.py` fills
`scripts/maintenance/email/template.html` from the run's `summary.env`, `result.env` and
`report.json`. The logo and gradient stripe in `email/` are embedded as inline images, so Outlook
shows them without asking to download pictures. The template is written for Outlook's Word
rendering engine: tables for layout, inline styles, no CSS gradients. Keep it that way when you
change it, and preview a change without sending anything:
`python scripts/maintenance/notify.py update <out-dir> success --preview /tmp/email.html`.

**At every domain cutover, add the property to `scripts/maintenance/sites.txt`.** It drives the
smoke test and the post-update Varnish purges.

### Restoring

Take a fresh `scripts/prod.sh backup <domain> pre-restore` first, so the restore itself can be
undone. Then use whichever of these fits:

- **The whole network, fast:** the newest export is already on the server.
  `scripts/prod.sh sh 'ls -1dt ~/backups/*/'`, then
  `scripts/prod.sh sh 'gunzip -c ~/backups/<dir>/db.sql.gz | wp db import - && wp cache flush'`.
- **From SharePoint:** download the folder and copy the files up with `scp -i
  ~/.ssh/cloudways_sterling` to `~/backups/restore/`, then run the same import. Check the files
  against `SHA256SUMS` first.
- **One property only:** the dump replaces every site. Extract that blog's `wp_<N>_*` table
  blocks from the dump and import only those. Leave `wp_blogs`, `wp_site*`, `wp_users` and
  `wp_usermeta` alone unless they are what was lost.
- **Uploads:** `tar -xf uploads.tar -C wp-content` from the WordPress root, then
  `find wp-content/uploads -user "$(id -un)" -exec chgrp www-data {} + -exec chmod g+w {} +`.
- **ACF field groups:** take them from `meta.tar.gz` and **write them in place** with
  `cat >` (see "Copying a field group to the server safely"). Never replace the file.
- **A plugin version:** use `manifest/plugins.json`, then
  `wp plugin install <slug> --version=<v> --force`.
- Afterwards, purge Varnish for every domain and run `scripts/maintenance/smoke.sh`.

Rehearse a restore occasionally: import a `db.sql.gz` into a scratch database. **Never import it
into the Local multisite**, because the blog IDs and paths differ.

### One-time setup: the Entra app, SharePoint and secrets

The workflows need one Entra ID app registration in the Brudda's tenant, which handles both the
mail and the SharePoint uploads:

1. **Register the app.** In Entra admin center → App registrations → New, name it
   "Sterling Maintenance" (single tenant). Note the tenant ID and client ID. Under Certificates &
   secrets, create a client secret valid for 24 months, and **put its expiry date in a calendar**.
2. **SharePoint (least privilege).** Under API permissions, add **Microsoft Graph → Application →
   `Sites.Selected`** and grant admin consent. Then an admin grants the app write access to the
   one site, for example in Graph Explorer:
   ```
   GET  https://graph.microsoft.com/v1.0/sites/<tenant>.sharepoint.com:/sites/<site>    → site id
   POST https://graph.microsoft.com/v1.0/sites/<site-id>/permissions
        {"roles":["write"],"grantedToIdentities":[{"application":{"id":"<client-id>","displayName":"Sterling Maintenance"}}]}
   GET  https://graph.microsoft.com/v1.0/sites/<site-id>/drives                          → the Documents library id = SP_DRIVE_ID
   ```
3. **Mail, scoped to one mailbox.** Do **not** add `Mail.Send` under Entra API permissions: an
   Entra grant there covers every mailbox in the tenant. Create a shared mailbox (for example
   `alerts@bruddasindustries.com`; it needs no licence) and grant the permission through
   **Exchange RBAC for Applications**, in Exchange Online PowerShell:
   ```powershell
   New-ServicePrincipal -AppId <client-id> -ObjectId <enterprise-app-object-id> -DisplayName "Sterling Maintenance"
   New-ManagementScope -Name "Sterling alerts sender" -RecipientRestrictionFilter "PrimarySmtpAddress -eq 'alerts@bruddasindustries.com'"
   New-ManagementRoleAssignment -App <client-id> -Role "Application Mail.Send" -CustomResourceScope "Sterling alerts sender"
   Test-ServicePrincipalAuthorization -Identity <client-id> -Resource alerts@bruddasindustries.com
   ```
4. **Set the repo secrets:** `M365_TENANT_ID`, `M365_CLIENT_ID`, `M365_CLIENT_SECRET`,
   `M365_SENDER` (the shared mailbox), `MAINT_NOTIFY_TO` (comma-separated recipients) and
   `SP_DRIVE_ID`. The SSH secrets are the deploy ones, reused: `CLOUDWAYS_SSH_KEY`,
   `CLOUDWAYS_HOST`, `CLOUDWAYS_USER`, and `CLOUDWAYS_THEME_PATH`, from which the WordPress root
   is derived.
5. **Test from the dev machine** before relying on the schedule. Export the same variables in
   Git Bash, then:
   ```bash
   echo hello > /tmp/t.txt
   PYTHON=python python scripts/maintenance/graph.py mail --subject "Sterling test" --body-file /tmp/t.txt
   PYTHON=python python scripts/maintenance/graph.py upload /tmp/t.txt test/2000-01-01/t.txt
   PYTHON=python python scripts/maintenance/graph.py prune test 1
   ```
6. **Confirm Cloudways server backups are on:** Server → Backups, daily, retained for 7+ days.

---

## Local development

Local by Flywheel, multisite, at `sterling-network.local`.

| blog_id | Path | Theme |
|---|---|---|
| 2 | `/rivergate-bordentown/` | `rivergate-bordentown` (network-enabled) |
| 3 | `/bellclair-montclair/` | `bellclair-montclair` (enabled for blog 3 only) |

Each theme folder under `app/public/wp-content/themes/` is a **directory junction** into this
repo (`New-Item -ItemType Junction`), so edits show up on refresh with no copy step. The local
blog IDs and paths do not match production — never copy a local database up.

XAMPP's `Apache2.4` service claims ports 80/443 on every reboot and blocks Local's router. Set
that service to **Manual** rather than stopping it each time.

**WP-CLI outside Local's site shell.** Local is installed on `E:` on this machine. Run its bundled
`wp-cli.phar` with Local's PHP and the site's generated `php.ini`, which is what points MySQL at
Local's port (10005) instead of 3306. Put Local's MySQL `bin` on `PATH` for `wp db export`:

```bash
export PATH="$APPDATA/Local/lightning-services/mysql-8.4.0/bin/win64/bin:$PATH"
MSYS_NO_PATHCONV=1 "$APPDATA/Local/lightning-services/php-8.2.29+0/bin/win64/php.exe" \
  -c "$APPDATA/Local/run/nNWYC5ilU/conf/php/php.ini" -d display_startup_errors=0 \
  "E:/Program Files (x86)/Local/resources/extraResources/bin/wp-cli/wp-cli.phar" \
  --path="C:/Users/heatw/Local Sites/sterling-network/app/public" <command> --url=<site url>
# db export also needs: --host=127.0.0.1 --port=10005
```

`nNWYC5ilU` is the site's ID in Local's `sites.json`. Mail sent locally is caught by Mailpit
(`http://localhost:10000`), so form tests go nowhere real.

---

## Helper scripts

`scripts/` holds the repeatable steps, so they are run the same way every time. Run them from
Git Bash at the repo root.

| Script | Does |
|---|---|
| `prod.sh wp …` / `sh '…'` | WP-CLI or a shell command on the server, from the WordPress root. It always takes `--url=<domain>` |
| `prod.sh purge <domain>` | Purges Varnish for one hostname |
| `prod.sh backup <domain> <label>` | Exports one site's tables to `~/premigration/<label>/` |
| `prod.sh pull <remote-path> <local-dir>` | Copies a file or folder down from the server (paths relative to `~`) |
| `prod.sh edit <domain> <id> <spec> [apply]` | Edits page content through `safe-edit.php`: dry run by default, then backup, `wp_slash()` (trap 10), a structure check, and a re-read to verify |
| `prod.sh insert-page <domain> <pattern> "<Title>" [...] [apply]` | Creates a page from a registered pattern (`insert-page.php`) |
| `bump-version.sh <property> [patch\|minor\|X.Y.Z]` | Bumps `<PREFIX>_VERSION` and the `style.css` header together |
| `cache-check.sh <urls>` | Status, `x-cache` and `age`, plus the served theme version compared with `main` |
| `site-qa.py <urls or preview dir>` | Playwright + Edge at 390/900/1440: console errors, 4xx, sideways scroll, page heights |
| `maintenance/smoke.sh` | Every live site in `maintenance/sites.txt`: 200, theme CSS present, no WordPress error page. Read-only |
| `maintenance/backup.sh <out-dir>` | The nightly backup, runnable by hand (see Backups & updates) |
| `DRY_RUN=1 python maintenance/update.py <out-dir>` | Lists available updates without changing anything |

`prod.sh` reads the SSH host, app path and key from `scripts/deploy.env`, which is git-ignored.
If that file is absent, it falls back to the values in the table above and
`~/.ssh/cloudways_sterling`.

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

**9. Never rename or delete a theme image that pages already reference.** Patterns bake
*absolute* URLs into page content at insert time, so the database points at
`/wp-content/themes/<property>/assets/images/…` — every image on the Rivergate homepage is
referenced this way. Because the deploy runs `--delete`, renaming or removing one of those files
takes it off the server while the database keeps pointing at the old path, and the live page shows
a broken image. Adding a new file is always safe. Replacing one *in place* (same filename, new
photo) is not only safe but the cleanest way to swap a photo everywhere it appears at once.

**10. Scripted page writes must `wp_slash()` the content.** `wp_insert_post()` and
`wp_update_post()` *unslash* what they are given, because they expect form-style input. Block
markup holds JSON escapes in its comments: WordPress writes `--` as `\u002d\u002d` and `&` as
`\u0026`. So content passed in raw from `wp eval` or `wp eval-file` silently loses those
backslashes. The page still renders, because the saved HTML is intact, but block attributes
are corrupted. Bellclair's live Availability hero read "Pricing u0026 Online Applications",
and classes like `section--alt` became `sectionu002du002dalt`, which the editor would have
written back into the HTML on the first save. Always
`wp_insert_post( wp_slash( [ ... ] ) )`, then re-read the post and compare. The editor and
`wp post update <id> <file>` handle this themselves.

---

## Domain cutover

Rivergate moved from the staging subdirectory to `rivergatenj.com` on 2026-10-01. Changing the
domain in Network Admin does **not** touch page content. Patterns bake absolute URLs into the
database (trap 9), so every image, PDF and in-page link still pointed at the staging host. Once
the Let's Encrypt cert replaced the default one, that host failed TLS and all of them broke.

For each property, after DNS, Network Admin → Sites → Edit, and SSL:

1. Back up the site's tables:
   `wp db export ~/premigration/domain-cutover/siteN-$(date +%F).sql --tables=$(wp db tables --url=<domain> --format=csv)`
2. Rewrite the old URL **including the old subdirectory path**, leaving `guid` alone. Dry run first:
   `wp search-replace 'https://<staging-host>/<old-path>' 'https://<domain>' --url=<domain> --skip-columns=guid --precise --report-changed-only --dry-run`
   Also dry-run the JSON-escaped form (`https:\/\/…`). It was zero for Rivergate, but block
   attributes can store it.
3. Purge Varnish, then crawl every page and confirm no reference to the staging host remains.
4. Update the `SITE_BASE_URL` repo secret, or the next deploy fails its smoke test.
5. Add the domain to the Google Maps key's allowed referrers (trap 7).
6. Trash WordPress's default "Hello world!" post and "Sample Page". The site becomes indexable
   on its new domain, and both are in the sitemap.
7. Then configure SMTP (see Mail).

> The network root keeps the staging domain (`DOMAIN_CURRENT_SITE` in `wp-config.php`), and its
> cert stops matching once a custom cert is installed. That means a browser warning on Network
> Admin. Decide where the network's primary domain lives before the next property moves.

---

## Content vs code: what a deploy can and cannot reach

The deploy touches exactly one directory — `wp-content/themes/<property>/` — so everything the
client does is structurally safe. Copy edited in the block editor, Media Library uploads, menus,
settings, CPT entries and block attributes all live in the database or `wp-content/uploads/`, and
no deploy has ever touched them.

The seam that *does* carry repo changes to live pages is **dynamic blocks**. `rivergate/amenities`,
`neighborhood-explorer`, `floor-plans`, `contact-form` and `hero` render from PHP at request time,
so editing a render file changes every page using it on the next deploy. Patterns are the opposite
— see trap 1 — and once the client starts editing, a pattern describes how a *new* page would be
built rather than what is published.

Three habits follow:

- Never re-insert a pattern over a page the client has edited; it replaces their copy wholesale.
- To change live copy, edit the page. Change the pattern too, so the next property fork inherits
  the fix — but do not expect the pattern edit to do anything on its own.
- QA against the live site, not the repo.

**One silent behaviour worth knowing.** If the client replaces a theme-asset photo through the
block editor, that block's `src` becomes an uploads URL and the theme file stops driving that slot
— it is still deployed and completely ignored. So when a swapped theme image does not show up,
check the block's actual `src` before assuming the deploy failed or that Varnish is stale.

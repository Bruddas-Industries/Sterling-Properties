# Stage 5 — Production, staging and domain cutover

Every step that writes to production needs the user's go-ahead first. Some steps belong to John
or the client (Network Admin, DNS, Cloudways panel, Google consoles). For those, prepare the
exact values and hand them over rather than looking for a way around them. The STATUS sections
"Live on the staging host" and "Domain cutover — bellclair.com" are the worked example.

## 1. Deploy workflow

Copy `.github/workflows/deploy-bellclair.yml` to `deploy-<name>.yml` and change:
- the name, the `concurrency` group, the `paths` filter and `SRC`
- the version constant in the "warn if not bumped" step
- the secrets, to `<NAME>_THEME_PATH` and `<NAME>_SITE_URL`
- the path guard's suffix, to `/themes/<slug>`
- the smoke-test page list, to this property's pages

**Never point a workflow at another property's theme path.** The rsync runs with `--delete`
and would wipe that theme. That is why the path guard exists.

The user sets the secrets:
- `gh secret set <NAME>_THEME_PATH` =
  `/home/master/applications/xhxwssuusk/public_html/wp-content/themes/<slug>`
- `gh secret set <NAME>_SITE_URL` = the staging URL for now

## 2. Create the site (staging)

1. **Back up the network:**
   `scripts/prod.sh sh 'mkdir -p ~/premigration/<slug>-setup && wp db export ~/premigration/<slug>-setup/network-before-<slug>-$(date +%F-%H%M).sql'`
2. **John creates the site** in Network Admin → Sites → Add New, at path `/<slug>/` on the
   staging host.
3. **Keep it out of search** until launch: `scripts/prod.sh wp option update blog_public 0 --url=<staging url>`.

## 3. Deploy the theme

1. Merge the fork PR. The workflow ships the theme. The first run's smoke test fails because the
   pages don't exist yet. That is expected; re-run it after step 5.
2. Enable and activate the theme for that site only:
   `scripts/prod.sh wp theme enable <slug> --activate --url=<staging url>`
3. Copy `acf-json/` by hand, since the deploy excludes it. Follow OPERATIONS → "Copying a field
   group to the server safely", one file at a time, written in place. Then verify with
   `acf_get_fields`.

## 4. Insert the pages

For each page, dry-run first, then add `apply`:
`scripts/prod.sh insert-page <staging url> <slug>/homepage "Home" slug=home front`

Then:
- re-run the workflow, which should go green
- `scripts/prod.sh purge <staging host>`
- `python scripts/site-qa.py <staging urls>`, comparing heights with local (they should be
  identical)
- the user confirms every page opens in the editor with no invalid blocks

Record "site created; pages inserted" in the STATUS database-changes table, with the backup path.

The staging host's certificate doesn't cover the new site, so browsers warn. That's fine for
internal review, but don't send that link to the client.

## 5. Launch decision

The client approves going live, and the domain is registered with DNS access arranged. Mail
needs SMTP before the forms deliver anything. FluentSMTP's settings are network-wide (OPERATIONS →
Mail), so once they exist a new property sends with no setup, as long as its theme kept
`inc/email-settings.php`. Check STATUS → Mail; if SMTP is still unconfigured, tell the user that
launching means the forms will not work yet (submissions are still saved under Leads).

## 6. Domain cutover

Follow OPERATIONS → "Domain cutover" exactly. Bellclair's run in STATUS shows the real values.
In order:

1. **DNS** (whoever controls it): `@` A → `142.93.71.107`, and `www` CNAME → `@`. Remove any
   parked records.
2. **John:** Network Admin → Sites → Edit sets the domain and path `/`, and `home`/`siteurl`
   to `https://<domain>`.
3. **Cloudways:** in Domain Management, add the domain and `www`. In SSL, reissue the Let's
   Encrypt certificate with **every existing property domain plus the new ones**. Dropping one
   breaks that live site.
4. **Back up, then rewrite the baked-in URLs.** Include the old subdirectory path. Dry-run
   first, and dry-run the JSON-escaped form too:
   - `scripts/prod.sh backup <domain> domain-cutover`
   - `scripts/prod.sh wp search-replace 'https://<staging-host>/<slug>' 'https://<domain>' --url=<domain> --skip-columns=guid --precise --report-changed-only --dry-run`
5. **Purge every hostname involved.** Then crawl every page and confirm that no staging-host
   reference remains (`curl -s … | grep -c cloudwaysapps`).
6. **Update `<NAME>_SITE_URL`** to `https://<domain>`, then run the workflow manually. It must go
   green with strict TLS.
7. **Maps key:** add the domain to its allowed referrers (trap 7).
8. **Trash the starter content:** "Hello world!" and "Sample Page". Both are in the sitemap once
   the site is indexable.
9. **Indexing:** `blog_public 1`. Confirm `robots.txt` points at `wp-sitemap.xml`.
10. **Search Console:** John creates a URL-prefix property and gives you the HTML-tag token.
    Add it to `inc/seo.php` (homepage only) and ship it as a version bump. Never remove it
    afterwards, because Google re-checks.
11. **Verify** with `python scripts/site-qa.py https://<domain>/ …`. Also check the redirects:
    `www` → apex, and `http` → `https`.
12. **Add the property to `scripts/maintenance/sites.txt`**, with its domain and the same pages
    the deploy workflow smoke-tests. The weekly update run smoke-tests and purges only the sites
    listed there, so a missing site would be updated blind.

## 7. Record it

Update:
- STATUS: the property's "Live at" line, the cutover details and backup paths, and new rows in
  the database-changes table
- OPERATIONS: "The network" table and the TLS certificate list
- README: the sites table

From here on, changes to this property go through **edit-site-prod**.

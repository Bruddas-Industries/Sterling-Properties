# Project Status

**Last updated: 2026-11-13.** Update this file when something here changes — it is the first
thing to read when picking the project back up.

Environment and deploy details are in [`OPERATIONS.md`](./OPERATIONS.md); architecture and
conventions in [`CLAUDE.md`](../CLAUDE.md).

---

## Where Rivergate stands

Property #1 of 10. **Live at `https://rivergatenj.com`** since the 2026-10-01 domain cutover
(see "Domain cutover" below).

- Theme **1.18.4** (2026-10-24): sends as `Rivergate@spgnj.com`, so FluentSMTP uses the Rivergate
  connection, and lead emails can be BCC'd through the `sterling_lead_bcc` network option
  (OPERATIONS → Mail). Same as Bellclair 1.1.4.
- Theme **1.18.3** (2026-10-23, deployed and purged): a network-wide **Email settings** switch on Network Admin
  → Users → Edit gives a user FluentSMTP access on every site. Same as Bellclair 1.1.3.
- Theme **1.18.2** (2026-10-23, deployed and purged): one FluentSMTP configuration for the whole network, and an
  Editor granted `sterling_manage_email` can configure it (OPERATIONS → Mail). Same as Bellclair 1.1.2.
- Theme **1.18.1** (2026-10-23, deployed and purged) serves amenity and floor-plan images from wp-admin
  entries at full size (the live plan drawings are 2000px and were served at 1024), and raises the
  amenities block's default limit from 8 to 12 so all ten show once they move into wp-admin.
- Theme **1.18.0** (2026-10-22, deployed and purged 2026-10-23) saves every form submission as a **Lead** in
  wp-admin before emailing it, and swaps the forms' nonce for a honeypot + 3-second check. See
  OPERATIONS → Mail → "Leads". Same change as Bellclair 1.1.0.
- Theme **1.17.3** (2026-10-12, live and purged 2026-10-14) keeps the open mobile menu clear
  of the logo bar. Varnish had been serving logged-out visitors a 42-hour-old page that still
  loaded the 1.17.2 CSS until it was purged.
  Details: On phones up
  to ~760px of visible height (iPhone Safari) the centred overlay overflowed upward and the first
  link sat under the logo. It also stops pages scrolling sideways on narrow phones (the homepage
  was 12px too wide at 390px). Same fix as Bellclair 1.0.1; CSS-only, in a new `global.css`
  section 29.
- Theme **1.17.2** (2026-10-02) renames "Full-Time Onsite Maintenance" to "Onsite Maintenance"
  at the client's request and adds a full-bleed rule between the homepage gallery teaser and the
  neighborhood section, which both sit on the blue field and had run together. The rule is
  CSS-only (`.feature + #neighborhood` in `global.css` section 7), so no page content changed.
- Theme **1.17.0** adds search and sharing metadata (`inc/seo.php`; see "Search (SEO)" below).
  1.16.6 removed the users sitemap and 1.16.7 swapped the WordPress favicon for the logo's square
  mark (`assets/images/site-icon/`, served through the `get_site_icon_url` filter, so a
  Customizer Site Icon still overrides it)
- All **16 client edits** requested by Andrew Zuckerman (8/26) and Nina Chichelo (8/18) are
  implemented and live — verified against the rendered page, not just the commits
- The homepage, amenities, floor plans, gallery, neighborhood and contact sections are all built
  as native block patterns + dynamic blocks

Bellclair (below) is property #2. Riverbend (below that) is property #3, on the staging host.
The Arden (below that) is property #4, with a coming-soon splash page in client review. The other
six have not been started. Everything in
`OPERATIONS.md` and the conventions in `CLAUDE.md` is written to be inherited by those forks.

---

## Where Bellclair stands

Property #2 of 10: **Bellclair at Montclair**, Montclair NJ. **Live at `https://bellclair.com`** since the 2026-10-15 domain cutover (see "Domain cutover — bellclair.com" below). Indexed since 2026-10-15; Search Console verified by HTML tag (theme 1.0.5). Brief: Nina Chichelo's email
"Next Website: Bellclair" (8/17/26, cc Andrew Zuckerman). Bellclair has never had its own site,
only a page on sterlingpropertiesnj.com.

**Static preview built** (2026-09-30, branch `feature/bellclair-preview`) in
`bellclair-montclair/preview/`: a one-page homepage plus Residents, Availability and Gallery. It
is named `preview/`, not `preview-1/`, at John's request. Hosted on Vercel at
`https://bellclair-montclair-preview.vercel.app/` (see "Vercel" below).

- `bellclair-montclair/theme/bellclair-montclair/` is now the **full WordPress theme** (see
  "WordPress fork" below). The preview still links to its `global.css` and images, so the
  preview and the theme share one stylesheet.
- `global.css` is Rivergate's stylesheet re-skinned by a scripted, exact-match transform. The
  scheme is inverted: Rivergate is blue-dominant, Bellclair is a light ivory field with black
  bands. Its header comment and section 4 explain the difference, and all Bellclair-only
  styles are in section 29. **A new dark band must be added to both selector lists in
  section 4**, the inverse of the white-surface trap in Rivergate.
- The preview carries no layout CSS of its own. `preview.css` is just the preview badge, and
  all shared behaviour is in `preview/site.js`. So OPERATIONS trap 5 (preview inline rules
  beating theme media queries) cannot happen here.
- Brand: `Bellclair-BrandGuidelines_C01.pdf` (New World Group). Its palette is in `:root`.
  Gold and copper fail contrast as small text, so eyebrows and links use a deepened bronze
  (`--color-primary #7A5719`). Gold is used only for fills, rules and icons.

**Client edits (2026-10-08, branch `feature/bellclair-client-edits`).** Applied from John's list
using the SharePoint share, now synced locally (`07_Assets/Bellclair (Montclair, NJ)`):
- **Hero video + film.** Hero = VTC "Wide_Silent" cut trimmed to 2.8–83.7 s (title card and
  end card removed). That cut has **no** interview shots; Andrew appears only in the v9 FINAL,
  which plays with sound in the "Watch the Film" lightbox. Encodes and commands are in
  `assets/video/README.txt` (hero 13.7 MB desktop / 8.7 MB 720p for phones, film 34.7 MB).
- **Floor plans.** Eight plans from "Updated Floorplans/JPG", smallest → largest: Calloway 745,
  Armstrong 775, Holloway 825 (loft), Basie 850, Dorsey 1,075, Ellington 1,125, Duplex 1,300,
  Fitzgerald 1,383. The one-bedroom placeholder is gone. Data is `PLANS` in `preview/site.js`.
  Armstrong has a Matterport tour (`PVaodWhQSWT`). Matterport's own titles confirm Dorsey =
  `hrsGWLg4gYT` and Ellington = `ah1D28hoCbj`; the "Dorsey NDR Matterport.docx" on SharePoint
  wrongly lists Ellington's link.
- **Amenities.** Landscaped Grounds → EV Chargers (photo is a ChargePoint frame from the film).
  Added "24/7 On-Site Maintenance", as the client requested; the Residents card says the same.
- **Photos.** Residences are **renovated units only**. On SharePoint, **"NDR" folders are the
  un-renovated units** (beige granite, tile kitchens, almond tub surrounds); the renovated
  finish (white shaker uppers, dark lowers, white quartz) is in "Armstrong (1 BR)",
  "Ellington (2 BR)", "Calloway (8 Bell Street)", "Halloway24" and the six `DSC0562x–5651`
  frames at the end of "Dorsey NDR". Every previous residence photo was from an NDR unit and
  has been replaced. Virtually staged shots are captioned as such. Skipped: the "(Edited)" and
  "(Golden Hour)" exterior PNGs (AI-edit mark, fabricated sky).
- **Gallery** opens with the film, then Residences, Building & Amenities, Exterior, and
  Around Montclair (stills from the silent cut).

**WordPress fork — running locally (2026-10-12, branch `feature/bellclair-wordpress`).** Not on
production yet; that waits for John's go-ahead. Theme **1.0.0**, forked from Rivergate 1.17.2.
- **Local site:** blog 3, `http://sterling-network.local/bellclair-montclair/`. The theme is a
  directory junction from `wp-content/themes/bellclair-montclair` into the repo, like Rivergate's,
  and is enabled for that site only (not network-wide). Pages: Home (front page), Availability,
  Gallery, Residents — each inserted from its composite pattern on the Block Canvas template.
  There are no Amenities, Floor Plans, Contact or Location pages; those live on the homepage.
- **Re-prefixed throughout:** `bellclair_` functions, `BELLCLAIR_` constants, `bellclair/*` blocks,
  `is-style-bellclair-*` buttons, `bc_amenity` / `bc_floor_plan` CPTs, `group_bc_*` ACF groups.
- **New vs Rivergate:** a `bellclair/film-frame` block and film modal (any `[data-film]` opens
  it); the hero takes a second, phone-sized video; the floor-plan panel shows the building, a
  "3D Tour" tag, a Laundry row and a "drawing coming soon" placeholder (Duplex); the map uses
  Bellclair styling, walking directions and an OpenStreetMap fallback. ACF gained `plan_where`
  and `plan_laundry` plus `leasing` / `cat` icon choices. Dropped as unused: the `amenity-cards`
  and `plan-cards` blocks and the Amenities / Floor Plans / Contact / Location page patterns.
- **Data is in code, as on Rivergate:** the seven amenities and eight plans are the fallback lists
  in `blocks/_shared.php`, and the neighborhood places and times are in the explorer's
  `render.php`. Trap 8 applies — create CPT entries all at once or not at all.
- **Spacing:** `global.css` section 2 cancels WordPress's default 24px block gap
  (`:root :where(.is-layout-flow) > *`), which otherwise added up to ~100px per section over the
  approved preview. Rivergate still has the gap. After this, every page measures within 0.5% of
  the preview's height at desktop and phone widths.
- **QA (2026-10-12, Playwright + Edge):** no console errors; every asset 200; all four pages open
  in the block editor with zero invalid blocks; tour form and pop-up both deliver to
  `bellclaire@spgnj.com` (Local's Mailpit). The map shows the OpenStreetMap fallback locally
  because the Maps key refuses `sterling-network.local`.
- **Found on the way, Rivergate:** `patterns/parts/communities.php` has an HTML comment inside
  the block markup, which makes the editor flag that group as invalid ("unexpected content").
  Fixed in Bellclair's copy; Rivergate's homepage likely shows the warning. Not yet fixed there.

**Live on the staging host (2026-10-12), no domain yet.** Production blog 3:
`https://wordpress-1661870-6622382.cloudwaysapps.com/bellclair-montclair/`.
- Search engines are discouraged (`blog_public` 0; pages carry `noindex, nofollow`). The staging
  host's certificate covers only rivergatenj.com, so browsers warn before showing the site — fine
  for internal review, not something to send the client.
- Theme 1.0.0 deployed by `deploy-bellclair.yml` (PR #34) and enabled for blog 3 only. Secrets
  `BELLCLAIR_THEME_PATH` and `BELLCLAIR_SITE_URL`; the workflow refuses any path not ending in
  `/themes/bellclair-montclair`. Its first run failed only the smoke test because the pages did
  not exist yet; the re-run was green.
- `acf-json/` copied by hand from `main`; ACF registers both groups, including `plan_where` and
  `plan_laundry`.
- Pages 6–9 (Home, Availability, Gallery, Residents) inserted from their patterns via
  `~/premigration/bellclair-setup/setup-pages.php`; Home is the front page. Varnish purged.
- QA on production (Playwright + Edge): no console errors or failed requests, all 56 assets 200,
  page heights identical to local, all four pages valid in the block editor. The Google map
  renders there (the key already allows the Cloudways host).
- **Forms:** both are addressed to `bellclaire@spgnj.com`. SMTP is configured (see "Mail" below),
  but no Bellclair form has been test-submitted on production yet.
- Theme **1.1.4** (2026-10-24): sends as `Bellclaire@spgnj.com`, so FluentSMTP uses the Bellclair
  connection; before this, Bellclair mail would have gone out as "Rivergate Website". Lead emails
  can be BCC'd through `sterling_lead_bcc`, as on Rivergate 1.18.4.
- Theme **1.1.3** (2026-10-23, deployed and purged): the network-wide Email settings switch, as on Rivergate
  1.18.3.
- Theme **1.1.2** (2026-10-23, deployed and purged): network-wide FluentSMTP settings and the
  `sterling_manage_email` grant, as on Rivergate 1.18.2.
- Theme **1.1.1** (2026-10-23, deployed and purged): amenity and floor-plan images from wp-admin
  entries at full size, and an amenities limit of 12; groundwork for moving both lists into wp-admin.
- Theme **1.1.0** (2026-10-22, deployed and purged 2026-10-23): submissions are saved as **Leads** in wp-admin
  before they are emailed, so nothing is lost while SMTP is unconfigured; the nonce is replaced by
  a honeypot + 3-second check. See OPERATIONS → Mail → "Leads". Same change as Rivergate 1.18.0.
- **Backup** taken before any of this:
  `~/premigration/bellclair-setup/network-before-bellclair-2026-10-13-0203.sql`.

**Domain cutover — bellclair.com (2026-10-15).**
- DNS (GoDaddy, registered 2026-10-12): `@` A → `142.93.71.107`; `www` CNAME → `@`. Nina made the
  change; GoDaddy's parked A records are gone.
- John changed site 3's URL in Network Admin: `wp_blogs` domain `bellclair.com`, path `/`, and
  `home`/`siteurl` `https://bellclair.com`.
- Cloudways: `bellclair.com` and `www.bellclair.com` added under Domain Management (primary stays
  rivergatenj.com). The Let's Encrypt certificate was reissued with **all four names** —
  rivergatenj.com, www.rivergatenj.com, bellclair.com, www.bellclair.com — issued 2026-10-15,
  expires 2027-01-13, auto-renewal on. Saving the domain list reissues it; "Renew Now" is not
  needed. **Any future property domain must be added to this same list, keeping the others.**
- Page content: 56 staging URLs on the four live pages (114 in total, counting revisions and the
  trashed starter content) were rewritten with
  `wp search-replace 'https://wordpress-1661870-6622382.cloudwaysapps.com/bellclair-montclair'
  'https://bellclair.com' --skip-columns=guid --precise`. The JSON-escaped form had 0 matches,
  nothing remained in postmeta or options, and 0 references remain. Varnish purged for all three
  hostnames.
- `BELLCLAIR_SITE_URL` set to `https://bellclair.com`, so the smoke test no longer skips the
  certificate check. A manual workflow run after the cutover was green.
- Verified with strict TLS (Playwright + Edge): all four pages, all 56 assets, `www` → apex and
  `http` → `https` redirects, no console errors. The Google map renders, so the Maps key already
  accepts bellclair.com. The old staging path now returns 404; it was never public or indexed.
- **Indexing on** 2026-10-15 (`blog_public` 1): pages carry `max-image-preview:large`, and
  `robots.txt` points at `wp-sitemap.xml`, which lists the four pages. `?author=` and `/author/`
  301 home; the logged-out users REST route returns no route.
- **Search Console:** URL-prefix property `https://bellclair.com/`, HTML-tag method. The token is
  in `inc/seo.php` (theme 1.0.5, homepage only). Do not remove it, because Google re-checks.
  John verifies and submits the sitemap.
- SMTP: configured 2026-10-23; see "Mail".
- Backup before the rewrite: `~/premigration/domain-cutover/site3-bellclair-2026-10-15-1815.sql`.

**Sources for content and assets (original 2026-09-30 build).** Before the SharePoint share was
available, everything came from:
- The email's 3 logo PNGs and the brand PDF. The cream/gold on-dark logo and the favicon were
  derived from the black/gold PNG, following the guide's "Color Usage" panel.
- 9 photos from the Sterling portfolio page (`sterlingpropertiesnj.com/sterling-portfolio/bellclair-at-montclair/`)
- Sterling's AppFolio listings (filter `property_list=BELLCLAIRE, LLC`): 13 interior and aerial
  photos, two branded floor-plan sheets (**Dorsey** 2bd/1ba 1,075 sf, **Ellington** 2bd/2ba
  1,125 sf), two Matterport tours (`hrsGWLg4gYT`, `ah1D28hoCbj`), the amenity and feature copy,
  and the pet policy

### Open — Bellclair

**Theme fix, not urgent:**
- **The closing-CTA eyebrows ("Ready to Move In?" and the inner pages' CTAs) render in muted
  ivory with a 40px gap**, not in the accent colour with 16px as on the approved preview
  (checked on bellclair.com, 2026-11-09). In the patterns the eyebrow is a `<p>`, so
  `.cta-section p` outranks `.eyebrow`. Riverbend's fix is two rules in its section 29m
  (`.cta-section p.eyebrow`); port them and bump the version. Rivergate has not been checked.

**Waiting on Nina:**
- **Duplex drawing — built 2026-10-14 (theme 1.0.3).** Nina's screenshots of the plan from a
  listing site were rebuilt onto the blank Duplex template: Unit A, with the garage level,
  first floor and second floor, and labels retyped to match the other sheets. Sources and
  script are in `bellclair-montclair/floorplan-sources/duplex/`. The plan is three levels: a
  private tandem garage (11'8" × 43'8"), storage and a shared entry at ground level, then two
  living floors. **The drawing shows 2.5 baths** (a powder room plus two full baths), but the
  sheet and the site say 2. Confirm with the client. Swap in a clean drawing from the Regional
  Manager if one arrives, keeping the filename `duplex.jpg`.
- **A second "Holloway" sheet** (`Holloway (2).jpg`: 691 Bloomfield, 2bd/2ba, 1,134 sf) is a
  different plan under the same name. Only the 1bd/825 sf loft is on the site. Ask what the
  2-bed plan is called and whether it belongs in the selector.
- Plan names follow the sheets: **Basie** and **Calloway** (not "Bassey"/"Callaway").
- **Licensed webfonts.** Micaroline and ITC Avant Garde Gothic Pro are commercial. The preview
  uses Poiret One and Questrial as stand-ins. To switch, drop in the files and uncomment the
  `@font-face` block at the end of `global.css` (see `assets/fonts/README.txt`).

**Confirmed by the client (2026-10-14), live in theme 1.0.2 and on production pages 6 and 9:**
- **Leasing office hours:** Tuesday–Saturday 10 AM–5 PM, closed Sunday and Monday, **by
  appointment only** (2026-10-15: the leasing manager is not on site full time, so every visit
  is by appointment within those hours). The appointment line sits in bold above the hours. These are in
  the homepage contact section and in the JSON-LD (`openingHoursSpecification`, `inc/seo.php`),
  so change both together.
- **Early lease termination:** the resident stays responsible through the full term, but may end
  the lease early with 60 days' written notice plus a two-month rent penalty, and owes rent
  through the notice period on top of the penalty. Two lines were added to the Residents page's
  Move-Out block, which is now titled "Move-Out & Lease Termination".

**Copy to confirm with the client.** All of these came from AppFolio listing text or were
carried over from Rivergate, and none has been verified with Sterling:
- "Hardwood floors throughout" (AppFolio) was **deliberately left out**. The photos look like
  vinyl plank, and Rivergate's flooring rule shows Sterling cares about this wording. Ask
  before saying anything about floors.
- "Gas ranges" (AppFolio, townhome text) was **left out**. The kitchen photo shows an
  electric coil range.
- The townhome collection at 8 Bell St (private 2-car garages, loft/duplex units, 14 ft
  ceilings) is only described as "select residences". EV charging says only "ChargePoint
  electric-vehicle charging on site". **John, 2026-10-14: treat both as correct unless the
  client says otherwise.** Do not add detail (charger count, fees, unit list) without a source.
- The contact section now lists residences at "7 & 8 Bell Street and 691 Bloomfield Avenue",
  since the Holloway lofts are at 691 Bloomfield.
- Residents page: **quiet hours and the parking rules** are still Rivergate's text, and
  "Maintenance Requests" points at the AppFolio portal (Rivergate uses Pilera). The Regional
  Manager and Bellclair staff are confirming all three (asked 2026-10-14). The after-hours line
  is assumed to be the office number.
- ~~The email address~~ **Confirmed by the client 2026-10-14:** `bellclaire@spgnj.com`, spelled
  with an **e**, is correct.
- The address: the email says "691 Bloomfield Avenue 7 8". The units are **7 and 8 Bell
  Street** and the leasing office is 691 Bloomfield Avenue. Both appear in the contact section.

**Infrastructure:**
- **Google Maps key is referrer-restricted.** It refused `localhost`. Add the Vercel preview
  domain (and later the production domain) to the key's allowed referrers. If Google refuses
  the domain, the preview falls back to an OpenStreetMap embed (`gm_authFailure` in `site.js`),
  so the client never sees an error box.
- **Vercel.** Both previews are in the team "jjosephsen-bruddas' projects"
  (jjosephsen@bruddasindustries.com), as `bellclair-montclair-preview` and
  `rivergate-bordentown-preview`.
  - **How they deploy.** Each project is Git-connected to this repo, with the property folder as
    its Root Directory (the preview links to `../theme/`). `main` is the production branch, so
    every merge rebuilds both, whatever folder changed.
  - **Client links:** `https://bellclair-montclair-preview.vercel.app/` (vercel.json redirects to
    `/preview/`) and `https://rivergate-bordentown-preview.vercel.app/preview-1/`. Rivergate's
    root returns 404, because it has no redirect.
  - **Branch deployments are behind Vercel login** (Deployment Protection), so they are not
    client links.
  - **Theme PHP was exposed.** Until 2026-10-16 both projects served the theme's PHP as plain
    text (e.g. `/theme/bellclair-montclair/functions.php` returned 200). The only sensitive-looking
    value was the Maps key, which is public by design. Each property folder now has a
    `.vercelignore` that keeps everything but `theme/*/assets/` off Vercel.
- The "More Communities" cards are the six the client chose (2026-10-15): Riverbend
  (Florham Park), MillPond (Franklin Lakes), Canterly Place (Livingston), Station Court
  (Berkeley Heights), Columbia Court (Springfield) and Lafayette Court (Morristown). Each links
  to its Sterling Portfolio page, all of which returned 200 that day; the towns come from those
  pages. "MillPond" follows Sterling's own spelling. Station Court's portfolio URL misspells
  "berkely", but the card text spells Berkeley correctly.

**Neighborhood times are measured, not estimated.** They were routed from 7 Bell St (US Census
geocode 40.817948, −74.222495) with OSRM, 2026-09-30: foot routing for anything under 25
minutes on foot, car routing beyond that. Like Rivergate's, re-measure rather than guess if a
place is added or moved. The nearest train station (Walnut Street) is a **22-minute walk**,
and Bay Street, the main Midtown Direct stop, is a 27-minute walk or 4-minute drive. So the
copy never calls the trains "steps away". Church Street (5 min), the Art Museum (5 min) and
Whole Foods (2 min) are the walkable highlights. Route data: OSRM foot and car profiles,
OpenStreetMap geocodes, with each POI's coordinates in the `data-lat`/`data-lng` of its list
item in `index.html`.

---

## Where Riverbend stands

Property #3 of 10: **Riverbend at Florham Park**, 1901 Sterling Drive, Florham Park NJ 07932.
Brief: Nina Chichelo's email "Next Website: Riverbend" (2026-10-20, to John and Chris). Nina
describes it as "small, community-oriented". Sterling is still writing text for the new site, so
the brief carries only the contact facts. Branding and visuals are complete. The meeting is the
following Monday.

**Stage: live on the staging host (2026-11-10), no domain yet.** Production blog 4:
`https://wordpress-1661870-6622382.cloudwaysapps.com/riverbend-florhampark/`. The fork (PR #56) and its deploy workflow (PR #57)
are merged. The static preview in
`riverbend-florhampark/preview/` was approved (confirmed by John, 2026-11-09). It is a one-page
homepage plus Residents, Availability and Gallery, as at Bellclair. The Vercel project is
`riverbend-florhampark-preview` (`https://riverbend-florhampark-preview.vercel.app/`). The theme
`riverbend-florhampark` 1.0.0 is forked from Bellclair 1.1.4 and runs in Local as blog 4 too.
What remains is the launch decision and the domain cutover. From here on a copy change goes in
the preview, the patterns **and** the production page content (patterns are copy-on-insert), and
production changes go through **edit-site-prod**.

**Live on the staging host (2026-11-10).**
- Search engines are discouraged (`blog_public` 0; pages carry `noindex, nofollow`). The staging
  host's certificate does not cover it, so browsers warn — fine for internal review, not a link
  to send the client.
- Theme 1.0.0 deployed by `deploy-riverbend.yml` and enabled for blog 4 only. Secrets
  `RIVERBEND_THEME_PATH` and `RIVERBEND_SITE_URL` (the staging URL for now). The first run failed
  in rsync because the path secret had been set from Git Bash, which rewrote it to a Windows path
  (OPERATIONS trap 11); re-set from PowerShell. The next run failed only the smoke test (no pages
  yet), and the run after the pages went in was green.
- Theme **1.0.1** (2026-11-13): the old site's URLs 301 to their new homes (`/amenities/`,
  `/floor-plans/`, `/photo-gallery/`, `/portfolio/*`, `/view-availability/`, the four
  `/request-availability-the-<plan>/` pages, `/location/`, `/contact/`, `/thank-you/`, `/about/`,
  `/residents-4-blocks/`). Path-matched and only on a 404, so a real page always wins; the old
  theme's demo pages (samples, features, our-work, blog) are left to 404. Map in
  `functions.php` §10.
- `acf-json/` written in place from `main` (master:www-data, 775/664, as on Bellclair); ACF
  registers both groups (4 amenity fields, 13 floor-plan fields).
- Pages 4–7 (Home, Availability, Gallery, Residents) inserted from their patterns with
  `scripts/prod.sh insert-page`; Home is the front page. Varnish purged.
- QA on production (Playwright + Edge, certificate ignored): page heights identical to local on
  all four pages at 390/900/1440, no console errors, failed requests or sideways scroll, and no
  references to the local host. The Google map renders (the key already allows the Cloudways
  host).
- **Not done yet:** a check that every page opens in the editor with no invalid blocks (the
  content is the same as local, where all four passed). "Hello world!" and "Sample Page" are
  still there; trash them at cutover.
- **Forms:** both are addressed to `riverbend@spgnj.com` and send as it, but there is no
  FluentSMTP connection for that address yet, so until Rob adds one (sender exactly
  `riverbend@spgnj.com`, lowercase, as `RIVERBEND_MAIL_FROM`) mail would go out through the
  default Rivergate connection. Submissions are saved under Leads either way. Not test-submitted
  on production.

**WordPress fork (theme 1.0.0, 2026-11-09).**
- **Base.** `bellclair-montclair` 1.1.4, copied in around the preview's `assets/` and re-prefixed
  per the naming below. Rivergate had no fixes since 1.17.2 that Bellclair lacks.
- **Dropped from the base:** the film-frame block, the film pattern and band, the film modal
  (footer and `main.js`), the videos, and Bellclair's eight plans (Duplex included).
- **Added:**
  - the shuttle band (`patterns/parts/shuttle.php`) in place of the film band. Its intro and
    stat cards are core blocks; the timetable is a new dynamic block,
    `riverbend/shuttle-schedule`, whose times are edited in the sidebar, one per line
    (`5:22 AM | arrives 5:28`).
  - a photo hero (main entrance), with an optional background video if Nina supplies one.
  - a "Take a 3D Tour" button in the amenities photo panel (Chatham by default, editable).
  - the preview's line icons for every amenity, plus a new `grounds` icon key (ACF choice too).
  - map directions that default to driving, and walk only for places measured as a walk.
- **Data is in the fallbacks**, as at Bellclair: the ten amenities and four plans in
  `blocks/_shared.php`, the 23 neighborhood places in the explorer's `render.php`. The CPTs stay
  empty (OPERATIONS trap 8).
- **CSS beyond the preview's `global.css`:**
  - Bellclair's `.form-hp` honeypot rule (section 22). The preview's stylesheet was copied before
    the lead-capture change, so without it the "Leave this field empty" trap showed on the form.
  - 29d's wave divider now also matches `.wp-block-separator.divider`. Section 28's flat 2px bar
    outranked it, so every divider on the WordPress pages was a plain bar, not the wave.
  - 29m bridges: shuttle-band groups and stat paragraphs, the residences split's top margin (lost
    to core's first-child reset), the contact column's last margin, and the CTA eyebrow's colour
    and spacing (`.cta-section p` outranked `.eyebrow`; see the Bellclair item under Open).
- **Identity:** titles and descriptions, the JSON-LD (address, the clubhouse pin, phone, three
  opening-hours blocks, pets, amenities) and the share image (main entrance). Bellclair's
  `google-site-verification` token is removed; Riverbend gets its own at go-live. Mail is
  `riverbend@spgnj.com` both ways: the contact block's `recipientEmail` in the pattern, the
  inquiry pop-up default and `RIVERBEND_MAIL_FROM`.
- **Local QA (2026-11-09),** `scripts/site-qa.py` at 390/900/1440: the homepage, Availability and
  Gallery are identical in height to the preview; Residents is within 10px (0.2%). No console
  errors or failed requests. All four pages open in the editor with no invalid blocks (353
  blocks). The tour form and the inquiry pop-up each saved a Lead and reached Mailpit from and
  to `riverbend@spgnj.com`.
- **Local site:** blog 4, `http://sterling-network.local/riverbend-florhampark/`. Pages: Home 3
  (front page), Availability 4, Gallery 5, Residents 6. The theme junction points at the git
  worktree `D:\Bruddas Industries\sp-riverbend-wp` until this branch merges; repoint it at the
  main checkout afterwards, like the other two.

**Naming (decided with John, 2026-10-20).**
- Slug: `riverbend-florhampark`.
- For the fork: `riverbend_` / `RIVERBEND_`, CPTs `rb_amenity` / `rb_floor_plan`, ACF groups
  `group_rb_*`, blocks `riverbend/*`, buttons `is-style-riverbend-*` (already renamed in
  `global.css`), and `deploy-riverbend.yml` with `RIVERBEND_THEME_PATH` / `RIVERBEND_SITE_URL`.

**Facts and their sources.**
| Fact | Value | Source |
|---|---|---|
| Name | Riverbend at Florham Park ("Riverbend" one word, as in the logo) | Brand sheet, current site, AppFolio |
| Address | 1901 Sterling Drive, Florham Park, NJ 07932 | Brief |
| Phone / email | 973.665.0050 / `riverbend@spgnj.com` | Brief, floor-plan sheets |
| Office hours | Tue & Wed 10 AM – 5 PM · Thu 10 AM – 6 PM · Fri 10 AM – 5 PM · Sat 9 AM – 5 PM · Sun & Mon closed (Nina wrote "OFF") | Nina, "Re: Riverbend Website - First Preview" (2026-10-22) |
| Plans | Madison 1bd/1ba 725 sf (floor 1) · Livingston 1bd/1ba + study 800 sf (floor 1) · Chatham 2bd/2ba 1,050 sf (floor 2) · Florham 2bd/2ba 1,225 sf (floor 2). All in Buildings 1–18; every sheet shows a balcony, walk-in closet, washer/dryer and garage | "Updated Floorplans" sheets (SharePoint) |
| 3D tours | Madison `qRBhDU6A7ZQ`, Livingston `yGQSWLhuVAq`, Chatham `bbuj3Fchx1H` (Matterport's own titles name each plan). Florham has none | AppFolio listings |
| Pets | 2 pets max. Dog: $500 non-refundable deposit + $50/month. Cat: $250 non-refundable deposit + $25/month. The old "small dogs" (AppFolio) and "select buildings" (current site) limits were dropped by John's decision, because Nina's policy doesn't state them | Nina (2026-10-22) |
| Shuttle | Weekday AM/PM route. Riverbend is Stop #3, in front of the Gate House; Stops #1–2 are Sun Valley Buildings 16 and 13 and are not on the site. Mornings leave Riverbend at 5:22, 5:42, 6:07, 6:17, 6:32, 6:47, 7:02, 7:21, 7:37, 7:54, 8:15 and 8:27 AM, each reaching Chatham station 6 minutes later. Evenings leave Chatham station every 15 minutes, 5:30–8:30 PM; the sheet gives no Riverbend arrival times | "Shutte Service Schedule.pdf", attached to Nina's follow-up email (2026-10-22) |
| More Communities | Canterly Place, Lafayette Court, Willow Court | Nina (2026-10-22) |
| Resident portal | AppFolio (`sterlingproperties.appfolio.com/connect`), for rent and service requests | Current site's Residents page |
| Apply / availability | AppFolio city filter `filters[cities][]=Florham Park`, which returns exactly Riverbend's four listings (2026-10-20). Riverbend has no `property_list` value of its own, unlike Bellclair's `BELLCLAIRE, LLC` | AppFolio |
| Amenities | clubhouse, fitness center, pool and patio, BBQ, private entrances, garages with remote openers, weekday morning-and-evening shuttle to Chatham station, landscaped grounds with snow removal, onsite and 24/7 emergency maintenance, individual storage | Current site's Amenities page, Sterling portfolio page |
| More amenities | EV charging, quartz countertops, gas heat, central air, private garage with direct entry into the home | AppFolio listing amenities |
| Domain | `riverbendatflorhampark.com`, in Sterling's GoDaddy account | Brief |
| Utilities | **Residents pay water and sewer.** Never list them (or any utility) as included. The old portfolio listing's "Utilities included: Garbage, Water, Sewer" is wrong; the preview never carried it (checked 2026-11-05). Garbage is unconfirmed either way | Riverbend office via Nina, "Fw: Riverbend website" (2026-10-30) |

**Brand.**
- Source: `Riverbend_Brand_1-Pager.pdf` (New World Group). The sheet covers a family of
  Riverbend properties: Wappinger Falls, East and Florham Park. Florham Park's colourway is the
  coral sub-line.
- Palette in `:root`: charcoal `#232121`, coral `#EB6F63`, sage `#A8C1AA`, sky `#A0BEE2`,
  cream `#F5F1E9`. The scheme is the same light field with dark bands as Bellclair: a cream page,
  charcoal bands, and a pale-sage `.section--alt`.
- Coral fails as small text (2.7:1 on cream), so eyebrows and links use `--color-primary`
  `#A63F35` (5.5:1 cream, 5.0:1 sage band). Coral itself is only a fill with charcoal labels
  (5.3:1), a rule or an icon.
- Typefaces: Condor Light, Acme Gothic Extrawide and Area Normal, all commercial. Stand-ins are
  Jost, Syncopate and Manrope, with the `@font-face` block commented out (see
  `assets/fonts/README.txt`). Acme Gothic, as Syncopate, is a third token (`--font-accent`) for
  eyebrows and labels.
- The brand's wave pattern is a 64×12 CSS tile (`.wave-texture`) on the dark bands, and the
  wave line is the `.divider`.
- Logos are the guide's SVGs: the secondary wordmark in the header (charcoal / cream) and the
  stacked primary logo in the footer. Site icons are the coral R mark on cream, opaque.
- Riverbend-only CSS is in section 29. Bellclair's film band, film modal and gold deco rules are
  gone. A new `.shuttle-band` is registered in both dark-band lists in section 4.

**Photos (2026 shoot, SharePoint `07_Assets/River Bend (Florham Park, NJ)/Photos`).**
- Only the "Updated" folders are used, never "Old".
- **Residences:** every residence photo is from the four model units (Madison 1205, Livingston
  1401, Chatham 510, Florham 1802), all with the same finish: white cabinetry, white counters,
  stainless appliances and tile kitchen floors. The Virtual Staging sets are captioned
  "Virtually Staged".
- **Clubhouse:** the clubhouse photos are the 1200px MLS exports. The High Resolution files are
  online-only, and their paths exceed Windows' 260-character limit, so OneDrive refuses to
  download them ("The cloud operation is invalid"). Swap in the high-res versions if they are
  ever needed, for example by shortening the folder path.
- **Other rooms:** the clubhouse restrooms are not used.
- **October 2026 exterior shoot** (Nina, "New Riverbend Photos", 2026-10-30; SharePoint
  `SPGProperties` › "Oct 2026 Property Exterior", High Resolution and MLS sets of 15 photos). Nina
  sent it to replace **the drone photos and the photos with the old signage** (the blue
  "riverbend at florham park" sign and banners). Applied 2026-11-05 from the MLS exports
  (2700–2800px):
  - **All four drone aerials are removed** (`images/aerials/` is gone), as is the aerial social
    share image, now cut from photo 10. Don't bring aerials back without asking Nina.
  - **Old-signage shots replaced in place:** `main-entrance` (photo 10, now also the homepage
    hero), `entrance-sign` (16, gallery hero), `entry-drive` (7, availability hero),
    `monument-sign` (1), `clubhouse/clubhouse-exterior` (25) and `garden-benches` (28).
  - **New files:** `entrance-approach` (4), `entrance-corner` (13), `rental-office-sign` (19),
    `community-drive` (22, the neighborhood photo) and `clubhouse/ev-charger` (31, the EV
    Charging amenity photo).
  - **Not used:** the four lamp-post banner close-ups (34–37). `residences-garages` (summer
    2026) stays; its only sign is a small building-number plaque.
  - The pool gallery lost its aerial, so its six photos now alternate wide and single to fill
    the three-column grid.

**Neighborhood times are measured, not estimated.** They were routed with OSRM on 2026-10-20,
using foot routing under 25 minutes on foot and car routing beyond that.
- **Origin.** The routes start at the unnumbered building beside the pool (OpenStreetMap way
  1368465163, 40.75336, −74.36954), taken to be the clubhouse; "1901 Sterling Dr" is not in OSM,
  and the Census geocoder does not match it. Re-measure rather than guess if a place is added.
- **Location.** Riverbend is on North Passaic Avenue at the Chatham line, beside the Passaic
  River, not up on Columbia Turnpike. Chatham station is a **4-minute drive** and the only rail
  nearby; Florham Park has no station.
- **Drive times are free-flow**, with no traffic, and the section intro says so. Downtown Chatham
  is a 24.6-minute walk, shown as its 3-minute drive.
- **Corrected from the old site's location claims:**
  - "Three minutes from Chatham Train Station" is 4 minutes by car.
  - "Elm Street Recreational Complex" does not exist; the nearest match is the borough fields
    off Elm Street.
  - "Center Court Tennis Club" is Centercourt Athletic Club of Chatham, next door. It is listed
    mainly for pickleball, so the card says "racquet club".
  - "St. Barnabas" is now Cooperman Barnabas Medical Center.
- **Residents page numbers:**
  - Florham Park Police dispatch 973-377-2206 (Morris County OEM and NJ 211; the borough's own
    police page labels no non-emergency line).
  - Electric is JCP&L: outages 1-888-544-4877, from the FirstEnergy NJ bill insert of Nov 2025.
  - Gas is PSE&G: leaks 1-800-880-7734, with Florham Park listed in PSE&G's gas tariff.
- **Working files:** the full route table and rejected places are in the session scratchpad
  (not in the repo). The coordinates are in each list item's `data-lat` / `data-lng`.

### Open — Riverbend

**Copy to confirm with the client.** None of these is verified with Sterling:
- **55+ / age restriction.** The current site markets Riverbend as "NJ's newest 55+ Apartment
  Community" and "Adult Lifestyle Living". The brief, the portfolio page and the AppFolio listings
  never say so, and AppFolio lists a playground (the old site lists a tot lot). The preview makes
  **no age claim**, by John's decision on 2026-10-20, because an age restriction has fair-housing
  weight. Ask Nina whether it is still age-restricted. That answer also decides whether the
  playground belongs on the site, so it is left out for now.
- **Flooring is not described.** AppFolio and the portfolio page say "hardwood floors", and the
  old site says "hardwood and ceramic tile flooring with partial carpeting in select homes".
  Rivergate's flooring rule shows how much Sterling cares about this wording, so ask before
  saying anything.
- **Hours.** Confirmed by Nina (2026-10-22). Whether visits are by appointment is still unknown.
- **Shuttle.** The schedule is confirmed (2026-10-22) and is on the homepage as a timetable.
  "Complimentary" still comes only from the old homepage. The PDF's tables run to the bottom
  edge of its single page; if a second page has later runs, add them.
- **Pets.** The fees and the two-pet limit are confirmed (2026-10-22). Ask whether there are
  size, weight or breed limits, or buildings without pets, since the old sources said "small
  dogs" and "select buildings".
- **EV charging** is from AppFolio (the clubhouse photo shows a charging space). Do not add a
  count or fees.
- **Clubhouse.** "Renovated" comes from the SharePoint folder name ("Updated Renovated
  Clubhouse"). "Fireside lounge", "coffee bar" and "pergola" describe what the photos show.
- **Not used on the preview:** the old site's "200 private apartments" (unit count), its "1 Month
  Free" promotion, the 2016 brochure, and the rental-application PDF, which now returns 404 (the
  preview uses AppFolio).
- **Residents page.** The guideline blocks carry only sourced lines. Quiet hours, parking rules,
  packages and move-out terms are omitted until the client supplies them, unlike Bellclair, which
  carried Rivergate's text. Police and utility numbers were looked up (sources below).
- **Sister communities.** These are Nina's three (2026-10-22). Canterly Place (Livingston) and
  Lafayette Court (Morristown) link to their Sterling Portfolio pages. **Willow Court** is not
  in the portfolio (all 30 entries checked 2026-10-22), on AppFolio, on SharePoint or in a web
  search, so its card shows the name only, with no town and no link. Ask Nina for both. The
  portfolio returns 403 to a bare `curl` but 200 with a browser user agent.
- **Spelling.** The SharePoint folder and the AppFolio description spell it "River Bend"; the
  brand and the logo say "Riverbend".

**Waiting on Nina:** the text for the new site (in progress, per the brief); the web licences
for the three fonts; a Florham Matterport tour; any video (the SharePoint `Videos` folder is
empty); Willow Court's town and link. The October 2026 exterior shoot has arrived (above);
any further shoots (pool in season, interiors) would be new.

**Infrastructure:** add `riverbend-florhampark-preview.vercel.app` to the Maps key's allowed
referrers (Google Cloud Console). Until then, the preview shows the OpenStreetMap fallback.

**The domain today (checked 2026-11-13).** `riverbendatflorhampark.com` serves the old 2016
WordPress site through **Sucuri's proxy** (`@` and `www` → `192.124.249.30`), probably set up by
the old site's vendor. DNS is at GoDaddy (`ns05/ns06.domaincontrol.com`), so the cutover is two
records there (`@` A → `142.93.71.107`, `www` CNAME → `@`), which also takes Sucuri out of the
path; cancel that subscription afterwards. **Leave `mail.`, MX and the SPF TXT alone**: they
point at separate GoDaddy mail hosting (`68.178.221.10`).

**Waiting on before launch (2026-11-13):** Nina's copy changes (in progress), including the 55+
question; Rob's FluentSMTP connection for `riverbend@spgnj.com` (John emailed him).

**Next — launch (new-property skill, `references/go-live.md` §5–7):** the client's go-ahead;
Rob's FluentSMTP connection for `riverbend@spgnj.com`; then the cutover to
`riverbendatflorhampark.com` (Sterling's GoDaddy): DNS, Network Admin domain, the Let's Encrypt
reissue listing **every** property domain, the URL rewrite, `RIVERBEND_SITE_URL`, the Maps key
referrer, `blog_public` 1, a Search Console token, and `scripts/maintenance/sites.txt`.

---

## Where The Arden stands

Property #4 of 10: **The Arden at Sterling Pointe**, Franklin Township (Somerset County), NJ. The
working name was "Sterling Pointe". Brief: Nina Chichelo's emails "The Arden (Sterling Pointe) -
Next Website" (2026-11-11, to John and Chris) and her reply the same day: **a splash page first,
then the official website closer to the end of December.** She describes a splash page as "one
page of a website with general information and more of a COMING SOON vibe. 'Inquire here for
more information' etc.", and points to Blue Hill's (`bluehillnj.com`) as the example.

**Stage: coming-soon splash built (2026-11-13, branch `feature/arden-splash`)** in
`arden-franklin/preview/index.html`. It is a single page; there are no Residents, Availability or
Gallery pages yet. `theme/arden-franklin/` holds only `assets/` (`global.css`, logos, site icons,
the share image, the terrain texture and the fonts README). The Vercel project is
`arden-franklin-preview`, to be added by John in the dashboard; the client link will be
`https://arden-franklin-preview.vercel.app/` once the branch is merged.

**Naming (decided with John, 2026-11-13).**
- Slug: `arden-franklin`.
- For the fork: `arden_` / `ARDEN_`, CPTs `ar_amenity` / `ar_floor_plan`, ACF groups `group_ar_*`,
  blocks `arden/*`, buttons `is-style-arden-*` (already renamed in `global.css`), and
  `deploy-arden.yml` with `ARDEN_THEME_PATH` / `ARDEN_SITE_URL`.

**Facts and their sources.**
| Fact | Value | Source |
|---|---|---|
| Name | The Arden ("the arden" in the logo); "Arden at Sterling Pointe" on the guide's identity page and business-card mock-up | Brief; branding proposal |
| Town | Franklin Township, NJ (Somerset County) | Branding proposal cover ("Sterling Pointe, Franklin Township") |
| Domain | `TheArdenNJ.com` — registration and DNS control not yet confirmed | Branding proposal, page 2 |
| Brand story | "Arden evokes a sense of natural beauty and timeless sophistication… from the wooded trails of Six Mile Run to the gardens and open green spaces of Colonial Park"; vibe board: "Calm, Comfortable, & Contemporary" | Branding proposal, pages 2–3 |
| Palette | Espresso `#4B3D32`, Walnut `#675A49`, Sage Mountain `#999381`, Intrigue `#818981`, Stone `#CCC9C0`, plus white and black | Branding proposal, page 4 |
| Typefaces | Ansam (headlines, tagline), Mont (headlines, sub-heads, body), Josefin Sans (sub-heads, body). The font packet also shows Craw Modern, with no stated use | Branding proposal, pages 4–5 |
| Logos | PNGs only (3840×2160 canvases): "THE ARDEN" and "ARDEN" full logos and the "ar" mark, each in beige, black, dark brown, green and white | SharePoint `07_Assets/The Arden/Branding & Logos/LOGOS/PNG` |

**Brand and build.**
- `global.css` is Riverbend's, re-skinned by the same kind of scripted exact-match transform
  (Riverbend's charcoal/coral/cream → espresso/Intrigue/stone-white). It keeps the light field
  with dark bands; the splash hero is registered in both dark-band lists in section 4.
- **Contrast.** Intrigue and Sage Mountain fail as small text on the light field (3.2:1 and
  2.7:1), so eyebrows and links use `--color-primary #596159`, a deepened Intrigue (5.7:1).
  Muted text is Walnut (6.0:1). The brand tones are rules, icons and large fills only.
- **Buttons** read new `--btn-*` tokens: espresso with a stone-white label on the field, stone
  with an espresso label on dark bands (the tokens flip in the section 4 dark-band rule).
- **Fonts.** Josefin Sans is a Google Font, so the labels are already on-brand. Ansam and Mont
  are commercial: Cormorant and Montserrat stand in, with the `@font-face` block commented out
  (see `assets/fonts/README.txt`).
- **No photography or renderings exist yet**, so the hero is the logo in Stone on Espresso (the
  guide's colour logo panel) over a terrain-line texture (`assets/images/texture/contours.svg`,
  generated contour lines in Stone that nod to the parks named in the brand story). None of the
  vibe-board photos is used; they are mood imagery of unknown licence.
- Arden-only CSS is in section 29: 29a–29m are the brand layer (most carried from Riverbend),
  29n onward the splash components (`.splash-*`, `.terrain`). Riverbend's wave divider, wave
  texture and shuttle band are gone.
- `preview/site.js` is cut down to the header, smooth scrolling, scroll reveal and the form. The
  form shows a thank-you state and sends nothing; on WordPress it becomes a lead form (theme
  1.18's Leads). There is no inquiry pop-up: the whole page is the inquiry.
- Site icons are the espresso "ar" mark on Stone, opaque (32–512px); the 1200×630 share image is
  the hero lockup.

### Open — The Arden

**Copy to confirm with the client.** None of these is verified with Sterling:
- **55+ / age restriction.** A 2021 Franklin Reporter article on the Planning Board approval
  calls Sterling Pointe "a 55-and-over community", and the neighbouring Four Seasons at Sterling
  Pointe is age-restricted. The brief never says so, and the vibe board shows older couples.
  The splash makes **no age claim**, as at Riverbend, because an age restriction has
  fair-housing weight. Ask Nina.
- **What the building is.** The page says only "a new community from Sterling Properties" and
  "new homes". The same article describes two four-storey buildings of 30 apartments each; a
  traffic letter in the township's records says 60 apartments in two three-storey buildings.
  Neither figure, nor "luxury", nor any unit mix, amenity or finish is on the page.
- **The setting.** Six Mile Run and Colonial Park come from the branding proposal. The D&R Canal
  towpath and the park descriptions (Six Mile Run State Park is part of D&R Canal State Park;
  Colonial Park has the Rudolf W. van der Goot Rose Garden) are public facts added for the page.
  No distances are given; measure with OSRM once the address is known.
- **"The Arden Way of Living"** pillars (Calm / Comfortable / Contemporary) expand the brand's
  vibe-board line with general copy that makes no factual claim.
- **Form fields.** Name, email, phone, ideal move-in timing and questions. Ask Nina what she
  wants collected (Blue Hill's shows no form fields), and where leads should go.

**Waiting on Nina:** the street address; the leasing phone and `@spgnj.com` email (confirm the
spelling); whether a leasing office or sales centre will be open before launch; the leasing or
opening timeline; any renderings, floor plans or site photography; web licences for Ansam and
Mont; and whether `TheArdenNJ.com` is registered and who controls its DNS.

**Next:** merge, then John adds the `arden-franklin-preview` Vercel project (Root Directory
`arden-franklin/`, framework Other, no build command) and sends Nina the link. Revision rounds
use **edit-site-dev**. For the go-live of the splash itself, decide with John whether it goes
onto the multisite as a one-page Arden site (the splash as a page pattern) or stays static until
the December site.

---

## Changes made directly in the database

**These are not in the repo and no deploy will reproduce them.** Because patterns are
copy-on-insert, these had to be applied to existing page content by hand. If the site is ever
rebuilt from patterns, re-apply them.

| Post | Change | Backup |
|---|---|---|
| 9 (Home) | `rivergate/contact-form` given `recipientEmail: rivergate@spgnj.com` | `~/premigration/prelaunch-edits/post-9.*.bak` |
| 27 (Contact) | same | `~/premigration/prelaunch-edits/post-27.*.bak` |
| 28 (Residents) | `info@sterlingpropertiesnj.com` → `rivergate@spgnj.com` | `~/premigration/prelaunch-edits/post-28.*.bak` |
| all site 2 content | 2026-10-01 domain cutover — 51 `https://wordpress-1661870-6622382.cloudwaysapps.com/rivergate-bordentown` URLs rewritten to `https://rivergatenj.com` (`guid` left alone); default post 1 "Hello world!" and page 2 "Sample Page" trashed | `~/premigration/domain-cutover/site2-2026-10-01-1854.sql` |
| 9, 13, 25, 28 | 2026-09-30 photo refresh — images swapped to Media Library attachments 82–101; Gallery rebuilt with Residences + Neighborhood rows | `~/premigration/photo-refresh/` (per-post `.bak` + posts/postmeta SQL); also WP revisions 102–105 |
| site 3 (Bellclair) | 2026-10-12 — site created; pages 6–9 inserted from patterns (`blog_public` 0) | `~/premigration/bellclair-setup/network-before-bellclair-2026-10-13-0203.sql` |
| site 3 (Bellclair) | 2026-10-15 — domain cutover: 56 staging URLs on the live pages (114 including revisions) rewritten to `https://bellclair.com` (`guid` left alone) | `~/premigration/domain-cutover/site3-bellclair-2026-10-15-1815.sql` |
| 6–9 (Bellclair) | 2026-10-14 — repaired block-comment escapes that the setup script stripped (OPERATIONS trap 10: the Availability hero read "Pricing u0026 Online Applications"); office hours (6) and lease termination (9) swapped in | `~/premigration/bellclair-setup/site3-before-repair-2026-10-14-2243.sql` + per-page `post-<id>-*.html` |
| 6 (Bellclair Home) | 2026-10-15 — "By appointment only" added above the hours, and the six community cards replaced. The first attempt dropped the last old card's closing tag, so the page stopped rendering after the hours (contact form and cards missing) for a few minutes. It was restored from the backup and re-applied with a structure check (`restore-and-swap-home.php`: same top-level blocks, balanced block comments, nothing unparsed) | `~/premigration/bellclair-setup/post-6-home-20261015-215218.html` |
| site 4 (Riverbend) | 2026-11-10 — site created on the staging host (`/riverbend-florhampark/`, `blog_public` 0); theme enabled for blog 4 only; pages 4–7 inserted from patterns | `~/premigration/riverbend-setup/network-before-riverbend-2026-11-10-0254.sql` |
| network (user `rsadsts`, ID 5) | 2026-10-23 — Rob given FluentSMTP access: user meta `sterling_manage_email = 1`, set by John in Network Admin → Users. Applies on every site he belongs to; role unchanged (Editor). Undo: untick the box, or `wp user meta delete rsadsts sterling_manage_email` | none needed (one meta row) |
| network (site option) | 2026-10-24 — `sterling_lead_bcc = jjosephsen@bruddasindustries.com`: lead emails on every property BCC John while delivery is confirmed (read by Rivergate 1.18.4+ / Bellclair 1.1.4+ / Riverbend 1.0.0+). **Temporary.** Undo: `scripts/prod.sh wp site option delete sterling_lead_bcc --url=rivergatenj.com` | none needed (one option row) |

Also applied by hand on the server: `acf-json/group_rg_amenity.json` (4 fields), because the
deploy workflow excludes that directory. See the ACF section of `OPERATIONS.md`.

**Photo refresh (2026-09-30).** Nina's 9/14/26 Pailas Media set is live. Home CTA, the Amenities
and Residents heroes, the Amenities pool section and every Gallery photo now point at **Media
Library** attachments (IDs 82–101, uploaded at 2400px), so the theme files and patterns no longer
describe those slots — see "one silent behaviour" in `OPERATIONS.md`. The homepage amenity cards
are still theme files: `amenities/amenity-5.jpg` (pool, replaced in place) and
`amenities/dog-run.jpg`. Staged living-room shots deliberately stay in the Floor Plans hero and
homepage Residences section. Source exports: `D:\Downloads\Rivergate New Photos\web-2400\`
(filenames prefixed with Nina's frame number).

---

## Open — client-facing

**Conflicting drive times.** 13 of 30 neighborhood rows show a stale `~N min` estimate in the
description line directly above the measured drive time, so the card displays two different
numbers for the same trip. Worst: Princeton "~20 min" above "27 min drive"; Rider "~20 min" above
"24 min"; Capital Health "~20 min" above "30 min". The measured figures are the accurate ones —
strip the `~N min` fragments from the `$categories` array in
`blocks/neighborhood-explorer/render.php`.

**Four amenities have no photo.** Secured Access, Private Garage Spaces, Elevator Access and
Onsite Maintenance ship an empty image, so selecting them snaps the panel back to the
default clubhouse shot. None of the 66 photos in the 9/14 set shows any of them. Ask Nina for a
fob reader or controlled entry, the detached garages with doors in view, the elevator lobby, and
the maintenance team, shop or vehicle. Note that creating real `rg_amenity` entries is
all-or-nothing (trap 8 in `OPERATIONS.md`), so add them to the `_shared.php` fallback list
instead.

**Sister-property links unverified.** All six point at distinct
`sterlingpropertiesnj.com/sterling-portfolio/<slug>/` pages, but one looks wrong: the card
labelled "Eggert's Crossing" links to `/sterling-portfolio/the-crossings-at-ewing/`. Click all
six and confirm each lands on the right property.

**Awaiting the client:**
- Dog Run copy was written deliberately minimal and is unverified — confirm with Wendy/Nina
  whether it is fenced, its size, hours, seasonal limits
- Three resident documents still outstanding from Nina: Move-In/Move-Out Checklist, Community
  Rules, Renter's Insurance. Resident links currently route to AppFolio or the contact page.

---

## Open — infrastructure

**Domain cutover — done 2026-10-01.** DNS, Network Admin and Let's Encrypt were done by John.
After the switch, every image, PDF and in-page link baked into page content still pointed at the
staging host. The new certificate does not cover that host, so all of them broke. They were
rewritten in the database and Varnish was purged. Left over:
- **The network root (blog 1) and Network Admin still live on the staging domain**, which now
  shows a certificate error because the cert covers only `rivergatenj.com`. Accepted for now.
  Decide where the network's primary domain lives before property #2 gets its own domain.
- The Maps key has `rivergatenj.com` in its allowed referrers (John, 2026-10-01).
- `SITE_BASE_URL` now points at `https://rivergatenj.com` (updated 2026-10-01, before PRs #27/#28
  merged).

**Search (SEO) — theme side done in 1.17.0.** `inc/seo.php` sets per-page titles and meta
descriptions, Open Graph/Twitter tags (`assets/images/social/rivergate-share.jpg`, a 1200×630
crop of the hero aerial), and homepage JSON-LD (`WebSite` + `ApartmentComplex` with address,
phone, geo and amenities). It also drops `/contact/` and `/location/` from the sitemap (they
301), and closes `/author/*`, `?author=N`, the logged-out `/wp-json/wp/v2/users` route and the
oEmbed author fields, which all exposed the developer login.
- **Descriptions are client-editable**: a page's Excerpt (block editor sidebar) overrides the
  theme copy. Titles stay in code (`rivergate_seo_pages` filter).
- Everything except the sitemap/author lockdown steps aside if an SEO plugin is activated.
- **Search Console verification** is the `google-site-verification` meta tag in `inc/seo.php`
  (URL-prefix property `https://rivergatenj.com/`, HTML-tag method — the HTML-file method would
  need a file in the network-shared web root, which no deploy reaches). Do not remove it: Google
  re-checks it. **A property fork must drop or replace this token.**
- Still open, none of it ours to do: Search Console (needs a DNS TXT record — Sterling's DNS),
  Google Business Profile, the link from the Sterling portfolio page, listing-site URLs. Tracked
  with the user outside the repo.
- **Copy bug on the live Availability page**: "Compare all eight layouts" — there are nine.
  It is page content (DB), so fix it in wp-admin, and in `patterns/page-availability.php`.

**Mail — configured 2026-10-23 by Rob.** Not Google Workspace as planned: an SMTP relay
(`outbound.mailhop.org`), with two connections, **Rivergate Website <Rivergate@spgnj.com>**
(default) and **Bellclair Website <Bellclaire@spgnj.com>**. Rob could not tie the forms to a
connection because FluentSMTP routes by From address and the themes set none, so everything used
the default; Rivergate 1.18.4 / Bellclair 1.1.4 fix that (OPERATIONS → Mail). Rivergate's tour
form delivered on 2026-10-23 (FluentSMTP log, blog 2). **Next:** with the user's approval, one
tour-form and one pop-up submission per site, checking the From name, the BCC copy and "Emailed
to …" under Leads; then delete `sterling_lead_bcc`.

**SMTP owner (2026-10-23):** Rob at Sterling IT (`rsadsts`, an Editor on both sites) configures
FluentSMTP himself, so the app password never passes through us. **Access granted 2026-10-23**:
John ticked "Can manage email settings" on Rob in Network Admin → Users (OPERATIONS → Mail).
Verified on production: Rob has `sterling_manage_email` on rivergatenj.com and bellclair.com,
is still an Editor on both, and does not have `manage_options`. The settings are network-wide, so
he configures once, on either site, and every property sends with it. Done 2026-10-23 (above).

**Backups and updates are automated (branch `feature/maintenance-automation`, 2026-10-20)** —
nightly verified backups to SharePoint, and a weekly update run with smoke test and automatic
rollback; see OPERATIONS → "Backups & updates". **Not live until:**
- the Entra app, the SharePoint grant and the `M365_*`/`SP_DRIVE_ID` secrets exist (OPERATIONS
  one-time setup);
- the PR is merged (schedules only run from `main`);
- someone confirms Cloudways server backups are on in the panel.

A dry run on 2026-10-21 found core at 7.0.7 (WordPress's own minor auto-update had already
applied it). **WordPress 7.1.3 is a major release and is held** for manual approval. Updates
were waiting for ACF 6.8.4 → 6.8.10, FluentSMTP 2.3.1 → 2.4.1, Akismet, Breeze (inactive), and
the twentytwentythree and twentytwentyfour default themes. The first real run should be
watched. `wp-content/plugins/fluent-smtp/` is owned by the SSH user with mode 755/644. It was
installed over WP-CLI, so WordPress itself cannot update it from wp-admin. The update run's
permission pass (`g+w` on files the SSH user owns) fixes that on its first run.

**Cosmetic.** A 48-byte junk file with an unprintable glyph name sits at the root of
`rivergate-bordentown/` — residue of a mistyped shell redirect. Deliberately left for now.

---

## Decisions already made

Recorded so they are not relitigated.

- **Amenities run on the hardcoded fallback**, not the CPT. Deliberate until the client is ready
  to enter all ten in one pass.
- **Breeze and Object Cache Pro stay off** through launch. Varnish already handles page caching;
  Object Cache Pro is worth revisiting when more sites are live.
- **Contact and Location pages are folded into the homepage** and 301 to `/#contact` and
  `/#neighborhood`. The standalone pages still exist but are unlinked.
- **Floor plans are sorted smallest → largest by square footage** in code
  (`rivergate_sort_plans_by_sqft`), which overrides drag-to-reorder in wp-admin. Remove that call
  to hand ordering back to `menu_order`.
- **`admin_email` stays with the developer** through launch so WordPress system notices are not
  lost; hand it to the client afterward.
- **New properties fork from Bellclair** (2026-10-16), both the theme and the preview, not
  Rivergate. Bellclair carries the block-gap, communities-markup and map-fallback fixes, and its
  preview has no inline layout CSS. The `new-property` skill has the details.
- **Branching:** short-lived `feature/` / `fix/` / `docs/` branches cut from `main` and PR'd
  straight to `main`, deleted on merge (2026-10-16). There is no `dev` branch.
- **Procedures live in `.claude/skills/`** (`edit-site-dev`, `edit-site-prod`, `new-property`),
  with repeatable steps in `scripts/` (see OPERATIONS → "Helper scripts"). The skills hold no
  per-property facts; those stay in this file.

### Still undecided

**Should git own ACF field group definitions?** The deploy workflow currently excludes
`acf-json/` so production owns them. Defensible, but field groups are closer to code than
content. Decide before forking the next nine properties — see `OPERATIONS.md`.

---

## Client-verified copy constraints

Accuracy rules confirmed with the client. **Do not reintroduce these errors.**

- **The River Line is a neighborhood amenity, not a property amenity.** It may be described as
  nearby; it must never be listed among the community's own amenities.
- **Flooring is "hardwood-like."** Never "hardwood" and never "carpet."
- **Maintenance is "onsite," not "full-time."** The client had "Full-Time" removed (2026-10-02);
  do not describe the maintenance team as full-time anywhere.
- **Email convention is `@spgnj.com`** (cf. `Canterly@spgNJ.com`), not `@sterlingpropertiesnj.com`.
- **Never link `sterlingproperties.com`** — an unrelated firm in Washington/Oregon. The correct
  domain is `sterlingpropertiesnj.com`.
- **There is no privacy policy page.** `sterlingpropertiesnj.com/privacy-policy/` returns 404, so
  do not link it.
- Drive times shown in the neighborhood map are **real routing figures** measured from 500 Bluff
  View Circle, not estimates. Re-measure rather than guess if a location is added or moved.

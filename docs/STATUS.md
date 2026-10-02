# Project Status

**Last updated: 2026-10-02.** Update this file when something here changes — it is the first
thing to read when picking the project back up.

Environment and deploy details are in [`OPERATIONS.md`](./OPERATIONS.md); architecture and
conventions in [`CLAUDE.md`](../CLAUDE.md).

---

## Where Rivergate stands

Property #1 of 10. **Live at `https://rivergatenj.com`** since the 2026-10-01 domain cutover
(see "Domain cutover" below).

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

Bellclair (below) is property #2. The other eight have not been started. Everything in
`OPERATIONS.md` and the conventions in `CLAUDE.md` is written to be inherited by those forks.

---

## Where Bellclair stands

Property #2 of 10: **Bellclair at Montclair**, Montclair NJ. Brief: Nina Chichelo's email
"Next Website: Bellclair" (8/17/26, cc Andrew Zuckerman). Bellclair has never had its own site,
only a page on sterlingpropertiesnj.com.

**Static preview built** (2026-09-30, branch `feature/bellclair-preview`) in
`bellclair-montclair/preview/`: a one-page homepage plus Residents, Availability and Gallery. It
is named `preview/`, not `preview-1/`, at John's request. It is **not deployed yet.**

- `bellclair-montclair/theme/bellclair-montclair/` holds **assets only**: `global.css`, images
  and logos. These are what the preview links to. The WordPress fork of `rivergate-bordentown`
  (PHP, blocks, patterns, `theme.json`) has **not** been done. Fork it into this folder when the
  preview is approved; the CSS and assets are already in place for it.
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

**Sources for content and assets.** Nina's SharePoint folder could not be opened, so nothing
from it is in the repo. Everything came from:
- The email's 3 logo PNGs and the brand PDF. The cream/gold on-dark logo and the favicon were
  derived from the black/gold PNG, following the guide's "Color Usage" panel.
- 9 photos from the Sterling portfolio page (`sterlingpropertiesnj.com/sterling-portfolio/bellclair-at-montclair/`)
- Sterling's AppFolio listings (filter `property_list=BELLCLAIRE, LLC`): 13 interior and aerial
  photos, two branded floor-plan sheets (**Dorsey** 2bd/1ba 1,075 sf, **Ellington** 2bd/2ba
  1,125 sf), two Matterport tours (`hrsGWLg4gYT`, `ah1D28hoCbj`), the amenity and feature copy,
  and the pet policy

### Open — Bellclair

**Waiting on Nina:**
- **The professional video** (the main feature she asked for) is in the SharePoint "Video"
  folder. The hero and the "Watch the Film" lightbox are already wired. Drop the files in as
  `assets/video/bellclair-hero.mp4` (muted loop) and `bellclair-film.mp4` (full film). See
  `assets/video/README.txt`. Until then the hero shows the aerial and the lightbox shows a
  "Premiering Soon" card. The full film may be too large for git; consider a video host.
- The rest of the SharePoint photos. No one-bedroom floor plan exists yet; the selector shows a
  "coming soon" placeholder.
- **Licensed webfonts.** Micaroline and ITC Avant Garde Gothic Pro are commercial. The preview
  uses Poiret One and Questrial as stand-ins. To switch, drop in the files and uncomment the
  `@font-face` block at the end of `global.css` (see `assets/fonts/README.txt`).
- The leasing office hours. The contact section currently says "Call or email to schedule a
  visit."

**Copy to confirm with the client.** All of these came from AppFolio listing text or were
carried over from Rivergate, and none has been verified with Sterling:
- "Hardwood floors throughout" (AppFolio) was **deliberately left out**. The photos look like
  vinyl plank, and Rivergate's flooring rule shows Sterling cares about this wording. Ask
  before saying anything about floors.
- "Gas ranges" (AppFolio, townhome text) was **left out**. The kitchen photo shows an
  electric coil range.
- The townhome collection at 8 Bell St (private 2-car garages, loft/duplex units, 14 ft
  ceilings) is only described as "select residences". Confirm which units these are.
- Residents page: quiet hours, the 60-day move-out notice and the parking rules are
  Rivergate's text. "Maintenance Requests" points at the AppFolio portal; Rivergate uses
  Pilera, so ask which Bellclair uses. The after-hours line is assumed to be the office
  number.
- The email address is `bellclaire@spgnj.com`, spelled with an **e**. It matches the email,
  the website and the floor-plan sheets, and the owner LLC is "BELLCLAIRE, LLC", so it is
  probably right despite the brand spelling.
- The address: the email says "691 Bloomfield Avenue 7 8". The units are **7 and 8 Bell
  Street** and the leasing office is 691 Bloomfield Avenue. Both appear in the contact section.

**Infrastructure:**
- **Google Maps key is referrer-restricted.** It refused `localhost`. Add the Vercel preview
  domain (and later the production domain) to the key's allowed referrers. If Google refuses
  the domain, the preview falls back to an OpenStreetMap embed (`gm_authFailure` in `site.js`),
  so the client never sees an error box.
- **Vercel.** The project root must be `bellclair-montclair/`, because the preview links to
  `../theme/`. `bellclair-montclair/vercel.json` redirects `/` to `/preview/`. The connected
  Vercel account (`jjosephsen-bruddas' projects`) has no projects, so Rivergate's preview
  lives on a different account.
- The "More Communities" cards are the same six as Rivergate, including the unverified
  Eggert's Crossing link (see the Rivergate list below).

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

Next is SMTP, with Nina. The order was **domain first, then SMTP**, because OAuth binds to a
redirect URI containing the domain.

**Mail.** FluentSMTP is installed and network-activated but not configured, so **no form on the
site delivers anything right now**. Route chosen: Google Workspace via app password (not OAuth,
because domains are still moving). Sterling IT needs to supply a licensed mailbox — not an alias
or group — with 2-Step Verification on and a 16-character app password, and confirm Workspace
policy does not block app passwords.

**Pending updates.** WordPress 7.0.5 → 7.1.1 (major, take a Cloudways backup first). ACF and
FluentSMTP both have updates and are network-activated.

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

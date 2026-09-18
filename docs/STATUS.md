# Project Status

**Last updated: 2026-09-18.** Update this file when something here changes — it is the first
thing to read when picking the project back up.

Environment and deploy details are in [`OPERATIONS.md`](./OPERATIONS.md); architecture and
conventions in [`CLAUDE.md`](../CLAUDE.md).

---

## Where Rivergate stands

Property #1 of 10. Pre-launch, not yet on its own domain.

- Theme **1.16.3** on `main` and deployed to production
- All **16 client edits** requested by Andrew Zuckerman (8/26) and Nina Chichelo (8/18) are
  implemented and live — verified against the rendered page, not just the commits
- The homepage, amenities, floor plans, gallery, neighborhood and contact sections are all built
  as native block patterns + dynamic blocks

The nine remaining properties have not been started. Everything in `OPERATIONS.md` and the
conventions in `CLAUDE.md` is written to be inherited by those forks.

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

Also applied by hand on the server: `acf-json/group_rg_amenity.json` (4 fields), because the
deploy workflow excludes that directory. See the ACF section of `OPERATIONS.md`.

---

## Open — client-facing

**Conflicting drive times.** 13 of 30 neighborhood rows show a stale `~N min` estimate in the
description line directly above the measured drive time, so the card displays two different
numbers for the same trip. Worst: Princeton "~20 min" above "27 min drive"; Rider "~20 min" above
"24 min"; Capital Health "~20 min" above "30 min". The measured figures are the accurate ones —
strip the `~N min` fragments from the `$categories` array in
`blocks/neighborhood-explorer/render.php`.

**Five amenities have no photo.** Secured Access, Dog Run, Private Garage Spaces, Elevator Access
and Full-Time Onsite Maintenance ship an empty image, so selecting them snaps the panel back to
the default clubhouse shot. Clicking "Dog Run" and being shown the clubhouse reads as a bug.
Needs photos chosen — note that creating real `rg_amenity` entries is all-or-nothing (trap 8 in
`OPERATIONS.md`).

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

**Domain.** `rivergatenj.com` is registered in Sterling's GoDaddy account but not connected.
Agreed order: **domain first, then SMTP** — OAuth binds to a redirect URI containing the domain,
so doing it the other way round means re-authenticating.

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
- **Email convention is `@spgnj.com`** (cf. `Canterly@spgNJ.com`), not `@sterlingpropertiesnj.com`.
- **Never link `sterlingproperties.com`** — an unrelated firm in Washington/Oregon. The correct
  domain is `sterlingpropertiesnj.com`.
- **There is no privacy policy page.** `sterlingpropertiesnj.com/privacy-policy/` returns 404, so
  do not link it.
- Drive times shown in the neighborhood map are **real routing figures** measured from 500 Bluff
  View Circle, not estimates. Re-measure rather than guess if a location is added or moved.

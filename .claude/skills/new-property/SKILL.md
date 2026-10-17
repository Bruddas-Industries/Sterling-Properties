---
name: new-property
description: Start a new Sterling Properties property website and take it from brief to live — gather the brief and assets, scaffold the property folder, re-skin global.css from the brand guide, build the static client preview, fork the WordPress theme, wire its deploy workflow and production site, and run the domain cutover. Use this whenever the user mentions the next property, a new Sterling site or community, a new brief from Nina or Andrew, forking a theme, or moving an approved preview onto WordPress — properties 3 through 10 of the network. For changes to an existing preview use edit-site-dev; for changes to a live site use edit-site-prod.
---

# Starting a new property

Ten Sterling properties share one design system and one WordPress multisite. Each new site is a
fork that goes through the same five stages. Bellclair (property #2) went from Nina's brief on
8/17 to a preview on 9/30, then the WordPress fork and staging on 10/12, and its own domain on
10/15. Its commits and its STATUS section are the worked example for every step below.

| Stage | Where the instructions are |
|---|---|
| 1. Brief and assets | Below |
| 2. Scaffold and re-skin | Below |
| 3. Static preview and client review | Below; revision rounds use **edit-site-dev** |
| 4. WordPress fork (local) | `references/wordpress-fork.md` |
| 5. Production, staging and domain cutover | `references/go-live.md` |

Find out which stage the user is at before doing anything. "Start the next property" is
stage 1. "Bellclair's preview is approved, put it on WordPress" is stage 4.

## Naming

Decide these with the user at the start. They appear in hundreds of places, and renaming later
is painful.

| Thing | Convention | Rivergate / Bellclair |
|---|---|---|
| Folder and theme slug | `<name>-<town>`, lowercase | `rivergate-bordentown`, `bellclair-montclair` |
| PHP functions, constants | `<name>_`, `<NAME>_` | `rivergate_`, `BELLCLAIR_` |
| CPTs and ACF groups | a 2-letter prefix not used yet | `rg_amenity`, `bc_floor_plan`, `group_bc_*` |
| Block namespace, button styles | `<name>/*`, `is-style-<name>-*` | `bellclair/hero` |
| Deploy workflow, secrets | `deploy-<name>.yml`, `<NAME>_THEME_PATH`, `<NAME>_SITE_URL` | `BELLCLAIR_SITE_URL` |
| Preview folder | `preview/`, as at Bellclair. Rivergate's `preview-1/` is older | |

## Stage 1 — Brief and assets

Ask the user for anything missing from this list rather than inventing it. Wrong facts on these
sites are costly, because the client has to find them.

- **Identity:** the official name and spelling, street address(es), leasing-office address,
  phone, and email. The convention is `@spgnj.com`; confirm the exact spelling, since Bellclair's
  is `bellclaire@`, with an e.
- **Hours:** office hours, and whether visits are by appointment.
- **Units:** unit mix and the floor-plan sheets (plan names exactly as on the sheet, beds/baths,
  square feet), plus Matterport links.
- **Amenities:** the property's own amenities, as distinct from neighborhood ones. The River
  Line rule at Rivergate exists because those got mixed up.
- **Policies:** pet policy, and the resident portal (AppFolio or Pilera).
- **Brand:** the guide (palette, typefaces, logo variants), photos and video. The client's media
  lives in SharePoint `07_Assets/<Property>`, synced locally as described in edit-site-dev.
- **Site:** which pages it has. Bellclair is a one-page homepage plus Residents, Availability
  and Gallery; Rivergate is fully multi-page. Also the sister communities to feature, and the
  domain: whether it is registered and who controls its DNS.

Start a **"Where <Name> stands"** section in `docs/STATUS.md` right away. Record each fact with
its source, and keep a **"Copy to confirm"** list of anything taken from listings or carried over
from another property. Sterling-wide rules apply from day one:
- `@spgnj.com` addresses
- never link `sterlingproperties.com` (an unrelated firm)
- no privacy-policy link
- no claims the client hasn't confirmed

## Stage 2 — Scaffold and re-skin

1. **Choose the bases.**
   - **The preview:** copy Bellclair's `preview/`. Bellclair is the default base for both the
     preview and the theme (decided 2026-10-16). Its preview has no inline layout CSS and keeps
     its data in `site.js`. Rivergate's `preview-1/` has inline `<style>` blocks that cause
     trap 5.
   - **`global.css`:** start from Bellclair's, since its block-gap fix and section 29 layout
     carry through to the WordPress fork. Borrow Rivergate's surface rules if the new brand is
     blue-dominant.
     - Rivergate is a dark blue field with light panels; its light panels are registered in two
       white-surface lists (trap 6).
     - Bellclair is a light ivory field with black bands; its dark bands are registered in two
       lists in section 4.
     - The scheme decides which lists you maintain.
2. **Scaffold:** `<slug>/preview/` and `<slug>/theme/<slug>/assets/{css,images,fonts}/`. Point the
   preview pages at `../theme/<slug>/assets/…`.
3. **Re-skin** in `:root`: the eight colour tokens and two font tokens (CLAUDE.md →
   Design Tokens).
   - **Check contrast.** Brand metallics usually fail as small text. Bellclair uses a deepened
     bronze for eyebrows and links, and keeps gold for fills and rules only.
   - **Put property-only styles in section 29,** and rewrite the file's header comment.
   - **Licensed brand fonts.** If they aren't available yet, use free stand-ins and leave the
     `@font-face` block commented out, as Bellclair's `assets/fonts/README.txt` describes.
4. **Replace every piece of the old property's content:**
   - name, address, phone, email and hours
   - the map centre and the neighborhood places, measured with OSRM as described in
     edit-site-dev
   - floor plans, Matterport IDs and amenities
   - the sister-community cards (which must not list the property itself)
   - logos, favicon and photos

   Then check for leftovers:
   `grep -rniE 'rivergate|bordentown|bellclair|montclair|<old phone>' <slug>/`. Every hit
   should be deliberate.
5. **Image sizes:** site icons at 32, 180, 192, 270 and 512px (opaque PNG), and a 1200×630
   share image.
6. **Maps:** the key is referrer-restricted. Keep the OpenStreetMap fallback from Bellclair's
   `site.js` (`gm_authFailure`), so a refused domain never shows the client an error.
7. **Vercel config:** copy `bellclair-montclair/vercel.json` (it redirects `/` to `/preview/`
   and sends a noindex header) and `bellclair-montclair/.vercelignore` (it keeps theme PHP off the
   static host).

## Stage 3 — Preview and client review

1. **QA it:** `python scripts/site-qa.py <slug>/preview --shots <scratchpad>/qa`, then review the
   screenshots yourself.
2. **Put it on Vercel.** Previews live in the team **"jjosephsen-bruddas' projects"**
   (jjosephsen@bruddasindustries.com), one project per property named `<slug>-preview`.
   - In Vercel → Add New → Project, import `Bruddas-Industries/Sterling-Properties`. Set the
     Root Directory to `<slug>/` and the framework to Other, with no build command.
   - The Vercel connector can also create the project. Ask the user before creating anything.
   - The client link is `https://<slug>-preview.vercel.app/` once the branch is merged to `main`.
     Record it in STATUS.
   - Add `<slug>-preview.vercel.app` to the Maps key's allowed referrers. The user does this in
     Google Cloud Console.
3. **Run the revision rounds** with **edit-site-dev**.
4. **Get explicit approval before stage 4.** The WordPress patterns are built from the approved
   preview's markup. Every change after the fork has to be made in two places, and once the site
   is live, in three.

## Throughout — keep the docs current

When each stage lands, update:
- `docs/STATUS.md`: the property's section, and any rows for the "Changes made directly in the
  database" table
- `docs/OPERATIONS.md`: "The network" and "Local development" tables, and the Deploying section
- `README.md`: the sites table and repo tree
- CLAUDE.md's repo-structure tree

These skills deliberately contain no per-property facts, so they never need updating for a new
property.

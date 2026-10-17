---
name: edit-site-dev
description: Edit a Sterling Properties property's static client preview, the HTML pages under <property>/preview/ or preview-1/ that Vercel hosts for client review before a site is on WordPress. Covers copy changes, photo and video swaps, floor-plan data, amenities, neighborhood places, layout and CSS tweaks, client revision rounds, and pushing a new preview link for the client. Use this whenever the user asks to update, fix or revise a property's preview, mockup or Vercel link, or passes on client feedback for a site that isn't live yet, even if they just say "update the site" for a property that is still preview-only. If the property is already live on WordPress, use edit-site-prod; to start a brand-new property, use new-property.
---

# Editing a property preview

Before a property goes onto WordPress, the client reviews a static HTML preview hosted on Vercel.
That preview is the design the WordPress theme is later built from: its markup becomes the block
patterns. So it has to stay accurate, and it has to stay buildable. A preview edit that cannot
be reproduced in the theme is a problem stored up for later.

## Before you start

1. **Read the property's section of `docs/STATUS.md`.** Find the preview folder (Bellclair's is
   `preview/` and Rivergate's is `preview-1/`), the Vercel URL, copy waiting on the client, and
   the **client-verified copy constraints**.
2. **Work out the property's stage**, because it changes what an edit means:
   - **Preview only** (there is no `theme/<slug>/functions.php`): the preview is the source of
     truth. Edit freely.
   - **WordPress fork exists, but not live:** mirror every change in the theme's patterns and
     blocks as well, or the fork drifts from what the client approved.
   - **Live on WordPress:** the live site is the source of truth and the preview is history.
     Client edits go through **edit-site-prod**. Touch the preview only if the user wants it
     kept in sync.
3. **Know what is shared with the theme.** The preview loads
   `../theme/<slug>/assets/css/global.css` and its images from the theme folder. Those are the
   WordPress theme's own files. Once a property has a deploy workflow
   (`.github/workflows/deploy-<name>.yml`), merging any change under `theme/<slug>/` to `main`
   **deploys it to production**. A CSS tweak "for the preview" is then a production change: bump
   the version and follow edit-site-prod's verify steps.

## Where things live

| What | Where |
|---|---|
| Page copy and structure | `<slug>/preview/*.html` |
| Shared behaviour and data, e.g. Bellclair's `PLANS` | `preview/site.js`, `preview/inquiry-popup.js` |
| All styling | `theme/<slug>/assets/css/global.css`. Property-only styles are in section 29 |
| The preview badge only | `preview/preview.css` |
| Photos, logos, floor plans, video | `theme/<slug>/assets/images/<category>/`, `assets/video/` |
| Vercel config | `<slug>/vercel.json`. The project root is the property folder |

**Do not add layout CSS to a preview page.** Bellclair's preview has none. Rivergate's
`preview-1` pages still carry inline `<style>` blocks that duplicate `global.css`, and a plain
inline rule silently beats a `global.css` media query. That made the preview and the live theme
stack the floor-plan selector at different widths (trap 5). If you touch one of those
duplicates, remove it in favour of `global.css` rather than editing both.

## Making the change

**Copy**
- **Don't invent facts.** Only state counts, fees, materials, distances or features that have a
  source: the client's brief, the brand guide, the client's listings, or the user. If something
  is plausible but unsourced, leave it out and add it to the property's "Copy to confirm" list
  in STATUS.
- **History says this matters.** "Hardwood" vs "hardwood-like", "full-time" maintenance, gas
  vs electric ranges, and trains described as "steps away" when they are a 22-minute walk were
  all errors the client had to catch, or nearly did.
- **Check every change against the constraints** in STATUS: `@spgnj.com` addresses, never
  linking `sterlingproperties.com`, no privacy-policy link, and the property-specific rules.

**Neighborhood places**
- **Times are measured, not estimated.** Geocode the property (US Census geocoder) and the
  place, then route with OSRM: walking for anything under 25 minutes on foot, driving beyond
  that.
- **Coordinates** go in the list item's `data-lat` / `data-lng`.
- **Re-measure** when a place is added or moved.

**Photos and video**
- **Where client media is.** It's on SharePoint, synced locally to
  `C:\Users\heatw\Brudda's Industries\Client Operations - Documents\_CLIENTS\The Sterling Properties Group, LLC\07_Assets\<Property>`.
  If a folder is missing, start OneDrive (`%LOCALAPPDATA%\Microsoft\OneDrive\OneDrive.exe /background`).
- **Look at what a photo actually shows** before using it. At Bellclair the "NDR" folders are
  *un-renovated* units, and every early residence photo had to be replaced. Caption virtually
  staged shots as staged. Skip AI-edited images (fabricated skies, "(Edited)" files).
- **Size images for the web.** Match the dimensions of the images already in that folder (hero
  and gallery around 2400px on the long edge, cards smaller). Re-encode with Pillow, and keep
  each file to a few hundred KB.
- **Video.** There is no system ffmpeg; `pip install imageio-ffmpeg` provides one. Bellclair's
  `assets/video/README.txt` records the encode settings for a desktop and a phone hero.
- **Replacing an image in place** (same filename) changes it everywhere at once. If the property
  is live, that includes the live site after the next deploy. Never rename or delete an image a
  live page references (trap 9).

**CSS**
- **Use the system.** Use the tokens, `clamp()` type, `--space-*` spacing and the **900px**
  breakpoint.
- **Register new panels in the scheme lists.** Rivergate is blue-dominant, so a new light panel
  goes in both white-surface selector lists (trap 6). Bellclair is inverted, so a new dark band
  goes in both lists in section 4.
- **Keep the `.preview-banner` strip,** so the client knows it is a preview.

## Check it

Run `python scripts/site-qa.py <slug>/preview --shots <scratchpad>/qa`.

- It serves the property folder, so `../theme/` resolves the way it does on Vercel.
- It loads every page at 390, 900 and 1440px and reports console errors, missing files and
  sideways scroll.
- Then look at the screenshots of the section you changed, at phone and desktop width. A clean
  report does not mean it looks right.

If the Google Maps key refuses localhost, either Bellclair's OpenStreetMap fallback takes over,
or you can pass `--ignore "RefererNotAllowed|maps.googleapis"`.

## Ship it to the client

1. **Branch and commit.** Branch from `main`
   (`git switch -c feature/<property>-<change> origin/main`), commit, push and open a PR to
   `main`.
2. **How Vercel picks it up.** Each property has a project named `<slug>-preview` in the team
   "jjosephsen-bruddas' projects", Git-connected to this repo, with the property folder as its
   Root Directory and `main` as its production branch.
   - **Merging to `main` updates the client's link.** These are
     `https://rivergate-bordentown-preview.vercel.app/preview-1/` and
     `https://bellclair-montclair-preview.vercel.app/` (which redirects to `/preview/`).
   - **Every merge rebuilds every preview project,** whatever folder changed.
   - **Branch deployments are not client links.** A branch push gets its own deployment at
     `<project>-git-<branch>-jjosephsen-bruddas-projects.vercel.app`, behind Vercel's login
     (Deployment Protection). It's useful for the user to check before merging, but the client
     cannot open it.
   - **Check a deploy** with the Vercel connector: `list_deployments` / `get_deployment` for the
     project. It should be READY, with the commit you expect.
3. **Keep theme PHP off Vercel.** The Vercel root also contains the theme's PHP, which a static
   host serves as plain text. Each property folder's `.vercelignore` excludes it. If you add a
   new non-public folder (source files, working PSDs), add it to that file too.
4. **Bump the version if the theme is deployed.** If the property has a deploy workflow and you
   changed files under `theme/<slug>/`, run `scripts/bump-version.sh <property>` before merging.
5. **Update STATUS** with what changed in this round and any new items for "Copy to confirm".
6. **Offer a client changelog.** Offer the user a short list of the changes they can paste to
   the client (Nina / Andrew), in plain words with no file names.

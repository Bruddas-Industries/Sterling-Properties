RIVERGATE BRAND WEBFONTS — drop-in folder
==========================================

The 2026 style guide specifies:
  - Logam  → titles / headings / decorative
  - Noyh   → body / paragraph text

STATUS
  Logam  → INSTALLED as Logam.otf (headings now render in the brand face).
  Noyh   → still needed (body text falls back to Inter/Segoe/system for now).

The @font-face rules at the bottom of assets/css/global.css point at these
filenames. Add the Noyh files here to finish:

  Logam.otf              (present — titles/headings)
  Noyh-Light.woff2       (weight 300)
  Noyh-Regular.woff2     (weight 400, required)   Noyh-Regular.woff (optional)
  Noyh-Medium.woff2      (weight 500)
  Noyh-Bold.woff2        (weight 700)

Until Noyh is added, body text falls back to Inter / Segoe UI / system sans.
(For best performance you can later convert Logam.otf → Logam.woff2 at
transfonter.org and update the src url() in global.css.)

woff2 is preferred (smallest). If you only have .otf/.ttf, convert to .woff2
(e.g. https://transfonter.org) or update the src url()s in global.css to match.

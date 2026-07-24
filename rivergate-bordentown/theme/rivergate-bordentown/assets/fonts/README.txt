RIVERGATE BRAND WEBFONTS — drop-in folder
==========================================

The 2026 style guide specifies:
  - Logam  → titles / headings / decorative
  - Noyh   → body / paragraph text

STATUS — both brand fonts are now installed and in use:
  Logam.otf         → titles / headings
  Noyh-Light.otf    → weight 300  (Noyh Geometric Slim Light)
  Noyh-Regular.otf  → weight 400  (Noyh Geometric Slim Regular — base body face)
  Noyh-Medium.otf   → weight 500  (Noyh Geometric Slim Medium)
  Noyh-Bold.otf     → weight 700  (Noyh Geometric Slim Bold)

The @font-face rules at the bottom of assets/css/global.css point at these
filenames (format('opentype')). Swap in a matching file to change a weight.

PERFORMANCE (optional): .otf works everywhere but .woff2 is ~40% smaller. To
optimize later, convert each .otf → .woff2 (e.g. https://transfonter.org) and
update the src url()/format() in global.css accordingly.

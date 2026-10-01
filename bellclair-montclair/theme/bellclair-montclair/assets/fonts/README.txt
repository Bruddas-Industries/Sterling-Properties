BELLCLAIR BRAND WEBFONTS — drop-in folder
=========================================

The brand guide (Bellclair-BrandGuidelines_C01.pdf, New World Group) specifies:
  - Micaroline Regular              -> titles / headings (all-caps Art Deco face)
  - ITC Avant Garde Gothic Pro Medium -> body / paragraph text

STATUS: neither is installed. Both are commercial typefaces and need a WEB
licence (a desktop licence does not cover @font-face). Ask Nina / New World
Group for the licensed webfont files.

Until then the site uses the closest Google Fonts:
  Micaroline                 -> Poiret One   (Art Deco display)
  ITC Avant Garde Gothic Pro -> Questrial    (Avant Garde-style geometric)

TO ACTIVATE: save the files here as
  Micaroline-Regular.woff2              (+ .otf fallback optional)
  ITCAvantGardeGothicPro-Medium.woff2   (+ .otf fallback optional)
then uncomment the @font-face block at the end of assets/css/global.css.
The font stacks in :root already list the brand names first.

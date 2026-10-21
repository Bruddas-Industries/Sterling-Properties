RIVERBEND BRAND WEBFONTS — drop-in folder
=========================================

The brand identity sheet (Riverbend_Brand_1-Pager.pdf, New World Group) specifies:
  - Condor Light                 -> main typeface (headings)
  - Acme Gothic Extrawide        -> secondary typeface (the FLORHAM PARK sub-line;
                                    eyebrows and small labels on the site)
  - Area Normal Regular          -> body copy

STATUS: none is installed. All three are commercial typefaces and need a WEB
licence (a desktop licence does not cover @font-face). Ask Nina / New World
Group for the licensed webfont files.

Until then the site uses the closest Google Fonts:
  Condor Light           -> Jost Light   (thin geometric capitals)
  Acme Gothic Extrawide  -> Syncopate    (extended capitals)
  Area Normal            -> Manrope      (body)

TO ACTIVATE: save the files here as
  Condor-Light.woff2                  (+ .otf fallback optional)
  AcmeGothic-ExtrawideRegular.woff2   (+ .otf fallback optional)
  AreaNormal-Regular.woff2            (+ .otf fallback optional)
then uncomment the @font-face block at the end of assets/css/global.css.
The font stacks in :root already list the brand names first.

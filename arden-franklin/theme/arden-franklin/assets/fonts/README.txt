THE ARDEN BRAND WEBFONTS — drop-in folder
=========================================

The brand identity page of "Sterling Pointe Branding Proposal_UPDATED 11_4_2026.pdf"
specifies:
  - Ansam          -> primary typeface 1: main headlines, tagline
  - Mont           -> primary typeface 2: headlines, sub-headers, body copy
  - Josefin Sans   -> secondary typeface: sub-headers, body copy
(The "Font Packet" page also shows Craw Modern; the identity page does not assign it a use.)

STATUS: Ansam and Mont are commercial typefaces and need a WEB licence (a desktop
licence does not cover @font-face). Ask Nina for the licensed webfont files.
Josefin Sans is a Google Font (SIL Open Font Licence) and is already loaded by
each page, so the site's labels use the real brand face today.

Until the files arrive the site uses the closest Google Fonts:
  Ansam  -> Cormorant   (light, high-contrast display serif)
  Mont   -> Montserrat  (geometric sans)

TO ACTIVATE: save the files here as
  Ansam-Regular.woff2        (+ .otf fallback optional)
  Mont-Regular.woff2         (+ .otf fallback optional)
  Mont-SemiBold.woff2        (+ .otf fallback optional)
then uncomment the @font-face block at the end of assets/css/global.css.
The font stacks in :root already list the brand names first.

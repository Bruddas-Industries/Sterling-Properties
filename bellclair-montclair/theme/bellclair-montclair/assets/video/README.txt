BELLCLAIR VIDEO — drop-in folder
================================

Nina Chichelo (8/17/26): Bellclair "just had a professional, high-quality
video done" and Sterling wants it to be a main feature of the website. It is
in the "Video" folder of her SharePoint share (Bellclair (Montclair, NJ)),
which we could not open when the preview was built — so nothing is here yet.

The preview already expects two files. Add them with these exact names and
they play with no code changes:

  bellclair-hero.mp4   Homepage hero background. Muted, looping, autoplay.
                       Cut a 15–30 s loop from the film with no text or
                       talking heads. Target 1920x1080, H.264, under ~10 MB.
                       Until it exists the hero shows the aerial poster.

  bellclair-film.mp4   The full film, played with sound in the "Watch the
                       Film" lightbox (homepage film band, hero button,
                       amenities panel, gallery page). Until it exists the
                       lightbox shows a "Premiering Soon" card.

Compress before committing. Rivergate's hero loop is 8.7 MB. A full-length
film may be large enough that it belongs on a video host (Vimeo / YouTube /
Cloudways) rather than in git. If so, swap the <source> in the film modal for
an embed.

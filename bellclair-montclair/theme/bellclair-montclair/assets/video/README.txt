BELLCLAIR VIDEO
===============

Both files come from VTC's "VTC Videos" folder on the Bellclair SharePoint
share (07_Assets/Bellclair (Montclair, NJ)/Videos/VTC Videos), delivered 10/7/26.

  bellclair-hero.mp4   Homepage hero background. Muted, looping, autoplay.
                       Cut from "VTC x Sterling_Bellclair_Wide_Silent.mp4"
                       (1:36, silent track). Trimmed 2.8 s - 83.7 s: drops the
                       BELLCLAIR title card at the head and the navy contact /
                       Sterling end card at the tail so no text sits under the
                       hero headline. The silent cut has no interview shots of
                       Andrew Zuckerman; every Montclair and community shot is
                       kept. 1920x1080 H.264, CRF 32, no audio, ~13.7 MB.
                       Poster: images/film/hero-poster.jpg (its first shot).

  bellclair-hero-720.mp4  Same cut at 1280x720, CRF 30, ~8.7 MB. Served to
                       screens <= 900px wide via <source media>. Scores SSIM
                       0.965 against the source at 720p (the 1080p hero scores
                       0.959 at 1080p), so phones lose nothing visible.

  bellclair-film.mp4   The full film with sound, played in the "Watch the
                       Film" lightbox (hero button, homepage film band,
                       amenities panel, gallery page). Re-encoded from
                       "VTC x Sterling_Bellclair_v9_FINAL.mp4" (1:56, 203 MB)
                       to 1920x1080 H.264 CRF 25, preset veryslow, tune film
                       (max 3.5 Mbps) + AAC 128k, ~34.7 MB. Same SSIM (0.980)
                       as the earlier 37 MB CRF 24 / preset slow encode.
                       Poster: images/film/film-poster.jpg.

Not used: the "_v9_Subtitles" cut (burned-in captions) and the vertical cut.

Re-encode commands (ffmpeg):

  ffmpeg -ss 2.80 -to 83.70 -i "VTC x Sterling_Bellclair_Wide_Silent.mp4" -an \
    -c:v libx264 -preset slow -crf 32 -profile:v high -pix_fmt yuv420p \
    -movflags +faststart bellclair-hero.mp4

  ffmpeg -ss 2.80 -to 83.70 -i "VTC x Sterling_Bellclair_Wide_Silent.mp4" -an \
    -vf scale=1280:720:flags=lanczos -c:v libx264 -preset veryslow -tune film \
    -crf 30 -profile:v high -pix_fmt yuv420p -movflags +faststart \
    bellclair-hero-720.mp4

  ffmpeg -i "VTC x Sterling_Bellclair_v9_FINAL.mp4" -c:v libx264 -preset veryslow \
    -tune film -crf 25 -maxrate 3500k -bufsize 7000k -profile:v high \
    -pix_fmt yuv420p -c:a aac -b:a 128k -movflags +faststart bellclair-film.mp4

Tested 2026-10-08: preset veryslow saves nothing meaningful on the 1080p hero
(13.6 vs 13.7 MB at equal SSIM), so it stays on preset slow. H.264 is near its
floor at this quality; going smaller means AV1/HEVC copies plus H.264 fallbacks.

If the film outgrows git (GitHub warns above 50 MB, blocks above 100 MB), move
it to a video host and swap the <source> in the film modal for an embed.

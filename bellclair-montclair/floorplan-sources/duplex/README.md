# Duplex floor-plan sheet: sources

`theme/bellclair-montclair/assets/images/floorplans/duplex.jpg` is built from these files by
`build_sheet.py`. Run it from this folder with Pillow and numpy installed. It writes
`Duplex-draft-2000.jpg` (2000×1545, the SharePoint sheet size) and `duplex.jpg` (1920×1483, the
size the site uses).

| File | What it is |
|---|---|
| `image1.jpeg`, `image2.jpeg`, `image3.jpeg` | Nina's screenshots ("Bellclair Duplex layout", 2026-10-14) of the plan on a listing site: first floor, second floor and garage level. Each shows a mirrored pair, Unit A and Unit B. |
| `image*-panel.png` | The white drawing panel cropped out of each screenshot. These are the script's actual input. |
| `sp-duplex.jpg` | The client's blank Duplex sheet from SharePoint (`Updated Floorplans/JPG/Duplex.jpg`). |

What the script does:
1. Rotates each drawing upright and crops Unit A, with the front of the building at the bottom.
2. Scales the second floor by 1.11×, because that screenshot was captured smaller. The
   drawings' own dimensions confirm the factor.
3. Whites out the tiny original room labels and retypes them in Century Gothic Bold, to match
   the other sheets.
4. Adds gold level titles and an entry arrow.

Every coordinate is in the rotated panel's pixels.

**Open:** the drawing shows a powder room plus two full baths, which is 2.5 baths, but the
client's sheet text and the site say 2. Ask before changing either. A clean drawing from the
Regional Manager should replace this one if it arrives. Keep the filename `duplex.jpg` when
you replace it (OPERATIONS trap 9).

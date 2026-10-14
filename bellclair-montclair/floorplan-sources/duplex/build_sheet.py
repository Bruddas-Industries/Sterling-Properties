"""Compose the Bellclair Duplex floor-plan sheet from the listing-site screenshots.

Inputs (same folder): image1/2/3-panel.png (the white drawing panels cropped
from Nina's screenshots), sp-duplex.jpg (the blank 2000x1545 Duplex template
from SharePoint). All source coordinates below are in the panel rotated -90
degrees (labels upright), measured from gridded zooms.

Unit A is shown; Unit B is its mirror image. Front of the building at the bottom.
"""
from PIL import Image, ImageDraw, ImageFont
import numpy as np

FONT = 'C:/Windows/Fonts/GOTHICB.TTF'
GOLD = None  # sampled from the template title below
INK = (17, 17, 17)

S = 1.85                 # px on the sheet per source px (first floor + garage)
S2 = S * 1.11            # second-floor screenshot was captured 1.11x smaller


def load(name):
    return Image.open(name).convert('L').rotate(-90, expand=True, fillcolor=255)


def erase(im, boxes):
    d = ImageDraw.Draw(im)
    for b in boxes:
        d.rectangle(b, fill=255)
    return im


def clean(im, scale):
    """Upscale, then levels: paper to pure white, lines to black, keep antialiasing."""
    im = im.resize((round(im.width * scale), round(im.height * scale)), Image.LANCZOS)
    a = np.asarray(im).astype(np.float32)
    a = np.clip((a - 80) / (205 - 80), 0, 1) * 255
    return Image.fromarray(a.astype(np.uint8))


def font(px):
    return ImageFont.truetype(FONT, px)


def label(d, cx, cy, lines, fill=INK):
    """lines: [(text, size), ...] centred on (cx, cy)."""
    metrics = [(t, font(sz), sz) for t, sz in lines]
    gap = 4
    heights = [f.getbbox(t)[3] - f.getbbox(t)[1] for t, f, _ in metrics]
    total = sum(heights) + gap * (len(lines) - 1)
    y = cy - total / 2
    for (t, f, _), h in zip(metrics, heights):
        w = f.getlength(t)
        top = f.getbbox(t)[1]
        d.text((cx - w / 2, y - top), t, font=f, fill=fill)
        y += h + gap


# ---------------------------------------------------------------- template
sheet = Image.open('sp-duplex.jpg').convert('RGB')
_t = np.asarray(sheet.crop((55, 75, 380, 150))).reshape(-1, 3)   # the DUPLEX title
GOLD = tuple(int(v) for v in np.median(_t[_t.sum(axis=1) < 600], axis=0))
print('gold sampled', GOLD)

# ---------------------------------------------------------------- first floor (image1)
ff_crop = (68, 362, 372, 678)
ff = erase(load('image1-panel.png'), [
    (282, 374, 317, 399),   # UTIL. 7'8"x3'4" (incl. the trailing period)
    (343, 360, 372, 372),   # UNIT A/B dimension tick at the party wall
    (275, 436, 312, 460),   # KIT. 9'4"x10'8"
    (112, 465, 157, 478),   # PWDR.
    (116, 479, 150, 486),   # 6'0"x7'0"
    (196, 565, 253, 589),   # LIV./DIN. 23'4"x14'0"
    (217, 399, 230, 407),   # DN
    (169, 417, 181, 425),   # UP
]).crop(ff_crop)
ff_labels = [  # source coords (rotated panel), lines
    ((224, 577), [('LIVING / DINING', 30), ('23\'4" X 14\'0"', 26)]),
    ((295, 446), [('KITCHEN', 20), ('9\'4" X 10\'8"', 16)]),
    ((298, 387), [('UTIL.', 16)]),
    ((134, 476), [('PR', 22)]),
    ((223, 403), [('DN', 16)]),
    ((175, 421), [('UP', 16)]),
]

# ---------------------------------------------------------------- second floor (image2)
sf_crop = (60, 353, 326, 645)
sf = erase(load('image2-panel.png'), [
    (158, 373, 171, 380),   # DN
    (303, 353, 326, 364),   # UNIT A/B dimension tick at the party wall
    (218, 394, 267, 416),   # BDR. #2 12'4"x11'0"
    (95, 455, 146, 476),    # M. BATH 7'0"x9'0"
    (216, 468, 233, 480),   # B2
    (263, 476, 286, 486),   # W.I.C.
    (100, 530, 123, 541),   # W.I.C.
    (100, 584, 123, 595),   # W.I.C.
    (178, 540, 221, 573),   # MSTR. BDRM. 19'0"x12'0"
]).crop(sf_crop)
sf_labels = [
    ((200, 556), [('PRIMARY', 26), ('BEDROOM', 26), ('19\'0" X 12\'0"', 22)]),
    ((242, 405), [('BEDROOM 2', 24), ('12\'4" X 11\'0"', 20)]),
    ((120, 466), [('BATH', 20), ('7\'0" X 9\'0"', 14)]),
    ((224, 474), [('BATH', 18)]),
    ((274, 481), [('W.I.C.', 14)]),
    ((111, 535), [('W.I.C.', 14)]),
    ((111, 589), [('W.I.C.', 14)]),
    ((164, 377), [('DN', 16)]),
]

# ---------------------------------------------------------------- garage level (image3)
gl_crop = (70, 81, 242, 682)
gl = erase(load('image3-panel.png'), [
    (119, 120, 154, 133),   # STOR. A (left)
    (199, 117, 231, 130),   # STOR. A (centre)
    (199, 180, 231, 193),   # STOR. B (centre) — Unit B's closet, left unlabelled
    (122, 280, 154, 292),   # UNIT A
    (109, 328, 170, 353),   # GARAGE 11'8"x43'8"
]).crop(gl_crop)
gl_labels = [
    ((136, 127), [('STORAGE', 18)]),
    ((215, 124), [('STOR.', 14)]),
    ((139, 341), [('PRIVATE', 26), ('GARAGE', 26), ('11\'8" X 43\'8"', 20)]),
]

# ---------------------------------------------------------------- layout
GX, GY = 505, 305                       # garage level
FX, FY = 905, 232                       # first floor
# align the second floor's exterior walls with the first floor's
SX = FX + round((106 - ff_crop[0]) * S) - round((90 - sf_crop[0]) * S2)
SY = FY + round((ff_crop[3] - ff_crop[1]) * S) + 40

d = ImageDraw.Draw(sheet)
for img, crop, scale, ox, oy, labels in (
    (gl, gl_crop, S, GX, GY, gl_labels),
    (ff, ff_crop, S, FX, FY, ff_labels),
    (sf, sf_crop, S2, SX, SY, sf_labels),
):
    c = clean(img, scale)
    sheet.paste(Image.merge('RGB', (c, c, c)), (ox, oy), mask=Image.eval(c, lambda v: 255 - v))
    for (sx, sy), lines in labels:
        label(d, ox + (sx - crop[0]) * scale, oy + (sy - crop[1]) * scale, lines)
    print('placed', img.size, 'at', (ox, oy), 'size', c.size)

# Level titles, gold, as on the Fitzgerald sheet
gw = round((gl_crop[2] - gl_crop[0]) * S)
label(d, GX + gw / 2, GY - 42, [('GARAGE LEVEL', 34)], fill=GOLD)
fw = round((ff_crop[2] - ff_crop[0]) * S)
fh = round((ff_crop[3] - ff_crop[1]) * S)
sh = round((sf_crop[3] - sf_crop[1]) * S2)
label(d, FX + fw + 125, FY + fh * 0.55, [('FIRST', 36), ('FLOOR', 36)], fill=GOLD)
label(d, FX + fw + 125, SY + sh * 0.45, [('SECOND', 36), ('FLOOR', 36)], fill=GOLD)

# Entry arrow: up into the shared vestibule at the front of the garage level
ex = GX + (216 - gl_crop[0]) * S
ey = GY + (gl_crop[3] - gl_crop[1]) * S + 18
d.polygon([(ex, ey), (ex - 30, ey + 38), (ex - 14, ey + 38), (ex - 14, ey + 70),
           (ex + 14, ey + 70), (ex + 14, ey + 38), (ex + 30, ey + 38)], fill=GOLD)

sheet.save('Duplex-draft-2000.jpg', quality=92)
sheet.resize((1920, 1483), Image.LANCZOS).save('duplex.jpg', quality=88, optimize=True, progressive=True)
print('saved', sheet.size)

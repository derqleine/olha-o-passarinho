# Creates test_video.mp4: a grey 1280x720 video with a square in the top-left
# corner that cycles through the 15 colours used to pick a character.
# Lets you test the photo booth without a webcam:
#   python make_test_video.py
#   set OLHA_CAMERA=test_video.mp4      (Linux/macOS: export OLHA_CAMERA=test_video.mp4)
#   python olha_o_passarinho.py

import os

import cv2
import numpy as np

WIDTH, HEIGHT = 1280, 720
FPS = 30
SECONDS_PER_COLOUR = 2
SQUARE_SIZE = 150

# (name, hue) - the hue is in the middle of each range in olha_o_passarinho.py
COLOURS = [
    ("VERMELHO", 3),
    ("LARANJA ESCURO", 12),
    ("LARANJA CLARO", 19),
    ("AMARELO TORRADO", 23),
    ("AMARELO CLARO", 29),
    ("VERDE FLUORESCENTE", 37),
    ("VERDE", 59),
    ("AZUL ESVERDEADO", 80),
    ("AZUL CLARO", 89),
    ("AZUL", 100),
    ("AZUL ESCURO", 118),
    ("ROXO", 135),
    ("VIOLETA", 144),
    ("ROSA", 157),
    ("ROSA CHOQUE", 168),
]


def main():
    path = os.path.join(os.path.dirname(os.path.abspath(__file__)), "test_video.mp4")
    writer = cv2.VideoWriter(path, cv2.VideoWriter_fourcc(*"mp4v"), FPS, (WIDTH, HEIGHT))

    for i, (name, hue) in enumerate(COLOURS, start=1):
        frame = np.full((HEIGHT, WIDTH, 3), 90, np.uint8)

        hsv = np.uint8([[[hue, 255, 230]]])
        bgr = cv2.cvtColor(hsv, cv2.COLOR_HSV2BGR)[0][0].tolist()
        cv2.rectangle(frame, (0, 0), (SQUARE_SIZE, SQUARE_SIZE), bgr, -1)

        label = f"{i}/{len(COLOURS)} - cor esperada: {name}"
        cv2.putText(frame, label, (40, HEIGHT - 40), cv2.FONT_HERSHEY_SIMPLEX, 1.2, (255, 255, 255), 2)

        for _ in range(FPS * SECONDS_PER_COLOUR):
            writer.write(frame)

    writer.release()
    print(f"Vídeo criado: {path}")


if __name__ == "__main__":
    main()

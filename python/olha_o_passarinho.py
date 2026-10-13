# Dependencies: pip install -r requirements.txt

import cv2
import time as time_
import numpy as np
import requests
import os
os.environ['OPENCV_IO_ENABLE_WARNINGS'] = "FALSE"

# URL of the web/ folder on the server. Defaults to the local server
# (docker compose up). For another server, set the OLHA_SERVER_URL
# environment variable, e.g. https://example.com/olha-o-passarinho
SERVER_URL = os.environ.get("OLHA_SERVER_URL", "http://localhost:8000").rstrip("/")

# Always work from the script's folder so molduras/ is found
os.chdir(os.path.dirname(os.path.abspath(__file__)))
for pasta in ("fotosDefault", "fotosMoldura", "fotosMolduraCharacter"):
    os.makedirs(pasta, exist_ok=True)


cap = None


def sendToDB(categoriaEscolhida, fotoDefault, fotoMoldura, fotoMolduraCharacter="", observacoes="..."):
    url = SERVER_URL + '/upload_bd.php'
    data = {
        'categoria': categoriaEscolhida,
        'fotoDefault': fotoDefault,
        'fotoMoldura': fotoMoldura,
        'fotoMolduraCharacter': fotoMolduraCharacter,

    }
    response = requests.post(url, data=data)
    if (response.text == "Caminho registado na base de dados"):
        print("Caminho registado na base de dados!")
    else:
        print("Erro, caminho não guardado na BD!")
    print("Já podes tirar outra foto!")
    return True


def sendToServer(fotoEnviar, pastaEnviar, tipo):

    url = SERVER_URL + '/upload.php'
    files = {'image': open(fotoEnviar, 'rb')}
    data = {'folder': pastaEnviar}
    response = requests.post(url, files=files, data=data)

    if (response.text == "Imagem movida para o servidor com sucesso!"):
        print(f"Foto enviada para o servidor com sucesso! [{tipo}]")
    else:
        print(
            f"Erro, foto não foi enviada para o servidor com sucesso! [{tipo}]")


def overlapMoldura(frameCamara):

    moldura = 'molduras/frame.png'

    img1 = frameCamara
    img2 = cv2.imread(moldura, cv2.IMREAD_UNCHANGED)

    img1 = cv2.resize(img1, (1280, 720))
    img2 = cv2.resize(img2, (1280, 720))

    # Overlay as imagens
    result = img1.copy()
    rows, cols, channels = img2.shape
    roi = result[0:rows, 0:cols]
    alpha = img2[:, :, 3] / 255.0
    alpha_inv = 1.0 - alpha
    for c in range(0, 3):
        roi[:, :, c] = alpha * img2[:, :, c] + alpha_inv * roi[:, :, c]

    return result


def overlapCharacter(frameCamara, overlap):

    moldura = overlap

    img1 = frameCamara
    img2 = cv2.imread(moldura, cv2.IMREAD_UNCHANGED)

    img1 = cv2.resize(img1, (1280, 720))
    img2 = cv2.resize(img2, (1280, 720))

    # Overlay as imagens
    result = img1.copy()
    rows, cols, channels = img2.shape
    roi = result[0:rows, 0:cols]
    alpha = img2[:, :, 3] / 255.0
    alpha_inv = 1.0 - alpha
    for c in range(0, 3):
        roi[:, :, c] = alpha * img2[:, :, c] + alpha_inv * roi[:, :, c]

    return result


def categoria():
    print("Categorias disponíveis:")
    categoriasExistentes = [

        "10T2", "E1", "E2", "G1", "H1", "H2", "H3", "S1", "S2", "S3", "S4", "S5", "T1", "T2", "T3",
        "11AD1", "11AD2", "11AG", "11AJ", "11BT1", "11BT2", "11CIG1", "11CIG2", "11EA", "11ET", "11IF", "11LR", "11ME", "11PT", "11QA",
        "12AD1", "12AD2", "12AG", "12AJ", "12BT1", "12BT2", "12CIG1", "12CIG2", "12EA", "12ET", "12IF", "12LR", "12ME", "12PT", "12QA",
        "Visitantes", "Convidados", "Professores", "Colaboradores"
    ]
    for categoria in categoriasExistentes:
        print("\t- " + categoria)

    categoriaEscolhida = input("\nEscolhe uma das categorias disponíveis:\n")

    while (categoriaEscolhida not in categoriasExistentes):
        categoriaEscolhida = input(
            "\nEscolhe uma das categorias disponíveis a anterior não existe!\n")
    return categoriaEscolhida


def main():

    perguntar = True

    while True:

        if (perguntar):
            perguntar = False

            criar = input("Queres criar foto com alguém? [S/N]\n")
            if (criar == "S"):

                print("Ok, personagem será escolhido por cor!")
                haveCharacter = True
            else:
                print("Ok, foto não terá personagem!")
                haveCharacter = False

        ret, frame = cap.read()
        hsv_frame = cv2.cvtColor(frame, cv2.COLOR_BGR2HSV)

        height, width, _ = frame.shape

        frameCamaraAtual = frame

        key = cv2.waitKey(1)

        if not ret:
            print("Erro a conseguir o frame")
            break

        if key % 256 == 27:
            # ESC pressed
            print("Closing...")
            break

        elif key % 256 == 32:
            # SPACE pressed
            categoriaEscolhida = categoria() + "/"

            uniqID = str(int(round(time_.time() * 1000)))

            frame = cv2.resize(frame, (1280, 720))
            cv2.imwrite("fotosDefault/imagemDefault_" + uniqID + ".jpg", frame)

            print("Foto tirada com sucesso! Default")

            imagemMoldura = overlapMoldura(frame)
            cv2.imwrite("fotosMoldura/imagemMoldura_" +
                        uniqID + ".jpg", imagemMoldura)

            print("Foto tirada com sucesso! Moldura")

            sendToServer("fotosDefault/imagemDefault_" +
                         uniqID + ".jpg", categoriaEscolhida, 1)
            sendToServer("fotosMoldura/imagemMoldura_" +
                         uniqID + ".jpg", categoriaEscolhida, 2)

            if (haveCharacter):
                imagemCharacter = overlapCharacter(frame, overlap)
                imagemCharacterMoldura = overlapMoldura(imagemCharacter)
                cv2.imwrite("fotosMolduraCharacter/imagemCharacter_" +
                            uniqID + ".jpg", imagemCharacterMoldura)

                print("Foto tirada com sucesso! Moldura e Character")
                sendToServer("fotosMolduraCharacter/imagemCharacter_" +
                             uniqID + ".jpg", categoriaEscolhida, 3)
                perguntar = sendToDB(categoriaEscolhida, "imagemDefault_" + uniqID + ".jpg",
                                     "imagemMoldura_" + uniqID + ".jpg", "imagemCharacter_" + uniqID + ".jpg")
            else:
                perguntar = sendToDB(categoriaEscolhida, "imagemDefault_" +
                                     uniqID + ".jpg", "imagemMoldura_" + uniqID + ".jpg")

        cx = int(10)
        cy = int(10)

        cRx = int(width / 2)
        cRy = int(height / 2)

        pixel_center = hsv_frame[cy, cx]
        hue_value = pixel_center[0]

        color = "Undefined"

        if hue_value < 7:
            color = "VERMELHO"
            overlap = "molduras/1.png"
            if (haveCharacter):
                frameCamaraAtual = overlapCharacter(frame, overlap)

        elif hue_value < 17:
            color = "LARANJA ESCURO"
            overlap = "molduras/2.png"
            if (haveCharacter):
                frameCamaraAtual = overlapCharacter(frame, overlap)

        elif hue_value < 22:
            color = "LARANJA CLARO"
            overlap = "molduras/3.png"
            if (haveCharacter):
                frameCamaraAtual = overlapCharacter(frame, overlap)

        elif hue_value < 25:
            color = "AMARELO TORRADO"
            overlap = "molduras/4.png"
            if (haveCharacter):
                frameCamaraAtual = overlapCharacter(frame, overlap)

        elif hue_value < 33:
            color = "AMARELO CLARO"
            overlap = "molduras/5.png"
            if (haveCharacter):
                frameCamaraAtual = overlapCharacter(frame, overlap)

        elif hue_value < 42:
            
            color = "VERDE FLUORESCENTE"
            overlap = "molduras/6.png"
            if (haveCharacter):
                frameCamaraAtual = overlapCharacter(frame, overlap)
        
        elif hue_value < 77:
            color = "VERDE"
            overlap = "molduras/7.png"
            if (haveCharacter):
                frameCamaraAtual = overlapCharacter(frame, overlap)

        elif hue_value < 84:
            color = "AZUL ESVERDEADO"
            overlap = "molduras/8.png"
            if (haveCharacter):
                frameCamaraAtual = overlapCharacter(frame, overlap)

        elif hue_value < 94:
            color = "AZUL CLARO"
            overlap = "molduras/9.png"
            if (haveCharacter):
                frameCamaraAtual = overlapCharacter(frame, overlap)

        elif hue_value < 107:
            color = "AZUL"
            overlap = "molduras/10.png"
            if (haveCharacter):
                frameCamaraAtual = overlapCharacter(frame, overlap)

        elif hue_value < 130:
            color = "AZUL ESCURO"
            overlap = "molduras/11.png"
            if (haveCharacter):
                frameCamaraAtual = overlapCharacter(frame, overlap)

        elif hue_value < 140:
            color = "ROXO"
            overlap = "molduras/12.png"
            if (haveCharacter):
                frameCamaraAtual = overlapCharacter(frame, overlap)

        elif hue_value < 148:
            color = "VIOLETA"
            overlap = "molduras/13.png"
            if (haveCharacter):
                frameCamaraAtual = overlapCharacter(frame, overlap)

        elif hue_value < 166:
            color = "ROSA"
            overlap = "molduras/14.png"
            if (haveCharacter):
                frameCamaraAtual = overlapCharacter(frame, overlap)

        elif hue_value < 170:
            color = "ROSA CHOQUE"
            overlap = "molduras/15.png"
            if (haveCharacter):
                frameCamaraAtual = overlapCharacter(frame, overlap)

        else:
            color = "VERMELHO"
            overlap = "molduras/1.png"
            if (haveCharacter):
                frameCamaraAtual = overlapCharacter(frame, overlap)
        print(hue_value)
        pixel_center_bgr = frameCamaraAtual[cy, cx]
        b, g, r = int(pixel_center_bgr[0]), int(
            pixel_center_bgr[1]), int(pixel_center_bgr[2])

        # cv2.rectangle(frameCamaraAtual, (cx - 220, 10), (cx, 120), (255, 255, 255), -1)
        cv2.putText(frameCamaraAtual, color,
                    (cRx - 200, 100), 0, 3, (b, g, r), 5)
        cv2.circle(frameCamaraAtual, (cx, cy), 5, (0, 0, 255), 3)

        cv2.imshow(
            "Fotos 12IF ExpoCIC Afonso Saraiva e Gabriel Sousa", frameCamaraAtual)


if __name__ == "__main__":
    print("A abrir projeto...")
    print("Servidor: " + SERVER_URL)
    cap = cv2.VideoCapture(0)
    cap.set(cv2.CAP_PROP_FRAME_WIDTH, 1280)
    cap.set(cv2.CAP_PROP_FRAME_HEIGHT, 720)

    main()

    cap.release()
    cv2.destroyAllWindows()

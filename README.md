# Olha o passarinho! 📸

Cabine fotográfica (*photobooth*) feita para a banca de informática da **ExpoCIC 2023**, a semana cultural do Colégio Interno dos Carvalhos, por **Afonso Saraiva** e **Gabriel Sousa** (12IF).

O visitante coloca-se em frente à webcam e tira uma foto com a moldura da ExpoCIC. Se quiser, pode aparecer ao lado de uma personagem famosa, escolhida mostrando à câmara um objeto de uma determinada **cor** no canto superior esquerdo da imagem. As fotos são enviadas para um servidor e ficam disponíveis num site, organizadas por turma ou categoria.

> 🚧 Projeto original de março de 2023, recuperado para voltar a funcionar em localhost ou num servidor.

## Como funciona

```
┌──────────────────────┐   upload.php    ┌──────────────────┐
│  Script Python       │ ──────────────► │  Servidor PHP    │
│  (webcam + OpenCV)   │   upload_bd.php │  + MySQL         │
└──────────────────────┘ ──────────────► └────────┬─────────┘
                                                  │ index.php
                                                  ▼
                                         Galeria de fotos (site)
```

1. **`python/olha_o_passarinho.py`**: abre a webcam e lê a cor (HSV) do pixel do canto superior esquerdo para escolher uma de 15 personagens. Ao carregar em **Espaço**, pede a categoria (turma, visitantes, professores…) e gera até 3 versões da foto: original, com moldura e com moldura e personagem. **Esc** fecha o programa.
2. **`web/upload.php`** recebe as imagens e **`web/upload_bd.php`** regista-as na base de dados.
3. **`web/index.php`** mostra a galeria, com filtro por categoria e botão de download.

## Como correr (localhost)

Requisitos: [Docker Desktop](https://www.docker.com/products/docker-desktop/), Python 3 e uma webcam.

**1. Galeria web e base de dados.** Na pasta do projeto:

```bash
docker compose up -d
```

| Serviço | Endereço |
|---|---|
| Galeria | http://localhost:8000 |
| Adminer (gerir a base de dados) | http://localhost:8080 (servidor `db`, utilizador `olha`, password `olha`) |

Na primeira vez, a base de dados é criada automaticamente a partir de `database/polaroidexpocic.sql`. Para desligar: `docker compose down`. Para apagar também os dados: `docker compose down -v`. Para mudar as passwords, copiar `.env.example` para `.env`.

**2. Script da cabine.** Noutro terminal:

```bash
cd python
python -m venv .venv
.venv\Scripts\activate        # Windows (Linux/macOS: source .venv/bin/activate)
pip install -r requirements.txt
python olha_o_passarinho.py
```

Mostrar uma cor ao canto superior esquerdo da câmara para escolher a personagem, **Espaço** para tirar a foto, **Esc** para sair.

<details>
<summary>Sem Docker (XAMPP ou PHP + MySQL instalados)</summary>

Importar `database/polaroidexpocic.sql` no MySQL/MariaDB (utilizador `root` sem password, por defeito) e arrancar o servidor web com `php -S localhost:8000 -t web`. É necessária a extensão `mysqli`.

</details>

### Usar num servidor

- **Web:** copiar a pasta `web/` para o alojamento, importar `database/polaroidexpocic.sql` e criar `web/config.php` a partir de `web/config.example.php` com os dados da base de dados. O `config.php` não vai para o git.
- **Python:** indicar o endereço do servidor na variável de ambiente `OLHA_SERVER_URL`:

  ```bash
  set OLHA_SERVER_URL=https://exemplo.pt/olha-o-passarinho
  python olha_o_passarinho.py
  ```

## Estrutura

| Pasta | Conteúdo |
|---|---|
| `python/` | Script da cabine, calibrador de cores HSV (`hsv_color_picker.py`) e imagens das molduras e personagens |
| `web/` | Site da galeria e endpoints de upload (PHP) |
| `database/` | Esquema da base de dados (MariaDB/MySQL) |
| `docker/`, `compose.yaml` | Ambiente Docker: PHP + Apache, MariaDB e Adminer |
| `design/` | Material de trabalho: tabela de cores, personagens originais e recortadas, projetos Photoshop da moldura |
| `docs/` | Descrição original do projeto entregue para a ExpoCIC |

## Tecnologias

Python · OpenCV · PHP · MariaDB/MySQL · HTML/CSS (W3.CSS + Bootstrap) · Docker

## Autores

- Afonso Saraiva
- Gabriel Sousa

As imagens das personagens pertencem aos respetivos autores e foram usadas num contexto escolar, sem fins lucrativos.

# Olha o passarinho! 📸

Cabine fotográfica (*photobooth*) feita para a banca de informática da **ExpoCIC 2023**, a semana cultural do Colégio Interno dos Carvalhos, por **Afonso Saraiva** e **Gabriel Sousa** (12IF).

O visitante coloca-se em frente à webcam e tira uma foto com a moldura da ExpoCIC. Se quiser, pode aparecer ao lado de uma personagem famosa, escolhida mostrando à câmara um objeto de uma determinada **cor** no canto superior esquerdo da imagem. As fotos são enviadas para um servidor e ficam disponíveis num site, organizadas por turma ou categoria.

> 🚧 Projeto original de março de 2023, agora a ser recuperado para voltar a funcionar (em localhost ou num servidor).

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

## Estrutura

| Pasta | Conteúdo |
|---|---|
| `python/` | Script da cabine, calibrador de cores HSV (`hsv_color_picker.py`) e imagens das molduras e personagens |
| `web/` | Site da galeria e endpoints de upload (PHP) |
| `database/` | Esquema da base de dados MySQL |
| `design/` | Material de trabalho: tabela de cores, personagens originais e recortadas, projetos Photoshop da moldura |
| `docs/` | Descrição original do projeto entregue para a ExpoCIC |

## Tecnologias

Python · OpenCV · PHP · MySQL · HTML/CSS (W3.CSS + Bootstrap)

## Autores

- Afonso Saraiva
- Gabriel Sousa

As imagens das personagens pertencem aos respetivos autores e foram usadas num contexto escolar, sem fins lucrativos.

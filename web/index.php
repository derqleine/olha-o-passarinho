<?php
require __DIR__ . '/db.php';
$db = getDb();

// Categories are stored with a trailing "/" (e.g. "12IF/")
$categorias = $db->query("SELECT DISTINCT categoria FROM fotografias ORDER BY categoria")->fetchAll(PDO::FETCH_COLUMN);
$categorias = array_map(fn($categoria) => str_replace('/', '', $categoria), $categorias);

// "Coletivo" was the old name for "all photos"
$categoriaAtual = $_GET['categoria'] ?? '';
if ($categoriaAtual === 'Coletivo') {
  $categoriaAtual = '';
}

if ($categoriaAtual !== '') {
  $stmt = $db->prepare("SELECT categoria, fotoMoldura, fotoMolduraCharacter FROM fotografias WHERE categoria = ? ORDER BY id DESC");
  $stmt->execute([$categoriaAtual . "/"]);
} else {
  $stmt = $db->query("SELECT categoria, fotoMoldura, fotoMolduraCharacter FROM fotografias ORDER BY id DESC");
}

$fotos = array();
foreach ($stmt as $row) {
  // Show the photo with the character if there is one, otherwise the framed photo
  $foto = !empty($row['fotoMolduraCharacter']) ? $row['fotoMolduraCharacter'] : $row['fotoMoldura'];
  $fotos[] = UPLOADS_DIR . $row['categoria'] . $foto;
}

function e($texto)
{
  return htmlspecialchars($texto);
}

$chip = 'shrink-0 rounded-full px-4 py-1.5 text-sm font-medium transition-colors';
$chipAtivo = $chip . ' bg-brand text-white shadow-sm';
$chipInativo = $chip . ' bg-zinc-100 text-zinc-700 hover:bg-zinc-200';
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Olha o passarinho! · ExpoCIC 2023</title>
  <link rel="icon" type="image/png" href="assets/logo_ExpoCIC.png">
  <link rel="stylesheet" href="assets/css/style.css">
  <script src="assets/js/gallery.js" defer></script>
</head>
<body class="flex min-h-screen flex-col bg-zinc-50 text-zinc-900 antialiased">

  <header class="bg-white">
    <div class="mx-auto flex max-w-7xl items-center gap-4 px-4 py-5">
      <img src="assets/logo_ExpoCIC.png" alt="ExpoCIC 2023" class="h-12 w-auto sm:h-14">
      <div>
        <h1 class="text-2xl font-bold tracking-tight sm:text-3xl">Olha o passarinho!</h1>
        <p class="text-sm text-zinc-500">ExpoCIC 2023 · Banca de Informática</p>
      </div>
    </div>
    <div class="h-1 bg-linear-to-r from-brand to-accent"></div>
  </header>

  <!-- Categories -->
  <nav aria-label="Categorias" class="sticky top-0 z-10 border-b border-zinc-200 bg-white/90 backdrop-blur">
    <div class="mx-auto flex max-w-7xl gap-2 overflow-x-auto px-4 py-3 [scrollbar-width:none]">
      <a href="./" class="<?= $categoriaAtual === '' ? $chipAtivo : $chipInativo ?>">Todas</a>
      <?php foreach ($categorias as $categoria): ?>
        <a href="<?= e('?categoria=' . urlencode($categoria)) ?>"
           class="<?= $categoria === $categoriaAtual ? $chipAtivo : $chipInativo ?>"><?= e($categoria) ?></a>
      <?php endforeach; ?>
    </div>
  </nav>

  <main class="mx-auto w-full max-w-7xl flex-1 px-4 py-6">
    <h2 class="mb-4 text-lg font-semibold">
      <?= $categoriaAtual === '' ? 'Todas as fotos' : e($categoriaAtual) ?>
      <span class="font-normal text-zinc-400">(<?= count($fotos) ?>)</span>
    </h2>

    <?php if (count($fotos) === 0): ?>
      <p class="rounded-xl border border-dashed border-zinc-300 bg-white px-6 py-16 text-center text-zinc-500">
        Ainda não há fotos nesta categoria.
      </p>
    <?php else: ?>
      <!-- Photo grid (filled by gallery.js) -->
      <div id="gallery" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"></div>

      <div class="mt-8 text-center">
        <button id="show-more" type="button"
                class="hidden rounded-full border border-zinc-300 bg-white px-6 py-2 text-sm font-medium text-zinc-700 shadow-sm transition-colors hover:border-brand hover:text-brand">
          Mostrar mais fotos
        </button>
      </div>
    <?php endif; ?>
  </main>

  <footer class="border-t border-zinc-200 bg-white py-6 text-center text-sm text-zinc-500">
    Afonso Saraiva e Gabriel Sousa · ExpoCIC 2023
  </footer>

  <!-- Lightbox -->
  <div id="lightbox" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/90 p-4"
       role="dialog" aria-modal="true" aria-label="Foto">
    <button id="lightbox-close" type="button" aria-label="Fechar"
            class="absolute top-4 right-4 rounded-full p-2 text-3xl leading-none text-white/80 hover:bg-white/10 hover:text-white">&times;</button>
    <button id="lightbox-prev" type="button" aria-label="Foto anterior"
            class="absolute left-2 rounded-full px-3 py-1 text-5xl text-white/80 hover:bg-white/10 hover:text-white sm:left-6">&lsaquo;</button>

    <figure class="flex max-h-full flex-col items-center gap-4">
      <img id="lightbox-image" src="" alt="" class="max-h-[80vh] w-auto rounded-lg shadow-2xl">
      <figcaption class="flex items-center gap-4">
        <a id="lightbox-download" href="" download
           class="rounded-full bg-brand px-6 py-2 text-sm font-semibold text-white shadow transition-colors hover:bg-brand-dark">Descarregar</a>
        <span id="lightbox-counter" class="text-sm text-white/70"></span>
      </figcaption>
    </figure>

    <button id="lightbox-next" type="button" aria-label="Foto seguinte"
            class="absolute right-2 rounded-full px-3 py-1 text-5xl text-white/80 hover:bg-white/10 hover:text-white sm:right-6">&rsaquo;</button>
  </div>

  <!-- Photo list for gallery.js (escaped so it can't break out of the script tag) -->
  <script id="photos-data" type="application/json"><?= json_encode($fotos, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?></script>
</body>
</html>

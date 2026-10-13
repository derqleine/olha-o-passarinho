<?php
require file_exists(__DIR__ . '/config.php') ? __DIR__ . '/config.php' : __DIR__ . '/config.example.php';

if (isset($_POST['folder']) && isset($_FILES['image'])) {
    // A categoria vira nome de pasta: só letras, números, - e _
    $categoria = preg_replace('/[^A-Za-z0-9_-]/', '', $_POST['folder']);
    $nome = basename($_FILES['image']['name']);
    $extensao = strtolower(pathinfo($nome, PATHINFO_EXTENSION));

    if ($categoria === '' || !in_array($extensao, ['jpg', 'jpeg', 'png'])) {
        echo 'Invalid request.';
    } elseif ($_FILES['image']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['image']['tmp_name'])) {
        $target_dir = __DIR__ . '/' . UPLOADS_DIR . $categoria . '/';
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $nome);
        echo 'Imagem movida para o servidor com sucesso!';
    } else {
        echo 'Error uploading image.';
    }
} else {
    echo 'Invalid request.';
}
?>

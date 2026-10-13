<?php
if (isset($_POST['folder']) && isset($_FILES['image'])) {
    $folder = $_POST['folder'];
    if ($_FILES['image']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['image']['tmp_name'])) {
        $target_dir = $folder;
        $target_file = $target_dir . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
        echo 'Imagem movida para o servidor com sucesso!';
    } else {
        echo 'Error uploading image.';
    }
} else {
    echo 'Invalid request.';
}
?>
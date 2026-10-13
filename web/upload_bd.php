<?php
require __DIR__ . '/db.php';

$categoria = $_POST['categoria'] ?? '';
$fotoDefault = $_POST['fotoDefault'] ?? '';
$fotoMoldura = $_POST['fotoMoldura'] ?? '';
$fotoMolduraCharacter = $_POST['fotoMolduraCharacter'] ?? '';
$observacoes = "...";

// Category: letters, numbers, - and _ followed by "/" (e.g. "12IF/")
// File names: letters, numbers, ., - and _ (the character photo is optional)
if (!preg_match('/^[A-Za-z0-9_-]+\/$/', $categoria)
    || !preg_match('/^[A-Za-z0-9_.-]+$/', $fotoDefault)
    || !preg_match('/^[A-Za-z0-9_.-]+$/', $fotoMoldura)
    || !preg_match('/^[A-Za-z0-9_.-]*$/', $fotoMolduraCharacter)) {
    die("Invalid request.");
}

try {
    $stmt = getDb()->prepare("INSERT INTO fotografias (categoria, fotoDefault, fotoMoldura, fotoMolduraCharacter, observacoes) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$categoria, $fotoDefault, $fotoMoldura, $fotoMolduraCharacter, $observacoes]);
    echo "Caminho registado na base de dados";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

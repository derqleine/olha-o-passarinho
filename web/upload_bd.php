<?php
require __DIR__ . '/db.php';

$categoria = $_POST['categoria'];
$fotoDefault = $_POST['fotoDefault'];
$fotoMoldura = $_POST['fotoMoldura'];
$fotoMolduraCharacter = $_POST['fotoMolduraCharacter'];
$observacoes = "...";

try {
    $stmt = getDb()->prepare("INSERT INTO fotografias (categoria, fotoDefault, fotoMoldura, fotoMolduraCharacter, observacoes) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$categoria, $fotoDefault, $fotoMoldura, $fotoMolduraCharacter, $observacoes]);
    echo "Caminho registado na base de dados";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

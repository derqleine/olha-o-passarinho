<?php

$categoria = $_POST['categoria'];
$fotoDefault = $_POST['fotoDefault'];
$fotoMoldura = $_POST['fotoMoldura'];
$fotoMolduraCharacter = $_POST['fotoMolduraCharacter'];
$observacoes = "...";
#echo $categoria . " " . $fotoDefault. " " . $fotoMoldura. " " . $fotoMolduraCharacter. " " . $observacoes . "<br>Fim";
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "polaroidexpocic";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare the SQL statement
$stmt = $conn->prepare("INSERT INTO fotografias (categoria, fotoDefault, fotoMoldura, fotoMolduraCharacter, observacoes) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $categoria, $fotoDefault, $fotoMoldura, $fotoMolduraCharacter, $observacoes);

// Execute the statement
if ($stmt->execute() === TRUE) {
    echo "Caminho registado na base de dados";
} else {

    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
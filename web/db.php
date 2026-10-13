<?php
// Database connection shared by the gallery and the upload endpoints.
require_once file_exists(__DIR__ . '/config.php') ? __DIR__ . '/config.php' : __DIR__ . '/config.example.php';

function getDb()
{
    static $db = null;

    if ($db === null) {
        $dsn = 'mysql:host=' . DB_SERVER . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        try {
            $db = new PDO($dsn, DB_USERNAME, DB_PASSWORD, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            // Show only the message, never the connection details
            die("Connection failed: " . $e->getMessage());
        }
    }

    return $db;
}

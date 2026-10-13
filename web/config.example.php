<?php
// Configuração do servidor.
// - No Docker, os valores vêm das variáveis de ambiente definidas no compose.yaml.
// - Num alojamento, copiar este ficheiro para config.php e alterar os valores
//   por defeito (o config.php não vai para o git).

define('DB_SERVER', getenv('DB_HOST') ?: 'localhost');
define('DB_USERNAME', getenv('DB_USER') ?: 'root');
define('DB_PASSWORD', getenv('DB_PASSWORD') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'polaroidexpocic');

// Pasta onde ficam as fotos enviadas pelo script Python
define('UPLOADS_DIR', 'uploads/');

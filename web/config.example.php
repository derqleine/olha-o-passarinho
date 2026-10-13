<?php
// Configuração do servidor.
// Para usar valores próprios (ex.: num alojamento), copiar para config.php e alterar.
// O config.php não vai para o git; se não existir, usam-se estes valores (XAMPP).

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'polaroidexpocic');

// Pasta onde ficam as fotos enviadas pelo script Python
define('UPLOADS_DIR', 'uploads/');

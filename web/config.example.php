<?php
// Server configuration.
// - With Docker, values come from the environment variables set in compose.yaml.
// - On a web host, copy this file to config.php and change the default values
//   (config.php is git-ignored).

define('DB_SERVER', getenv('DB_HOST') ?: 'localhost');
define('DB_USERNAME', getenv('DB_USER') ?: 'root');
define('DB_PASSWORD', getenv('DB_PASSWORD') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'polaroidexpocic');

// Folder where the photos sent by the Python script are stored
define('UPLOADS_DIR', 'uploads/');

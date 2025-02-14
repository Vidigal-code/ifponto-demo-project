<?php

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'hr_db');
define('DB_USER', 'root');
define('DB_PASS', 'Kzlui_Xga4wO');

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

?>

<?php
$db_name = "geedb";
$db_host = "127.0.0.1";
$db_user = "root";
$db_pass = "";

try {
    $pdo = new PDO("mysql:dbname=" . $db_name . ";host" . $db_host, $db_user, $db_pass);

    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (exception $e) {
    echo "Error:" . $e;
}


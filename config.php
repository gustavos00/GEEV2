<?php
/*
require '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$db_name = $_ENV['DB_NAME'];
$db_host = $_ENV['DB_HOST'];
$db_user = $_ENV['DB_USER'];
$db_pass = $_ENV['DB_PASS'];*/

$db_name = 'geedb';
$db_host = '127.0.0.1';
$db_user = 'root';
$db_pass = '';

try {
    $pdo = new PDO("mysql:dbname=" . $db_name . ";host" . $db_host, $db_user, $db_pass);

    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (exception $e) {
    echo "Error:" . $e;
}
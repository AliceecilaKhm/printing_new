<?php
$enPath = __DIR__ . '/.env';

if (!file_exists($enPath)) {
    die("Файл не найден");
}


$env = parse_ini_file($enPath);
$host = isset($env['DB_HOST']) ? $env['DB_HOST'] : 'localhost';
$user = isset($env['DB_USER']) ? $env['DB_USER'] : '';
$pass = isset($env['DB_PASS']) ? $env['DB_PASS'] : '';
$db = isset($env['DB_NAME']) ? $env['DB_NAME'] : '';
$port = (int)(isset($env['DB_PORT']) ? $env['DB_PORT'] : 3306);

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Ошибка подключения");
}
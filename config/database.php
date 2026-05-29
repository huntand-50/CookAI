<?php
/**
 * Database Configuration
 * Конфигурация подключения к БД
 */

// Прочтение переменных окружения
$env_file = dirname(__FILE__) . '/.env';
if (!file_exists($env_file)) {
    die("Ошибка: Не найден файл .env. Скопируйте .env.example в .env");
}

$env_vars = parse_ini_file($env_file);

$db_host = $env_vars['DB_HOST'] ?? 'localhost';
$db_name = $env_vars['DB_NAME'] ?? 'cookai_db';
$db_user = $env_vars['DB_USER'] ?? 'cookai_user';
$db_pass = $env_vars['DB_PASSWORD'] ?? '';
$db_port = $env_vars['DB_PORT'] ?? 3306;
$db_charset = $env_vars['DB_CHARSET'] ?? 'utf8mb4';

// DSN для PDO
$dsn = "mysql:host={$db_host};port={$db_port};dbname={$db_name};charset={$db_charset}";

// Опции PDO
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$db_charset}",
];

try {
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
    
    // Установка сноса времени
    $pdo->query("SET SESSION sql_mode='STRICT_TRANS_TABLES,NO_ZERO_DATE,NO_ZERO_IN_DATE,ERROR_FOR_DIVISION_BY_ZERO'");
    
} catch (PDOException $e) {
    header('Content-Type: text/plain; charset=utf-8');
    die("Ошибка подключения к БД: " . $e->getMessage());
}

// Глобальная переменная
define('DB', $pdo);
?>

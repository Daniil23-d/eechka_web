<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'eshka_db');
define('DB_CHARSET', 'utf8mb4');

// Функция подключения к БД
function getDbConnection() {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    
    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        return $pdo;
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Ошибка подключения: ' . $e->getMessage()]);
        exit;
    }
}

// CORS headers для фронтенда
header("Access-Control-Allow-Origin: https://olivine-zircon23547.my-vm.work");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=utf-8');

// Обработка OPTIONS запросов
if (isset($_SERVER['HTTP_ORIGIN'])) {
    // Разрешаем только наш фронтенд
    $allowed_origins = [
        'http://localhost:3000',
        'http://localhost'
    ];
    
    if (in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        header('Access-Control-Max-Age: 86400'); // 24 часа кэширования preflight
    }
}

// Обработка OPTIONS запросов
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Обычные заголовки
header('Content-Type: application/json; charset=utf-8');
?>
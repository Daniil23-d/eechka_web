<?php
require_once '../config.php';

echo json_encode([
    'success' => true,
    'message' => 'API ЕШКА работает!',
    'version' => '1.0',
    'endpoints' => [
        'categories' => '/api/categories.php',
        'menu' => '/api/menu.php?category_id=X',
        'slides' => '/api/slides.php'
    ]
]);
?>
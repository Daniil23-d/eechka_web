<?php
require_once '../config.php';

header('Content-Type: application/json; charset=utf-8');

// session_start();
// if (!isset($_SESSION['admin_logged_in']) && !isset($_COOKIE['admin_logged_in'])) {
//     http_response_code(401);
//     echo json_encode(['success' => false, 'message' => 'Требуется авторизация']);
//     exit;
// }

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Метод не разрешен']);
    exit;
}

$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'ID категории не указан']);
    exit;
}

try {
    $pdo = getDbConnection();
    
    // Проверяем, есть ли блюда в этой категории
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM dishes WHERE category_id = ?");
    $stmt->execute([$id]);
    $result = $stmt->fetch();
    
    if ($result['count'] > 0) {
        echo json_encode(['success' => false, 'message' => 'Нельзя удалить категорию с блюдами']);
        exit;
    }
    
    // Удаляем категорию
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    
    echo json_encode(['success' => true, 'message' => 'Категория удалена']);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Ошибка базы данных']);
}
?>
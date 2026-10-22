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
    echo json_encode(['success' => false, 'message' => 'ID блюда не указан']);
    exit;
}

try {
    $pdo = getDbConnection();
    
    // Удаляем изображение
    $stmt = $pdo->prepare("SELECT image FROM dishes WHERE id = ?");
    $stmt->execute([$id]);
    $dish = $stmt->fetch();
    
    if ($dish && $dish['image'] !== 'default.png') {
        $filepath = '../uploads/' . $dish['image'];
        if (file_exists($filepath)) {
            unlink($filepath);
        }
    }
    
    // Удаляем блюдо из БД
    $stmt = $pdo->prepare("DELETE FROM dishes WHERE id = ?");
    $stmt->execute([$id]);
    
    echo json_encode(['success' => true, 'message' => 'Блюдо удалено']);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Ошибка базы данных']);
}
?>
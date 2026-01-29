<?php
require_once '../config.php';

$pdo = getDbConnection();

try {
    $categoryId = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;
    
    if ($categoryId) {
        $stmt = $pdo->prepare("SELECT * FROM dishes WHERE category_id = ? AND is_available = 1 ORDER BY id ASC");
        $stmt->execute([$categoryId]);
        $dishes = $stmt->fetchAll();
    } else {
        $stmt = $pdo->query("SELECT * FROM dishes WHERE is_available = 1 ORDER BY id ASC");
        $dishes = $stmt->fetchAll();
    }
    
    foreach ($dishes as &$dish) {
        $stmt = $pdo->prepare("SELECT name FROM categories WHERE id = ?");
        $stmt->execute([$dish['category_id']]);
        $category = $stmt->fetch();
        $dish['category_name'] = $category['name'] ?? 'Без категории';
    }
    
    echo json_encode(['success' => true, 'data' => $dishes]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
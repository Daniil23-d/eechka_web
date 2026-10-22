<?php
require_once '../config.php';
$pdo = getDbConnection();

try {
    $categoryId = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;
    $minPrice = isset($_GET['min_price']) ? (float)$_GET['min_price'] : null;
    $maxPrice = isset($_GET['max_price']) ? (float)$_GET['max_price'] : null;
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    
    $sql = "SELECT d.*, c.name as category_name 
            FROM dishes d 
            JOIN categories c ON d.category_id = c.id 
            WHERE d.is_available = 1";
    
    $params = [];
    
    if ($categoryId) {
        $sql .= " AND d.category_id = ?";
        $params[] = $categoryId;
    }
    
    if ($minPrice !== null) {
        $sql .= " AND d.price >= ?";
        $params[] = $minPrice;
    }
    
    if ($maxPrice !== null) {
        $sql .= " AND d.price <= ?";
        $params[] = $maxPrice;
    }
    
    if ($search) {
        $sql .= " AND (d.name LIKE ? OR d.description LIKE ?)";
        $searchParam = "%$search%";
        $params[] = $searchParam;
        $params[] = $searchParam;
    }
    
    $sql .= " ORDER BY d.sort_order ASC, d.id ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $dishes = $stmt->fetchAll();
    
    echo json_encode(['success' => true, 'data' => $dishes], JSON_UNESCAPED_UNICODE);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
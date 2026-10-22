<?php
require_once '../config.php';

$pdo = getDbConnection();

try {
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY sort_order ASC");
    $categories = $stmt->fetchAll();
    
    echo json_encode(['success' => true, 'data' => $categories]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
<?php
require_once '../config.php';

$pdo = getDbConnection();

try {
    $stmt = $pdo->query("SELECT * FROM slides WHERE is_active = 1 ORDER BY sort_order ASC");
    $slides = $stmt->fetchAll();
    
    echo json_encode(['success' => true, 'data' => $slides]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
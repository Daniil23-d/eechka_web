<?php
require_once '../config.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Метод не разрешен'], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $pdo = getDbConnection();
    
    $name = $_POST['name'] ?? '';
    $category_id = $_POST['category_id'] ?? null;
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    
    if (empty($name) || !$category_id) {
        echo json_encode(['success' => false, 'message' => 'Название и категория обязательны'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    $image = 'default-dish.png';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['image'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
        
        if (in_array($file['type'], $allowedTypes) && $file['size'] <= 5 * 1024 * 1024) {
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $image = 'dish_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
            
            $uploadDir = __DIR__ . '/uploads/';
            
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $filepath = $uploadDir . $image;
            if (!move_uploaded_file($file['tmp_name'], $filepath)) {
                throw new Exception('Не удалось сохранить файл');
            }
        }
    }
    
    $stmt = $pdo->prepare("
        INSERT INTO dishes (category_id, name, description, price, image, is_available) 
        VALUES (?, ?, ?, ?, ?, 1)
    ");
    $stmt->execute([$category_id, $name, $description, $price, $image]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Блюдо успешно добавлено',
        'dish' => [
            'id' => $pdo->lastInsertId(),
            'name' => $name,
            'category_id' => $category_id,
            'description' => $description,
            'price' => $price,
            'image' => $image
        ]
    ], JSON_UNESCAPED_UNICODE);
    
} catch (PDOException $e) {
    error_log('DB Error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Ошибка базы данных: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    error_log('Upload Error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Ошибка загрузки файла'], JSON_UNESCAPED_UNICODE);
}
?>
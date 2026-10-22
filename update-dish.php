<?php
require_once '../config.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Метод не разрешен']);
    exit;
}

try {
    $pdo = getDbConnection();
    
    $id = $_POST['id'] ?? null;
    $name = $_POST['name'] ?? '';
    $category_id = $_POST['category_id'] ?? null;
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $old_image = $_POST['old_image'] ?? 'default-dish.png';
    
    if (!$id || empty($name) || !$category_id) {
        echo json_encode(['success' => false, 'message' => 'ID, название и категория обязательны']);
        exit;
    }
    
    // 🔥 ОБРАБОТКА ИЗОБРАЖЕНИЯ
    $image = $old_image; // По умолчанию оставляем старое
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
            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                // 🔥 Удаляем старое фото, если оно не дефолтное
                if ($old_image !== 'default-dish.png' && file_exists($uploadDir . $old_image)) {
                    unlink($uploadDir . $old_image);
                }
            } else {
                throw new Exception('Не удалось сохранить файл');
            }
        }
    }
    
    // 🔥 ПРОВЕРЬТЕ: есть ли колонка slug? Если нет — удалите из запроса
    $stmt = $pdo->prepare("
        UPDATE dishes 
        SET category_id = ?, name = ?, description = ?, price = ?, image = ? 
        WHERE id = ?
    ");
    $stmt->execute([$category_id, $name, $description, $price, $image, $id]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Блюдо успешно обновлено',
        'dish' => [
            'id' => $id,
            'name' => $name,
            'category_id' => $category_id,
            'description' => $description,
            'price' => $price,
            'image' => $image
        ]
    ]);
    
} catch (PDOException $e) {
    error_log('DB Error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Ошибка базы данных']);
} catch (Exception $e) {
    error_log('Update Error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Ошибка обновления']);
}
?>
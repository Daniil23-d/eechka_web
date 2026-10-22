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
    $slug = $_POST['slug'] ?? '';
    $description = $_POST['description'] ?? '';
    $show_on_homepage = $_POST['show_on_homepage'] ?? '1';
    $old_image = $_POST['old_image'] ?? 'default-category.png';
    
    if (!$id || empty($name) || empty($slug)) {
        echo json_encode(['success' => false, 'message' => 'ID, название и slug обязательны']);
        exit;
    }
    
    // 🔥 ОБРАБОТКА ИЗОБРАЖЕНИЯ
    $image = $old_image;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['image'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
        
        if (in_array($file['type'], $allowedTypes) && $file['size'] <= 5 * 1024 * 1024) {
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $image = 'cat_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
            
            $uploadDir = __DIR__ . '/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $filepath = $uploadDir . $image;
            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                // 🔥 Удаляем старое фото
                if ($old_image !== 'default-category.png' && file_exists($uploadDir . $old_image)) {
                    unlink($uploadDir . $old_image);
                }
            } else {
                throw new Exception('Не удалось сохранить файл');
            }
        }
    }
    
    $stmt = $pdo->prepare("
        UPDATE categories 
        SET name = ?, slug = ?, description = ?, show_on_homepage = ?, image = ? 
        WHERE id = ?
    ");
    $stmt->execute([$name, $slug, $description, $show_on_homepage, $image, $id]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Категория успешно обновлена',
        'category' => [
            'id' => $id,
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'show_on_homepage' => $show_on_homepage,
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
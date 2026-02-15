<?php
session_start();
require_once __DIR__ . '/../../config/database.php';

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['admin_logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'غير مصرح']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    try {
        $database = new Database();
        $db = $database->getConnection();

        if ($action === 'add' || $action === 'edit') {
            $name_ar = $_POST['name_ar'] ?? '';
            $name_fr = $_POST['name_fr'] ?? '';
            $description_ar = $_POST['description_ar'] ?? '';
            $description_fr = $_POST['description_fr'] ?? '';
            $icon = $_POST['icon'] ?? 'fas fa-taxi';
            $price = !empty($_POST['price']) ? $_POST['price'] : 0;
            $price_euro = !empty($_POST['price_euro']) ? $_POST['price_euro'] : null;
            $price_type = $_POST['price_type'] ?? 'starting_from';
            
            // Handle Image Upload
            $image_path = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = __DIR__ . '/../../uploads/services/';
                
                // Create directory if it doesn't exist
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $file_name = uniqid('service_') . '.' . $file_extension;
                $target_file = $upload_dir . $file_name;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                    $image_path = 'uploads/services/' . $file_name;
                }
            }

            if ($action === 'add') {
                $query = "INSERT INTO services (name_ar, name_fr, description_ar, description_fr, icon, image, price, price_euro, price_type) 
                          VALUES (:name_ar, :name_fr, :description_ar, :description_fr, :icon, :image, :price, :price_euro, :price_type)";
                $stmt = $db->prepare($query);
                $stmt->bindValue(':image', $image_path);
            } else {
                $id = $_POST['id'];
                $query = "UPDATE services SET 
                          name_ar = :name_ar, 
                          name_fr = :name_fr, 
                          description_ar = :description_ar, 
                          description_fr = :description_fr, 
                          icon = :icon,
                          price = :price,
                          price_euro = :price_euro,
                          price_type = :price_type";
                
                if ($image_path) {
                    $query .= ", image = :image";
                }
                
                $query .= " WHERE id = :id";
                $stmt = $db->prepare($query);
                $stmt->bindValue(':id', $id);
                
                if ($image_path) {
                    $stmt->bindValue(':image', $image_path);
                }
            }

            $stmt->bindValue(':name_ar', $name_ar);
            $stmt->bindValue(':name_fr', $name_fr);
            $stmt->bindValue(':description_ar', $description_ar);
            $stmt->bindValue(':description_fr', $description_fr);
            $stmt->bindValue(':icon', $icon);
            $stmt->bindValue(':price', $price);
            $stmt->bindValue(':price_euro', $price_euro);
            $stmt->bindValue(':price_type', $price_type);

            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'فشل الحفظ في قاعدة البيانات']);
            }
        } elseif ($action === 'delete') {
            $id = $_POST['id'];
            
            // Get image path to delete file
            $stmt = $db->prepare("SELECT image FROM services WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $service = $stmt->fetch();
            
            if ($service && $service['image'] && file_exists(__DIR__ . '/../../' . $service['image'])) {
                unlink(__DIR__ . '/../../' . $service['image']);
            }
            
            $stmt = $db->prepare("DELETE FROM services WHERE id = :id");
            if ($stmt->execute([':id' => $id])) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'فشل الحذف']);
            }
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>

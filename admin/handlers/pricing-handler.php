<?php
session_start();
require_once __DIR__ . '/../../config/database.php';
header('Content-Type: application/json');

if (!isset($_SESSION['admin_logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']); exit;
}

$action = $_POST['action'] ?? '';
$db = (new Database())->getConnection();

if ($action === 'add') {
    $stmt = $db->prepare("INSERT INTO pricing (from_location_ar, from_location_fr, to_location_ar, to_location_fr, price, price_euro, service_id, distance_km, duration) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['from_location_ar'], 
        $_POST['from_location_fr'], 
        $_POST['to_location_ar'], 
        $_POST['to_location_fr'], 
        $_POST['price'], 
        !empty($_POST['price_euro']) ? $_POST['price_euro'] : null,
        $_POST['service_id'],
        $_POST['distance'], 
        $_POST['duration']
    ]);
    echo json_encode(['success' => true]);
} elseif ($action === 'edit') {
    $stmt = $db->prepare("UPDATE pricing SET from_location_ar=?, from_location_fr=?, to_location_ar=?, to_location_fr=?, price=?, price_euro=?, service_id=?, distance_km=?, duration=? WHERE id=?");
    $stmt->execute([
        $_POST['from_location_ar'], 
        $_POST['from_location_fr'], 
        $_POST['to_location_ar'], 
        $_POST['to_location_fr'], 
        $_POST['price'], 
        !empty($_POST['price_euro']) ? $_POST['price_euro'] : null,
        $_POST['service_id'],
        $_POST['distance'], 
        $_POST['duration'], 
        $_POST['id']
    ]);
    echo json_encode(['success' => true]);
} elseif ($action === 'delete') {
    $stmt = $db->prepare("DELETE FROM pricing WHERE id=?");
    $stmt->execute([$_POST['id']]);
    echo json_encode(['success' => true]);
}
?>

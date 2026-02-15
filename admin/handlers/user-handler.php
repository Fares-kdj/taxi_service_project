<?php
session_start();
require_once __DIR__ . '/../../config/database.php';
header('Content-Type: application/json');

if (!isset($_SESSION['admin_logged_in'])) { exit; }

$db = (new Database())->getConnection();
$action = $_POST['action'];

if ($action === 'add') {
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_POST['username'], $_POST['email'], $pass, $_POST['role']]);
    echo json_encode(['success' => true]);
} elseif ($action === 'edit') {
    $sql = "UPDATE users SET username=?, email=?, role=?";
    $params = [$_POST['username'], $_POST['email'], $_POST['role']];
    
    if (!empty($_POST['password'])) {
        $sql .= ", password=?";
        $params[] = password_hash($_POST['password'], PASSWORD_DEFAULT);
    }
    
    $sql .= " WHERE id=?";
    $params[] = $_POST['id'];
    
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    echo json_encode(['success' => true]);
} elseif ($action === 'delete') {
    $stmt = $db->prepare("DELETE FROM users WHERE id=?");
    $stmt->execute([$_POST['id']]);
    echo json_encode(['success' => true]);
}
?>

<?php
session_start();
require_once __DIR__ . '/../../config/database.php';
header('Content-Type: application/json');

if (!isset($_SESSION['admin_logged_in'])) { exit; }

$db = (new Database())->getConnection();
$action = $_POST['action'];

if ($action === 'add') {
    $stmt = $db->prepare("INSERT INTO testimonials (name_ar, name_fr, role_ar, role_fr, content_ar, content_fr, rating) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$_POST['name_ar'], $_POST['name_fr'], $_POST['role_ar'], $_POST['role_fr'], $_POST['content_ar'], $_POST['content_fr'], $_POST['rating']]);
    echo json_encode(['success' => true]);
} elseif ($action === 'edit') {
    $stmt = $db->prepare("UPDATE testimonials SET name_ar=?, name_fr=?, role_ar=?, role_fr=?, content_ar=?, content_fr=?, rating=? WHERE id=?");
    $stmt->execute([$_POST['name_ar'], $_POST['name_fr'], $_POST['role_ar'], $_POST['role_fr'], $_POST['content_ar'], $_POST['content_fr'], $_POST['rating'], $_POST['id']]);
    echo json_encode(['success' => true]);
} elseif ($action === 'delete') {
    $stmt = $db->prepare("DELETE FROM testimonials WHERE id=?");
    $stmt->execute([$_POST['id']]);
    echo json_encode(['success' => true]);
}
?>

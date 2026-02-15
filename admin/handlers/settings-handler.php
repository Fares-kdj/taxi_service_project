<?php
session_start();
require_once __DIR__ . '/../../config/database.php';
header('Content-Type: application/json');

if (!isset($_SESSION['admin_logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']); exit;
}

$db = (new Database())->getConnection();

if ($_POST['action'] === 'update_settings') {
    try {
        $db->beginTransaction();
        $fields = ['company_email', 'company_phone', 'company_address_ar', 'company_address_fr', 'facebook_url', 'instagram_url', 'company_whatsapp'];
        
        $stmt = $db->prepare("UPDATE site_settings SET setting_value = :val WHERE setting_key = :key");
        
        foreach ($fields as $key) {
            if (isset($_POST[$key])) {
                $stmt->execute([':val' => $_POST[$key], ':key' => $key]);
            }
        }
        $db->commit();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        $db->rollBack();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>

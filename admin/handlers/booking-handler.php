<?php
session_start();
require_once __DIR__ . '/../../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['admin_logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$action = $_POST['action'] ?? '';
$id = $_POST['id'] ?? '';

try {
    $database = new Database();
    $db = $database->getConnection();

    if ($action === 'update_status') {
        $status = $_POST['status'] ?? '';
        $valid_statuses = ['new', 'confirmed', 'completed', 'cancelled'];
        
        if (!in_array($status, $valid_statuses)) {
            throw new Exception('Invalid status');
        }

        $query = "UPDATE bookings SET status = :status WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Status updated']);
    } 
    elseif ($action === 'delete') {
        $query = "DELETE FROM bookings WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Booking deleted']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error during deletion']);
        }
    }
    elseif ($action === 'bulk_delete') {
        $ids = $_POST['ids'] ?? [];
        if (empty($ids)) {
            echo json_encode(['success' => false, 'message' => 'No IDs provided']);
            exit;
        }
        
        // Ensure all IDs are integers to prevent SQL injection
        $ids = array_map('intval', $ids);

        // Create placeholders for IN clause
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $query = "DELETE FROM bookings WHERE id IN ($placeholders)";
        
        $stmt = $db->prepare($query); // Use $db for connection
        
        if ($stmt->execute($ids)) {
            echo json_encode(['success' => true, 'message' => 'Bookings deleted']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error during bulk deletion']);
        }
    }
    else {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>

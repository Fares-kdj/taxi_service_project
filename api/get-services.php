<?php
/**
 * Get Active Services API
 * Returns all active services as JSON
 */

require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

$database = new Database();
$db = $database->getConnection();

try {
    $lang = isset($_GET['lang']) && $_GET['lang'] === 'fr' ? 'fr' : 'ar';
    
    $query = "SELECT id, 
                     name_{$lang} as name,
                     description_{$lang} as description,
                     price, 
                     price_type,
                     icon 
              FROM services 
              WHERE active = TRUE 
              ORDER BY display_order ASC";
    
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'services' => $services
    ]);
} catch(PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>

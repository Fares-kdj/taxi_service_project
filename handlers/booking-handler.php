<?php
/**
 * Booking Form Handler
 * Processes customer booking requests
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/settings.php';

header('Content-Type: application/json');

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $customer_name = isset($_POST['customer_name']) ? trim($_POST['customer_name']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $pickup_location = isset($_POST['pickup_location']) ? trim($_POST['pickup_location']) : '';
    $destination = isset($_POST['destination']) ? trim($_POST['destination']) : '';
    $service_id = !empty($_POST['service_id']) ? intval($_POST['service_id']) : null;
    $booking_date = isset($_POST['booking_date']) ? $_POST['booking_date'] : null;
    $booking_time = isset($_POST['booking_time']) ? $_POST['booking_time'] : null;
    $passengers = isset($_POST['passengers']) ? intval($_POST['passengers']) : 1;
    $notes = isset($_POST['notes']) ? trim($_POST['notes']) : '';
    $price = isset($_POST['price']) ? $_POST['price'] : null;
    $price_euro = isset($_POST['price_euro']) ? $_POST['price_euro'] : null;
    $from_pricing = isset($_POST['from_pricing']) && $_POST['from_pricing'] == '1';
    
    // Validate and ensure service_id is valid
    // EXCEPTION: If booking from pricing page, allow NULL service_id
    if ($from_pricing) {
        // Booking from pricing page - service is not required
        $service_id = null;
    } else {
        // Regular booking - validate service_id
        if ($service_id) {
            // Check if this service_id exists and is active
            $service_check = $db->prepare("SELECT id FROM services WHERE id = ? AND active = TRUE");
            $service_check->execute([$service_id]);
            if ($service_check->rowCount() == 0) {
                // Service doesn't exist or is inactive, use default
                $service_id = null;
            }
        }
        
        // If service_id is still null, get first active service as fallback
        if (!$service_id) {
            $default_service = $db->query("SELECT id FROM services WHERE active = TRUE ORDER BY display_order ASC, id ASC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
            if ($default_service) {
                $service_id = $default_service['id'];
            } else {
                // No active services found - this is a critical error
                echo json_encode([
                    'success' => false,
                    'message' => 'No active services available. Please contact support.'
                ]);
                exit;
            }
        }
    }
    

    // Validation
    $errors = [];
    
    if (empty($customer_name)) {
        $errors[] = 'Name is required';
    }
    
    if (empty($phone)) {
        $errors[] = 'Phone number is required';
    } elseif (!preg_match('/^[0-9+\s\-()]+$/', $phone)) {
        $errors[] = 'Invalid phone number format';
    }
    
    if (empty($pickup_location)) {
        $errors[] = 'Pickup location is required';
    }
    
    if (!empty($errors)) {
        echo json_encode([
            'success' => false,
            'message' => implode(', ', $errors)
        ]);
        exit;
    }
    
    try {
        $query = "INSERT INTO bookings (customer_name, phone, pickup_location, destination, service_id, booking_date, booking_time, passengers, notes, price, price_euro, status) 
                  VALUES (:customer_name, :phone, :pickup_location, :destination, :service_id, :booking_date, :booking_time, :passengers, :notes, :price, :price_euro, 'new')";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':customer_name', $customer_name);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':pickup_location', $pickup_location);
        $stmt->bindParam(':destination', $destination);
        $stmt->bindParam(':service_id', $service_id);
        $stmt->bindParam(':booking_date', $booking_date);
        $stmt->bindParam(':booking_time', $booking_time);
        $stmt->bindParam(':passengers', $passengers);
        $stmt->bindParam(':notes', $notes);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':price_euro', $price_euro);
        
        if ($stmt->execute()) {
            $lang = $_SESSION['lang'] ?? 'ar';
            $msg = ($lang === 'fr') 
                ? 'Réservation envoyée avec succès ! Nous vous contacterons bientôt.' 
                : 'تم استلام طلب الحجز بنجاح! سنتواصل معك قريباً.';
            
            $title = ($lang === 'fr') ? 'Réservation réussie !' : 'تم الحجز بنجاح!';
            $btn_text = ($lang === 'fr') ? 'D\'accord' : 'حسناً';
                
            echo json_encode([
                'success' => true,
                'message' => $msg,
                'title' => $title,
                'btn_text' => $btn_text
            ]);
            
            // TODO: Send email notification to admin
            // TODO: Send SMS confirmation to customer
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to submit booking. Please try again.'
            ]);
        }
    } catch(PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}
?>

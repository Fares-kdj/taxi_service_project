<?php
/**
 * Site Configuration
 * Taxi Service - Chevaliers de la Route
 */

// Site URL
define('SITE_URL', 'http://localhost/dispatch-website');

// Upload paths
define('UPLOAD_DIR', __DIR__ . '/../../uploads/');
define('SERVICE_IMAGES_DIR', UPLOAD_DIR . 'services/');
define('TESTIMONIAL_IMAGES_DIR', UPLOAD_DIR . 'testimonials/');
define('GALLERY_IMAGES_DIR', UPLOAD_DIR . 'gallery/');

// Create upload directories if they don't exist
if (!file_exists(SERVICE_IMAGES_DIR)) {
    mkdir(SERVICE_IMAGES_DIR, 0755, true);
}
if (!file_exists(TESTIMONIAL_IMAGES_DIR)) {
    mkdir(TESTIMONIAL_IMAGES_DIR, 0755, true);
}
if (!file_exists(GALLERY_IMAGES_DIR)) {
    mkdir(GALLERY_IMAGES_DIR, 0755, true);
}

// Session configuration
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Default language
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'fr';
}

// Timezone
date_default_timezone_set('Africa/Algiers');

// Error reporting (disable in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>

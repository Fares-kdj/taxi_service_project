<?php
/**
 * Language Switcher Handler
 * Changes session language
 */

require_once __DIR__ . '/../config/settings.php';

header('Content-Type: application/json');

if (isset($_POST['lang']) && in_array($_POST['lang'], ['ar', 'fr'])) {
    $_SESSION['lang'] = $_POST['lang'];
    echo json_encode([
        'success' => true,
        'lang' => $_SESSION['lang']
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid language'
    ]);
}
?>

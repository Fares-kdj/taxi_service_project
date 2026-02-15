<?php
/**
 * Translation Helper
 * Load language files and provide translation function
 */

require_once __DIR__ . '/../config/settings.php';

// Get current language
$current_lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'fr';

// Load language file
$lang_file = __DIR__ . '/' . $current_lang . '.php';
if (file_exists($lang_file)) {
    require_once $lang_file;
} else {
    require_once __DIR__ . '/fr.php'; // Fallback to French
}

/**
 * Translation function
 * @param string $key - Translation key
 * @return string - Translated text
 */
function t($key) {
    global $translations;
    return isset($translations[$key]) ? $translations[$key] : $key;
}

/**
 * Get current language
 * @return string - Current language code (ar or fr)
 */
function getLang() {
    return isset($_SESSION['lang']) ? $_SESSION['lang'] : 'fr';
}

/**
 * Check if current language is RTL
 * @return bool
 */
function isRTL() {
    return getLang() === 'ar';
}
?>

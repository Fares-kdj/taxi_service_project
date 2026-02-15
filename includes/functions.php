<?php

/**
 * Format price from DZD to Euro based on exchange rate
 * @param float $dzdPrice
 * @return string Formatted Euro price
 */
function formatEuro($dzdPrice) {
    global $settings; // Assume settings are available globally or passed
    // Fetch rate if not available in global settings, but better to rely on what's loaded
    // For now, let's assume $GLOBALS['settings'] or similar is populated in header.
    // Actually, header.php fetches $settings. We should use that.
    
    // Check if we can access $settings from global scope if included in header
    // Ideally we pass it or use global
    global $settings;
    
    $rate = isset($settings['euro_exchange_rate']) ? (float)$settings['euro_exchange_rate'] : 220; // Default fallback
    if ($rate <= 0) $rate = 220;
    
    $euro = $dzdPrice / $rate;
    // Use dot as decimal separator for consistency
    return number_format($euro, 2, '.', '') . ' €';
}

/**
 * Display price with Euro equivalent
 * @param float $dzdPrice
 * @param float|null $manualEuroPrice
 * @return string HTML/String with both prices
 */
function displayPrice($dzdPrice, $manualEuroPrice = null) {
    // Detect language
    $lang = $_SESSION['lang'] ?? 'ar';
    
    // Format DZD price
    $formattedDzd = number_format($dzdPrice, 2, '.', '');
    $currency = t('dzd');
    
    // In Arabic, currency comes before number. In French, after.
    if ($lang === 'ar') {
        $dzd = $currency . ' ' . $formattedDzd;
    } else {
        $dzd = $formattedDzd . ' ' . $currency;
    }
    
    if ($manualEuroPrice && $manualEuroPrice > 0) {
        // Format Euro with dot as decimal separator
        $euro = number_format($manualEuroPrice, 2, '.', '') . ' €';
    } else {
        $euro = formatEuro($dzdPrice);
    }
    
    return '<span dir="ltr">' . $dzd . '</span> <span class="text-gray-400 mx-1">|</span> <span class="bg-blue-100 text-blue-800 text-sm font-bold px-3 py-1 rounded dark:bg-blue-200 dark:text-blue-800" dir="ltr">('.$euro.')</span>';
}
?>

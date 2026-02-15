<?php
/**
 * Floating Action Buttons
 * Quick contact buttons (Phone & WhatsApp)
 */

$phone = $settings_array['company_phone'] ?? '+213 555 123 456';
$whatsapp = $settings_array['company_whatsapp'] ?? '+213 555 123 456';
$whatsapp_clean = preg_replace('/[^0-9]/', '', $whatsapp);
// Ensure we have the country code
if (substr($whatsapp_clean, 0, 1) === '0') {
    $whatsapp_clean = '213' . substr($whatsapp_clean, 1);
} elseif (substr($whatsapp_clean, 0, 3) !== '213') {
    $whatsapp_clean = '213' . $whatsapp_clean;
}
?>

<!-- Floating Buttons -->
<div class="fixed bottom-6 <?php echo $lang === 'ar' ? 'left-6' : 'right-6'; ?> z-40 flex flex-col gap-3">
    
    <!-- WhatsApp Button -->
    <a href="https://wa.me/<?php echo $whatsapp_clean; ?>?text=<?php echo urlencode($lang === 'ar' ? 'مرحباً، أريد حجز تاكسي' : 'Bonjour, je voudrais réserver un taxi'); ?>" 
       target="_blank" 
       class="group w-14 h-14 bg-green-500 hover:bg-green-600 rounded-full flex items-center justify-center shadow-lg hover:shadow-xl transition-all hover:scale-110">
        <i class="fab fa-whatsapp text-white text-2xl"></i>
        <span class="absolute <?php echo $lang === 'ar' ? 'right-16' : 'left-16'; ?> bg-gray-900 text-white px-3 py-1 rounded-lg text-sm whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity">
            WhatsApp
        </span>
    </a>
    
    <!-- Phone Button -->
    <a href="tel:<?php echo $phone; ?>" 
       class="group w-14 h-14 bg-gold-500 hover:bg-gold-600 rounded-full flex items-center justify-center shadow-lg hover:shadow-xl transition-all hover:scale-110">
        <i class="fas fa-phone-alt text-white text-xl"></i>
        <span class="absolute <?php echo $lang === 'ar' ? 'right-16' : 'left-16'; ?> bg-gray-900 text-white px-3 py-1 rounded-lg text-sm whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity">
            <?php echo t('phone'); ?>
        </span>
    </a>
    
    <!-- Scroll to Top Button (shows on scroll) -->
    <button id="scroll-top" 
            onclick="scrollToTop()" 
            class="hidden w-14 h-14 bg-gray-700 hover:bg-gray-800 dark:bg-gray-600 dark:hover:bg-gray-700 rounded-full items-center justify-center shadow-lg hover:shadow-xl transition-all hover:scale-110">
        <i class="fas fa-arrow-up text-white text-xl"></i>
    </button>
</div>

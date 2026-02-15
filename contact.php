<?php include __DIR__ . '/includes/header.php'; ?>

<section class="py-20 bg-gray-50 dark:bg-gray-800">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-bold mb-4"><?php echo t('contact_us'); ?></h1>
            <div class="w-24 h-1 bg-gradient-to-r from-gold-500 to-gold-600 mx-auto"></div>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Form -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-bold mb-6"><?php echo t('send_message'); ?></h2>
                <form id="contactForm" class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold mb-2"><?php echo t('your_name'); ?> <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-gold-500 dark:bg-gray-800">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold mb-2"><?php echo t('your_email'); ?> <span class="text-red-500">*</span></label>
                        <input type="email" name="email" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-gold-500 dark:bg-gray-800">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold mb-2"><?php echo t('your_phone'); ?></label>
                        <input type="tel" name="phone" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-gold-500 dark:bg-gray-800">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold mb-2"><?php echo t('subject'); ?> <span class="text-red-500">*</span></label>
                        <input type="text" name="subject" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-gold-500 dark:bg-gray-800">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold mb-2"><?php echo t('message'); ?> <span class="text-red-500">*</span></label>
                        <textarea name="message" required rows="5" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-gold-500 dark:bg-gray-800"></textarea>
                    </div>
                    
                    <button type="submit" class="w-full px-6 py-4 bg-gradient-to-r from-gold-500 to-gold-600 text-white rounded-lg hover:from-gold-600 hover:to-gold-700 transition-all shadow-lg font-semibold">
                        <i class="fas fa-paper-plane mr-2"></i> <?php echo t('send_message'); ?>
                    </button>
                </form>
            </div>
            
            <!-- Contact Info & Map -->
            <div>
                <!-- Contact Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-lg">
                        <div class="w-12 h-12 bg-gradient-to-br from-gold-500 to-gold-600 rounded-lg flex items-center justify-center mb-4">
                            <i class="fas fa-phone text-white text-xl"></i>
                        </div>
                        <h3 class="font-semibold mb-2"><?php echo t('phone'); ?></h3>
                        <a href="tel:<?php echo $settings_array['company_phone']; ?>" class="text-gold-600 dark:text-gold-500">
                            <?php echo $settings_array['company_phone']; ?>
                        </a>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-lg">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center mb-4">
                            <i class="fab fa-whatsapp text-white text-xl"></i>
                        </div>
                        <h3 class="font-semibold mb-2">WhatsApp</h3>
                        <?php 
                            $wa_clean = preg_replace('/[^0-9]/', '', $settings_array['company_whatsapp']);
                            if (substr($wa_clean, 0, 1) === '0') {
                                $wa_clean = '213' . substr($wa_clean, 1);
                            } elseif (substr($wa_clean, 0, 3) !== '213') {
                                $wa_clean = '213' . $wa_clean;
                            }
                        ?>
                        <a href="https://wa.me/<?php echo $wa_clean; ?>" target="_blank" class="text-green-600">
                            <?php echo $settings_array['company_whatsapp']; ?>
                        </a>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-lg">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mb-4">
                            <i class="fas fa-envelope text-white text-xl"></i>
                        </div>
                        <h3 class="font-semibold mb-2"><?php echo t('email'); ?></h3>
                        <a href="mailto:<?php echo $settings_array['company_email']; ?>" class="text-blue-600">
                            <?php echo $settings_array['company_email']; ?>
                        </a>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-lg">
                        <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center mb-4">
                            <i class="fas fa-map-marker-alt text-white text-xl"></i>
                        </div>
                        <h3 class="font-semibold mb-2"><?php echo t('address'); ?></h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            <?php echo $settings_array['company_address_' . $lang]; ?>
                        </p>
                    </div>
                </div>
                
                <!-- Google Maps -->
                <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg overflow-hidden h-96">
                    <iframe 
                        src="<?php echo $settings_array['google_maps_embed']; ?>"
                        width="100%" 
                        height="100%" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

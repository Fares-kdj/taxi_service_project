    <!-- Footer -->
    <footer class="bg-gray-900 dark:bg-[#0b0f19] text-white mt-20 border-t border-gray-800">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                
                <!-- Column 1: About -->
                <div>
                    <div class="flex items-center space-x-3 rtl:space-x-reverse mb-4">
                        <?php if (file_exists(__DIR__ . '/../logo/white_logo.png')): ?>
                            <img src="<?php echo SITE_URL; ?>/logo/white_logo.png" alt="<?php echo $company_name; ?>" class="h-16 w-auto">
                        <?php else: ?>
                            <i class="fas fa-taxi text-2xl text-gold-500"></i>
                        <?php endif; ?>
                        <span class="text-lg font-bold text-gold-500"><?php echo $company_name; ?></span>
                    </div>
                    <p class="text-gray-400 text-sm">
                        <?php echo $lang === 'ar' 
                            ? 'خدمة تاكسي احترافية وموثوقة على مدار الساعة' 
                            : 'Service de taxi professionnel et fiable 24/7'; ?>
                    </p>
                </div>
                
                <!-- Column 2: Quick Links -->
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-gold-500"><?php echo t('quick_links'); ?></h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="<?php echo SITE_URL; ?>/index.php" class="text-gray-400 hover:text-gold-500 transition-colors"><?php echo t('home'); ?></a></li>
                        <li><a href="<?php echo SITE_URL; ?>/services.php" class="text-gray-400 hover:text-gold-500 transition-colors"><?php echo t('services'); ?></a></li>
                        <li><a href="<?php echo SITE_URL; ?>/pricing.php" class="text-gray-400 hover:text-gold-500 transition-colors"><?php echo t('pricing'); ?></a></li>
                        <li><a href="<?php echo SITE_URL; ?>/about.php" class="text-gray-400 hover:text-gold-500 transition-colors"><?php echo t('about'); ?></a></li>
                        <li><a href="<?php echo SITE_URL; ?>/contact.php" class="text-gray-400 hover:text-gold-500 transition-colors"><?php echo t('contact'); ?></a></li>
                    </ul>
                </div>
                
                <!-- Column 3: Contact Info -->
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-gold-500"><?php echo t('contact_us'); ?></h4>
                    <ul class="space-y-3 text-sm text-gray-400">
                        <li class="flex items-start space-x-3 rtl:space-x-reverse">
                            <i class="fas fa-phone mt-1 text-gold-500"></i>
                            <span><?php echo $settings_array['company_phone'] ?? '+213 555 123 456'; ?></span>
                        </li>
                        <li class="flex items-start space-x-3 rtl:space-x-reverse">
                            <i class="fab fa-whatsapp mt-1 text-gold-500"></i>
                            <span><?php echo $settings_array['company_whatsapp'] ?? '+213 555 123 456'; ?></span>
                        </li>
                        <li class="flex items-start space-x-3 rtl:space-x-reverse">
                            <i class="fas fa-envelope mt-1 text-gold-500"></i>
                            <span><?php echo $settings_array['company_email'] ?? 'contact@taxi.dz'; ?></span>
                        </li>
                        <li class="flex items-start space-x-3 rtl:space-x-reverse">
                            <i class="fas fa-map-marker-alt mt-1 text-gold-500"></i>
                            <span><?php echo $settings_array['company_address_' . $lang] ?? ''; ?></span>
                        </li>
                    </ul>
                </div>
                
                <!-- Column 4: Social & Newsletter -->
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-gold-500"><?php echo t('follow_us'); ?></h4>
                    <div class="flex space-x-4 rtl:space-x-reverse mb-6">
                        <?php if (!empty($settings_array['facebook_url'])): ?>
                        <a href="<?php echo $settings_array['facebook_url']; ?>" target="_blank" class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-800 hover:bg-gold-500 transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <?php endif; ?>
                        <?php if (!empty($settings_array['instagram_url'])): ?>
                        <a href="<?php echo $settings_array['instagram_url']; ?>" target="_blank" class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-800 hover:bg-gold-500 transition-colors">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <?php endif; ?>
                        <?php if (!empty($settings_array['twitter_url'])): ?>
                        <a href="<?php echo $settings_array['twitter_url']; ?>" target="_blank" class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-800 hover:bg-gold-500 transition-colors">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Newsletter -->
                    <h5 class="text-sm font-semibold mb-2"><?php echo t('newsletter'); ?></h5>
                    <form id="newsletter-form" class="flex gap-2">
                        <input type="email" name="newsletter_email" placeholder="<?php echo t('your_email'); ?>" 
                               class="flex-1 px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm focus:outline-none focus:border-gold-500">
                        <button type="submit" class="px-4 py-2 bg-gold-500 hover:bg-gold-600 rounded-lg transition-colors">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-6 text-center text-sm text-gray-400">
                <p>&copy; <?php echo date('Y'); ?> <?php echo $company_name; ?>. <?php echo t('all_rights_reserved'); ?></p>
            </div>
        </div>
    </footer>
    
    <?php include __DIR__ . '/floating-buttons.php'; ?>
    
    <!-- Scripts -->
    <script src="<?php echo SITE_URL; ?>/js/main.js?v=<?php echo time(); ?>"></script>
</body>
</html>

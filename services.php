<?php include __DIR__ . '/includes/header.php'; ?>

<section class="py-20 bg-gray-50 dark:bg-gray-800">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-bold mb-4"><?php echo t('our_services'); ?></h1>
            <div class="w-24 h-1 bg-gradient-to-r from-gold-500 to-gold-600 mx-auto mb-4"></div>
            <p class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                <?php echo $lang === 'ar' ? 'نقدم مجموعة واسعة من خدمات النقل المتميزة' : 'Nous offrons une large gamme de services de transport de qualité'; ?>
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            $services_query = "SELECT id, name_{$lang} as name, description_{$lang} as description, price, price_euro, price_type, icon, image 
                              FROM services WHERE active = TRUE ORDER BY display_order ASC";
            $services_stmt = $db->prepare($services_query);
            $services_stmt->execute();
            $services = $services_stmt->fetchAll();
            
            foreach ($services as $service):
            ?>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all hover:-translate-y-2">
                <div class="p-8">
                    <?php if (!empty($service['image'])): ?>
                    <div class="h-48 w-full mb-6 overflow-hidden">
                        <img src="<?php echo SITE_URL; ?>/<?php echo $service['image']; ?>" alt="<?php echo $service['name']; ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute top-4 right-4 bg-gold-500 backdrop-blur-sm p-2 rounded-lg shadow-sm">
                            <i class="fas <?php echo $service['icon']; ?> text-white text-xl"></i>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="w-20 h-20 bg-gradient-to-br from-gold-500 to-gold-600 rounded-lg flex items-center justify-center mb-6 mx-6">
                        <i class="fas <?php echo $service['icon']; ?> text-3xl text-white"></i>
                    </div>
                    <?php endif; ?>
                    <h3 class="text-2xl font-bold mb-4"><?php echo $service['name']; ?></h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6"><?php echo $service['description']; ?></p>
                    
                    <?php if ($service['price_type'] !== 'on_request'): ?>
                    <div class="text-3xl font-bold text-gold-600 dark:text-gold-500 mb-6 flex items-center gap-2">
                        <span class="text-sm font-normal text-gray-500"><?php echo t('starting_from'); ?></span>
                        <span dir="ltr"><?php echo displayPrice($service['price'], $service['price_euro'] ?? null); ?></span>
                    </div>
                    <?php else: ?>
                    <div class="text-lg font-semibold text-gray-600 dark:text-gray-400 mb-6">
                        <?php echo t('on_request'); ?>
                    </div>
                    <?php endif; ?>
                    
                    <a href="<?php echo SITE_URL; ?>/booking.php?service=<?php echo $service['id']; ?>" 
                       class="block text-center px-6 py-3 bg-gradient-to-r from-gold-500 to-gold-600 text-white rounded-lg hover:from-gold-600 hover:to-gold-700 transition-all">
                        <?php echo t('book_this_service'); ?>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Why Choose This Service -->
<section class="py-20">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">
                <?php echo t('why_choose_us'); ?>
            </h2>
            <div class="w-24 h-1 bg-gradient-to-r from-gold-500 to-gold-600 mx-auto"></div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php
            $features = [
                ['icon' => 'fa-shield-alt', 'title' => $lang === 'ar' ? 'السلامة أولاً' : 'Sécurité d\'abord', 'desc' => $lang === 'ar' ? 'سائقون محترفون ومركبات آمنة' : 'Chauffeurs professionnels et véhicules sûrs'],
                ['icon' => 'fa-clock', 'title' => $lang === 'ar' ? 'دقة المواعيد' : 'Ponctualité', 'desc' => $lang === 'ar' ? 'نصل في الوقت المحدد دائماً' : 'Nous arrivons toujours à l\'heure'],
                ['icon' => 'fa-dollar-sign', 'title' => $lang === 'ar' ? 'أسعار تنافسية' : 'Prix compétitifs', 'desc' => $lang === 'ar' ? 'أفضل الأسعار في السوق' : 'Les meilleurs prix du marché'],
                ['icon' => 'fa-headset', 'title' => $lang === 'ar' ? 'دعم 24/7' : 'Support 24/7', 'desc' => $lang === 'ar' ? 'خدمة عملاء على مدار الساعة' : 'Service client disponible 24h/24'],
            ];
            
            foreach ($features as $feature):
            ?>
            <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-lg text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-gold-500 to-gold-600 rounded-full flex items-center justify-center mb-4 mx-auto">
                    <i class="fas <?php echo $feature['icon']; ?> text-2xl text-white"></i>
                </div>
                <h3 class="text-xl font-bold mb-2"><?php echo $feature['title']; ?></h3>
                <p class="text-gray-600 dark:text-gray-400"><?php echo $feature['desc']; ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

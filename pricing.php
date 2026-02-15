<?php include __DIR__ . '/includes/header.php'; ?>

<section class="py-20 bg-gray-50 dark:bg-gray-800">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-bold mb-4"><?php echo t('pricing'); ?></h1>
            <div class="w-24 h-1 bg-gradient-to-r from-gold-500 to-gold-600 mx-auto mb-4"></div>
            <p class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                <?php echo $lang === 'ar' ? 'أسعار واضحة وشفافة لجميع وجهاتك' : 'Prix clairs et transparents pour toutes vos destinations'; ?>
            </p>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <?php
            $pricing_query = "SELECT from_location_{$lang} as from_location, to_location_{$lang} as to_location, 
                             distance_km as distance, duration, price, price_euro, service_id 
                             FROM pricing WHERE active = TRUE ORDER BY id DESC";
            $pricing_stmt = $db->prepare($pricing_query);
            $pricing_stmt->execute();
            $pricing_items = $pricing_stmt->fetchAll();
            
            foreach ($pricing_items as $item):
            ?>
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg p-6 hover:shadow-2xl transition-all">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-4">
                            <i class="fas fa-map-marker-alt text-gold-500 text-xl"></i>
                            <div>
                                <p class="text-gray-500 text-sm"><?php echo t('from'); ?></p>
                                <p class="font-semibold text-lg"><?php echo $item['from_location']; ?></p>
                            </div>
                        </div>
                        <div class="pe-8 mb-4">
                            <i class="fas fa-arrow-down text-gray-400 text-xl"></i>
                        </div>
                        <div class="flex items-center gap-3 mt-4">
                            <i class="fas fa-map-marker-alt text-gold-600 text-xl"></i>
                            <div>
                                <p class="text-gray-500 text-sm"><?php echo t('to'); ?></p>
                                <p class="font-semibold text-lg"><?php echo $item['to_location']; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="text-center border-l border-gray-200 dark:border-gray-700 pl-6 ml-6">
                        <p class="text-3xl font-bold text-gold-600 dark:text-gold-500" dir="ltr">
                            <?php echo displayPrice($item['price'], $item['price_euro'] ?? null); ?>
                        </p>
                        <div class="flex flex-col gap-2 mt-4 text-sm text-gray-500">
                             <div class="flex items-center justify-center gap-2 bg-gray-50 dark:bg-gray-800 py-1 px-3 rounded-full border border-gray-100 dark:border-gray-700">
                                <i class="fas fa-road text-gold-500"></i>
                                <span dir="ltr"><?php echo $item['distance']; ?> km</span>
                            </div>
                            <?php if(!empty($item['duration'])): ?>
                            <div class="flex items-center justify-center gap-2 bg-gray-50 dark:bg-gray-800 py-1 px-3 rounded-full border border-gray-100 dark:border-gray-700">
                                <i class="fas fa-clock text-gold-500"></i>
                                <span><?php echo $item['duration']; ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="<?php echo SITE_URL; ?>/booking.php?price=<?php echo $item['price']; ?>&from=<?php echo urlencode($item['from_location']); ?>&to=<?php echo urlencode($item['to_location']); ?>&service=<?php echo $item['service_id']; ?>" 
                       class="block text-center px-6 py-3 bg-gradient-to-r from-gold-500 to-gold-600 text-white rounded-lg hover:from-gold-600 hover:to-gold-700 transition-all">
                        <i class="fas fa-calendar-check mr-2"></i> <?php echo t('book_now'); ?>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Pricing Notice -->
<section class="py-20">
    <div class="container mx-auto px-4">
        <div class="bg-gradient-to-r from-gold-500 to-gold-600 rounded-2xl shadow-2xl p-8 md:p-12 text-white text-center">
            <i class="fas fa-info-circle text-5xl mb-4"></i>
            <h2 class="text-2xl md:text-3xl font-bold mb-4">
                <?php echo $lang === 'ar' ? 'معلومات هامة' : 'Informations importantes'; ?>
            </h2>
            <p class="text-lg mb-6 max-w-3xl mx-auto">
                <?php echo $lang === 'ar' ? 
                    'الأسعار المذكورة تقديرية وقد تختلف حسب حالة الطريق والوقت. للحصول على سعر دقيق، يرجى الاتصال بنا أو استخدام نموذج الحجز.' : 
                    'Les prix indiqués sont approximatifs et peuvent varier selon l\'état de la route et l\'heure. Pour un prix exact, veuillez nous contacter ou utiliser le formulaire de réservation.'; 
                ?>
            </p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="<?php echo SITE_URL; ?>/booking.php" class="px-8 py-3 bg-white text-gold-600 rounded-lg hover:bg-gray-100 transition-all font-semibold">
                    <?php echo t('book_now'); ?>
                </a>
                <a href="<?php echo SITE_URL; ?>/contact.php" class="px-8 py-3 bg-transparent border-2 border-white text-white rounded-lg hover:bg-white hover:text-gold-600 transition-all font-semibold">
                    <?php echo t('contact_us'); ?>
                </a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

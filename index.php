<?php include __DIR__ . '/includes/header.php'; ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "TaxiService",
  "name": "<?php echo $settings_array['company_name_' . $lang] ?? 'TAXI ORAN'; ?>",
  "image": "<?php echo SITE_URL; ?>/logo/logo.png",
  "@id": "<?php echo SITE_URL; ?>",
  "url": "<?php echo SITE_URL; ?>",
  "telephone": "<?php echo $settings_array['company_phone']; ?>",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "Oran Center",
    "addressLocality": "Oran",
    "postalCode": "31000",
    "addressCountry": "DZ"
  },
  "geo": {
    "@type": "GeoCoordinates",
    "latitude": 35.697654,
    "longitude": -0.633737
  },
  "openingHoursSpecification": {
    "@type": "OpeningHoursSpecification",
    "dayOfWeek": [
      "Monday",
      "Tuesday",
      "Wednesday",
      "Thursday",
      "Friday",
      "Saturday",
      "Sunday"
    ],
    "opens": "00:00",
    "closes": "23:59"
  },
  "priceRange": "$$"
}
</script>

<!-- Hero Section -->
<section class="relative min-h-[600px] flex items-center overflow-hidden">
    <!-- Video Background -->
    <video autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover">
        <source src="./logo/taxii.mp4" type="video/mp4">
        <source src="./logo/taxii.webm" type="video/webm">
        <!-- Fallback gradient if video doesn't load -->
    </video>
    
    <!-- Dark Overlay (ظل خفيف) -->
    <div class="absolute inset-0 bg-gradient-to-br from-black/70 via-black/60 to-black/70 dark:from-black/80 dark:via-black/70 dark:to-black/80"></div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-4xl">
            <h1 class="hero-main-title text-4xl md:text-6xl mb-6 leading-tight">
                <?php echo $settings_array['hero_title_' . $lang] ?? t('hero_title'); ?>
            </h1>
            
            <style>
                .hero-main-title {
                    font-family: 'Al Yamama Black', 'Arial Black', sans-serif;
                    font-weight: 900;
                    color: #feda15;
                    text-shadow: 
                        2px 2px 4px rgba(0, 0, 0, 0.3),
                        0 0 20px rgba(254, 218, 21, 0.6);
                }
            </style>
            <p class="hero-subtitle text-xl md:text-2xl mb-8">
                <?php 
                $subtitle = $settings_array['hero_subtitle_' . $lang] ?? t('hero_subtitle');
                
                // تلوين كلمات محددة في النص العربي
                if ($lang === 'ar') {
                    $subtitle = str_replace('احترافية', '<span class="highlight-word">احترافية</span>', $subtitle);
                    $subtitle = str_replace('موثوقة', '<span class="highlight-word">موثوقة</span>', $subtitle);
                } else {
                    $subtitle = str_replace('professionnelle', '<span class="highlight-word">professionnelle</span>', $subtitle);
                    $subtitle = str_replace('fiable', '<span class="highlight-word">fiable</span>', $subtitle);
                }
                
                echo $subtitle;
                ?>
            </p>
            
            <style>
                .hero-subtitle {
                    color: #E5E7EB;
                }
                
                /* تأثير على الكلمات المميزة فقط */
                .highlight-word {
                    background: linear-gradient(135deg, 
                        #feda15 0%, 
                        #fff3b3 50%, 
                        #feda15 100%
                    );
                    background-size: 200% auto;
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                    background-clip: text;
                    animation: shimmer 3s linear infinite;
                    font-weight: 700;
                    position: relative;
                    display: inline-block;
                    padding: 0 4px;
                }
                
                @keyframes shimmer {
                    0% { background-position: 0% center; }
                    100% { background-position: 200% center; }
                }
                
                /* خط ذهبي متحرك */
                .highlight-word::after {
                    content: '';
                    position: absolute;
                    bottom: 0px;
                    left: 0;
                    width: 0%;
                    height: 2px;
                    background: linear-gradient(90deg, #feda15, #fff3b3);
                    animation: lineGrow 2s ease-in-out infinite;
                }
                
                @keyframes lineGrow {
                    0%, 100% { width: 0%; }
                    50% { width: 100%; }
                }
            </style>
            <div class="flex flex-wrap gap-4">
                <a href="<?php echo SITE_URL; ?>/booking.php" class="px-8 py-4 bg-gradient-to-r from-gold-500 to-gold-600 text-white rounded-lg hover:from-gold-600 hover:to-gold-700 transition-all shadow-lg hover:shadow-2xl hover:scale-105 font-semibold">
                    <i class="fas fa-taxi mr-2"></i> <?php echo t('book_now'); ?>
                </a>
                <a href="<?php echo SITE_URL; ?>/services.php" class="px-8 py-4 bg-white/10 backdrop-blur-sm text-white rounded-lg hover:bg-white/20 transition-all border border-white/20 font-semibold">
                    <?php echo t('our_services'); ?>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Decorative Elements -->
    <div class="absolute top-20 right-20 w-72 h-72 bg-gold-500/20 rounded-full blur-3xl"></div>
    <div class="absolute bottom-20 left-20 w-96 h-96 bg-gold-600/10 rounded-full blur-3xl"></div>
</section>

<!-- Features/Why Choose Us -->
<section class="py-20 bg-gray-50 dark:bg-gray-800">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo t('why_choose_us'); ?></h2>
            <div class="w-24 h-1 bg-gradient-to-r from-gold-500 to-gold-600 mx-auto"></div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php
            $features = [
                ['icon' => 'fa-user-tie', 'title_key' => 'why_us_1_title_', 'desc_key' => 'why_us_1_desc_'],
                ['icon' => 'fa-clock', 'title_key' => 'why_us_2_title_', 'desc_key' => 'why_us_2_desc_'],
                ['icon' => 'fa-tags', 'title_key' => 'why_us_3_title_', 'desc_key' => 'why_us_3_desc_'],
                ['icon' => 'fa-car', 'title_key' => 'why_us_4_title_', 'desc_key' => 'why_us_4_desc_'],
            ];
            
            foreach ($features as $feature):
                $title = $settings_array[$feature['title_key'] . $lang] ?? '';
                $desc = $settings_array[$feature['desc_key'] . $lang] ?? '';
            ?>
            <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-lg hover:shadow-2xl transition-all hover:-translate-y-2">
                <div class="w-16 h-16 bg-gradient-to-br from-gold-500 to-gold-600 rounded-full flex items-center justify-center mb-4">
                    <i class="fas <?php echo $feature['icon']; ?> text-2xl text-white"></i>
                </div>
                <h3 class="text-xl font-bold mb-2"><?php echo $title; ?></h3>
                <p class="text-gray-600 dark:text-gray-400"><?php echo $desc; ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="py-20">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo t('our_services'); ?></h2>
            <div class="w-24 h-1 bg-gradient-to-r from-gold-500 to-gold-600 mx-auto"></div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            $services_query = "SELECT id, name_{$lang} as name, description_{$lang} as description, price, price_euro, price_type, icon, image 
                              FROM services WHERE active = TRUE ORDER BY display_order ASC LIMIT 6";
            $services_stmt = $db->prepare($services_query);
            $services_stmt->execute();
            $services = $services_stmt->fetchAll();
            
            foreach ($services as $service):
            ?>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all">
                    <div class="h-48 overflow-hidden relative">
                        <?php if (!empty($service['image'])): ?>
                        <img src="<?php echo htmlspecialchars($service['image']); ?>" alt="<?php echo htmlspecialchars($service['name']); ?>" class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">
                        <?php else: ?>
                        <div class="w-full h-full bg-gold-500 flex items-center justify-center">
                            <?php if (!empty($service['icon'])): ?>
                                <i class="fas <?php echo $service['icon']; ?> text-4xl text-white"></i>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($service['icon'])): ?>
                        <div class="absolute top-4 right-4 bg-gold-500 backdrop-blur-sm p-2 rounded-lg shadow-sm">
                            <i class="fas <?php echo $service['icon']; ?> text-white text-xl"></i>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="p-6">
                    <h3 class="text-xl font-bold mb-2"><?php echo $service['name']; ?></h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-2"><?php echo $service['description']; ?></p>
                    
                    <?php if ($service['price_type'] !== 'on_request'): ?>
                    <div class="text-2xl font-bold text-gold-600 dark:text-gold-500 mb-4 flex items-center gap-2">
                        <span class="text-sm font-normal text-gray-500"><?php echo t('starting_from'); ?></span>
                        <span dir="ltr"><?php echo displayPrice($service['price'], $service['price_euro'] ?? null); ?></span>
                    </div>
                    <?php else: ?>
                    <div class="text-lg font-semibold text-gray-600 dark:text-gray-400 mb-4">
                        <?php echo t('on_request'); ?>
                    </div>
                    <?php endif; ?>
                    
                    <a href="<?php echo SITE_URL; ?>/booking.php?service=<?php echo $service['id']; ?>" class="block text-center px-6 py-3 bg-gradient-to-r from-gold-500 to-gold-600 text-white rounded-lg hover:from-gold-600 hover:to-gold-700 transition-all">
                        <?php echo t('book_this_service'); ?>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-12">
            <a href="<?php echo SITE_URL; ?>/services.php" class="inline-block px-8 py-4 border-2 border-gold-500 text-gold-600 dark:text-white rounded-lg hover:bg-gold-500 hover:text-white transition-all font-semibold">
                <?php echo t('view_all_services'); ?> <i class="fas fa-arrow-<?php echo $lang === 'ar' ? 'left' : 'right'; ?> ml-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="py-20 bg-gray-50 dark:bg-gray-800">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo t('what_clients_say'); ?></h2>
            <div class="w-24 h-1 bg-gradient-to-r from-gold-500 to-gold-600 mx-auto"></div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            $name_field = $lang === 'ar' ? 'name_ar' : 'name_fr';
            $role_field = $lang === 'ar' ? 'role_ar' : 'role_fr';
            $content_field = $lang === 'ar' ? 'content_ar' : 'content_fr';
            $testimonials_query = "SELECT {$name_field} as name, {$role_field} as position, rating, {$content_field} as review 
                                  FROM testimonials WHERE active = TRUE ORDER BY created_at DESC LIMIT 6";
            $testimonials_stmt = $db->prepare($testimonials_query);
            $testimonials_stmt->execute();
            $testimonials = $testimonials_stmt->fetchAll();
            
            foreach ($testimonials as $testimonial):
            ?>
            <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-lg">
                <div class="flex items-center mb-4">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                    <i class="fas fa-star <?php echo $i <= $testimonial['rating'] ? 'text-gold-500' : 'text-gray-300'; ?>"></i>
                    <?php endfor; ?>
                </div>
                <p class="text-gray-600 dark:text-gray-400 mb-4 italic">"<?php echo $testimonial['review']; ?>"</p>
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-gold-500 to-gold-600 rounded-full flex items-center justify-center text-white font-bold rtl:ml-3 ltr:mr-3">
                        <?php echo mb_substr($testimonial['name'], 0, 1); ?>
                    </div>
                    <div>
                        <div class="font-semibold"><?php echo $testimonial['name']; ?></div>
                        <div class="text-sm text-gray-500"><?php echo $testimonial['position']; ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-20 bg-gradient-to-r from-gold-500 to-gold-600">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
            <?php echo $lang === 'ar' ? 'جاهز للانطلاق؟' : 'Prêt à partir?'; ?>
        </h2>
        <p class="text-xl text-white/90 mb-8">
            <?php echo $lang === 'ar' ? 'احجز تاكسيك الآن واستمتع برحلة مريحة وآمنة' : 'Réservez votre taxi maintenant et profitez d\'un voyage confortable et sûr'; ?>
        </p>
        <div class="flex flex-wrap gap-4 justify-center">
            <a href="<?php echo SITE_URL; ?>/booking.php" class="px-8 py-4 bg-white text-gold-600 rounded-lg hover:bg-gray-100 transition-all shadow-lg hover:shadow-2xl font-semibold">
                <i class="fas fa-taxi mr-2"></i> <?php echo t('book_now'); ?>
            </a>
            <a href="tel:<?php echo $settings_array['company_phone']; ?>" class="px-8 py-4 bg-transparent border-2 border-white text-white rounded-lg hover:bg-white hover:text-gold-600 transition-all font-semibold">
                <i class="fas fa-phone mr-2"></i> <?php echo t('phone'); ?>
            </a>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

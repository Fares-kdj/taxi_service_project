<?php include __DIR__ . '/includes/header.php'; ?>

<section class="py-20 bg-gradient-to-br from-gray-900 to-gray-800 text-white">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">
                <?php echo $lang === 'ar' ? 'من نحن' : 'À propos de nous';?>
            </h1>
            <p class="text-xl text-gray-300">
                <?php echo $lang === 'ar' ? 
                    'نحن فريق محترف من السائقين والمتخصصين في خدمات النقل، ملتزمون بتوفير تجربة سفر آمنة ومريحة' :
                    'Nous sommes une équipe professionnelle de chauffeurs et spécialistes des services de transport, engagés à fournir une expérience de voyage sûre et confortable';
                ?>
            </p>
        </div>
    </div>
</section>

<!-- Our Story -->
<section class="py-20">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-3xl font-bold mb-6">
                    <?php echo $lang === 'ar' ? 'قصتنا' : 'Notre histoire'; ?>
                </h2>
                <p class="text-gray-600 dark:text-gray-400 mb-4 leading-relaxed">
                    <?php echo $lang === 'ar' ? 
                        'تأسست شركة فرسان الطريق بهدف تقديم خدمات نقل متميزة ومريحة للركاب. منذ انطلاقتنا، كنا ملتزمين بتوفير أعلى معايير الجودة والسلامة في خدمات النقل.' :
                        'Chevaliers de la Route a été fondée dans le but de fournir des services de transport exceptionnels et confortables aux passagers. Depuis notre lancement, nous nous sommes engagés à fournir les plus hauts standards de qualité et de sécurité dans les services de transport.';
                    ?>
                </p>
                <p class="text-gray-600 dark:text-gray-400 mb-4 leading-relaxed">
                    <?php echo $lang === 'ar' ? 
                        'نحن نؤمن بأن رحلة كل عميل يجب أن تكون تجربة مميزة، لذلك نعمل باستمرار على تحسين خدماتنا وتدريب فريقنا ليكون في أفضل حالاته.' :
                        'Nous croyons que le voyage de chaque client doit être une expérience exceptionnelle, c\'est pourquoi nous travaillons continuellement à améliorer nos services et à former notre équipe pour qu\'elle soit à son meilleur.';
                    ?>
                </p>
            </div>
            <div class="relative">
                <div class="bg-gradient-to-br from-gold-500 to-gold-600 rounded-2xl p-1">
                    <div class="bg-white dark:bg-gray-900 rounded-2xl p-8">
                        <div class="grid grid-cols-2 gap-6 text-center">
                            <div>
                                <div class="text-4xl font-bold text-gold-600 mb-2">10+</div>
                                <div class="text-gray-600 dark:text-gray-400"><?php echo $lang === 'ar' ? 'سنوات خبرة' : 'Ans d\'expérience'; ?></div>
                            </div>
                            <div>
                                <div class="text-4xl font-bold text-gold-600 mb-2">50+</div>
                                <div class="text-gray-600 dark:text-gray-400"><?php echo $lang === 'ar' ? 'سائق محترف' : 'Chauffeurs pros'; ?></div>
                            </div>
                            <div>
                                <div class="text-4xl font-bold text-gold-600 mb-2">1000+</div>
                                <div class="text-gray-600 dark:text-gray-400"><?php echo $lang === 'ar' ? 'عميل راضٍ' : 'Clients satisfaits'; ?></div>
                            </div>
                            <div>
                                <div class="text-4xl font-bold text-gold-600 mb-2">24/7</div>
                                <div class="text-gray-600 dark:text-gray-400"><?php echo $lang === 'ar' ? 'خدمة متواصلة' : 'Service continu'; ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Values -->
<section class="py-20 bg-gray-50 dark:bg-gray-800">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">
                <?php echo $lang === 'ar' ? 'قيمنا' : 'Nos valeurs'; ?>
            </h2>
            <div class="w-24 h-1 bg-gradient-to-r from-gold-500 to-gold-600 mx-auto"></div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php
            $values = [
                [
                    'icon' => 'fa-shield-alt',
                    'title_ar' => 'السلامة',
                    'title_fr' => 'Sécurité',
                    'desc_ar' => 'نضع سلامة عملائنا في المقام الأول مع سائقين مدربين ومركبات آمنة',
                    'desc_fr' => 'Nous plaçons la sécurité de nos clients au premier plan avec des chauffeurs formés et des véhicules sûrs'
                ],
                [
                    'icon' => 'fa-handshake',
                    'title_ar' => 'الاحترافية',
                    'title_fr' => 'Professionnalisme',
                    'desc_ar' => 'نقدم خدمة احترافية عالية الجودة في كل رحلة',
                    'desc_fr' => 'Nous fournissons un service professionnel de haute qualité à chaque voyage'
                ],
                [
                    'icon' => 'fa-heart',
                    'title_ar' => 'رضا العملاء',
                    'title_fr' => 'Satisfaction client',
                    'desc_ar' => 'نسعى دائماً لتجاوز توقعات عملائنا وضمان رضاهم التام',
                    'desc_fr' => 'Nous nous efforçons toujours de dépasser les attentes de nos clients et d\'assurer leur satisfaction totale'
                ]
            ];
            
            foreach ($values as $value):
            ?>
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg p-8 text-center hover:shadow-2xl transition-all hover:-translate-y-2">
                <div class="w-20 h-20 bg-gradient-to-br from-gold-500 to-gold-600 rounded-full flex items-center justify-center mb-6 mx-auto">
                    <i class="fas <?php echo $value['icon']; ?> text-3xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4">
                    <?php echo $lang === 'ar' ? $value['title_ar'] : $value['title_fr']; ?>
                </h3>
                <p class="text-gray-600 dark:text-gray-400">
                    <?php echo $lang === 'ar' ? $value['desc_ar'] : $value['desc_fr']; ?>
                </p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="py-20">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">
                <?php echo $lang === 'ar' ? 'فريقنا' : 'Notre équipe'; ?>
            </h2>
            <div class="w-24 h-1 bg-gradient-to-r from-gold-500 to-gold-600 mx-auto mb-4"></div>
            <p class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                <?php echo $lang === 'ar' ? 
                    'فريق من المحترفين المتفانين في خدمة عملائنا' :
                    'Une équipe de professionnels dévoués au service de nos clients';
                ?>
            </p>
        </div>
        
        <div class="text-center">
            <p class="text-gray-600 dark:text-gray-400 mb-8">
                <?php echo $lang === 'ar' ? 
                    'سائقونا مدربون على أعلى مستوى ويمتلكون خبرة واسعة في مجال النقل' :
                    'Nos chauffeurs sont formés au plus haut niveau et possèdent une vaste expérience dans le domaine du transport';
                ?>
            </p>
            <a href="<?php echo SITE_URL; ?>/booking.php" 
               class="inline-block px-8 py-4 bg-gradient-to-r from-gold-500 to-gold-600 text-white rounded-lg hover:from-gold-600 hover:to-gold-700 transition-all shadow-lg font-semibold">
                <i class="fas fa-taxi mr-2"></i> <?php echo t('book_now'); ?>
            </a>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

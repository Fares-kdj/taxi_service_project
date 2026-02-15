<?php
/**
 * Header Component
 * فرسان الطريق / Chevaliers de la Route
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/settings.php';
require_once __DIR__ . '/../lang/translate.php';
require_once __DIR__ . '/functions.php';

// Get site settings
$database = new Database();
$db = $database->getConnection();

$query = "SELECT setting_key, setting_value FROM site_settings";
$stmt = $db->prepare($query);
$stmt->execute();
$settings_array = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

$lang = getLang();
$dir = isRTL() ? 'rtl' : 'ltr';
$company_name = $lang === 'ar' ? ($settings_array['company_name_ar'] ?? 'فرسان الطريق') : ($settings_array['company_name_fr'] ?? 'Chevaliers de la Route');
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" dir="<?php echo $dir; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="<?php echo SITE_URL; ?>/logo/favicon.ico">
    <!-- Mobile App Icons -->
    <link rel="icon" type="image/png" sizes="192x192" href="<?php echo SITE_URL; ?>/logo/logo_android.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo SITE_URL; ?>/logo/logo_android.png">
    <meta name="description" content="<?php echo $settings_array['seo_description_' . $lang] ?? ''; ?>">
    <meta name="keywords" content="<?php echo $settings_array['seo_keywords'] ?? ''; ?>">
    <title><?php echo $settings_array['seo_title_' . $lang] ?? $company_name; ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        gold: {
                            50: '#fffef5',
                            100: '#fffced',
                            200: '#fff8d1',
                            300: '#fff3b3',
                            400: '#ffed66',
                            500: '#feda15',
                            600: '#e6c000',
                            700: '#c7a200',
                            800: '#a88600',
                            900: '#8a6d00',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Flag Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php if ($lang === 'ar'): ?>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Cairo', 'Segoe UI Emoji', 'Noto Color Emoji', sans-serif; }
    </style>
    <?php else: ?>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
    <?php endif; ?>
    
    <!-- Custom Styles -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/css/style.css">
</head>
<body class="bg-white dark:bg-gray-900 text-gray-900 dark:text-white transition-colors duration-300">
    
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/95 dark:bg-gray-900/95 backdrop-blur-sm shadow-md transition-all duration-300">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-20">
                
                <!-- Logo -->
                <a href="<?php echo SITE_URL; ?>/index.php" class="flex items-center space-x-3 rtl:space-x-reverse">
                    <?php if (file_exists(__DIR__ . '/../logo/logo.png')): ?>
                        <img src="<?php echo SITE_URL; ?>/logo/logo.png" alt="<?php echo $company_name; ?>" class="h-16 w-auto block dark:hidden">
                        <img src="<?php echo SITE_URL; ?>/logo/white_logo.png" alt="<?php echo $company_name; ?>" class="h-[4.25rem] w-auto hidden dark:block">
                    <?php else: ?>
                        <i class="fas fa-taxi text-3xl text-gold-500"></i>
                    <?php endif; ?>
                    <span class="text-xl font-bold bg-gradient-to-r from-gold-500 to-gold-600 bg-clip-text text-transparent dark:text-white dark:bg-none">
                        <?php echo $company_name; ?>
                    </span>
                </a>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8 rtl:space-x-reverse">
                    <a href="<?php echo SITE_URL; ?>/index.php" class="nav-link hover:text-gold-500 transition-colors">
                        <?php echo t('home'); ?>
                    </a>
                    <a href="<?php echo SITE_URL; ?>/services.php" class="nav-link hover:text-gold-500 transition-colors">
                        <?php echo t('services'); ?>
                    </a>
                    <a href="<?php echo SITE_URL; ?>/pricing.php" class="nav-link hover:text-gold-500 transition-colors">
                        <?php echo t('pricing'); ?>
                    </a>
                    <a href="<?php echo SITE_URL; ?>/about.php" class="nav-link hover:text-gold-500 transition-colors">
                        <?php echo t('about'); ?>
                    </a>
                    <a href="<?php echo SITE_URL; ?>/contact.php" class="nav-link hover:text-gold-500 transition-colors">
                        <?php echo t('contact'); ?>
                    </a>
                </div>
                
                <!-- Right Side: Language, Theme, Booking -->
                <div class="hidden md:flex items-center space-x-4 rtl:space-x-reverse">
                    <!-- Language Switcher -->
                    <button onclick="switchLanguage()" class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <span class="flag-icon flag-icon-<?php echo $lang === 'ar' ? 'fr' : 'dz'; ?> text-xl shadow-sm rounded-sm"></span>
                        <span class="text-sm font-bold"><?php echo $lang === 'ar' ? 'FR' : 'AR'; ?></span>
                    </button>
                    
                    <!-- Theme Toggle -->
                    <button onclick="toggleTheme()" id="theme-toggle" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <i class="fas fa-moon dark:hidden text-xl"></i>
                        <i class="fas fa-sun hidden dark:inline text-xl"></i>
                    </button>
                    
                    <!-- Book Now Button -->
                    <a href="<?php echo SITE_URL; ?>/booking.php" class="px-6 py-2.5 bg-gradient-to-r from-gold-500 to-gold-600 text-white rounded-lg hover:from-gold-600 hover:to-gold-700 transition-all shadow-lg hover:shadow-xl">
                        <?php echo t('book_now'); ?>
                    </a>
                </div>
                
                <!-- Mobile Menu Button -->
                <button class="md:hidden p-2" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
            <div class="container mx-auto px-4 py-4 space-y-3">
                <a href="<?php echo SITE_URL; ?>/index.php" class="block py-2 hover:text-gold-500 transition-colors">
                    <?php echo t('home'); ?>
                </a>
                <a href="<?php echo SITE_URL; ?>/services.php" class="block py-2 hover:text-gold-500 transition-colors">
                    <?php echo t('services'); ?>
                </a>
                <a href="<?php echo SITE_URL; ?>/pricing.php" class="block py-2 hover:text-gold-500 transition-colors">
                    <?php echo t('pricing'); ?>
                </a>
                <a href="<?php echo SITE_URL; ?>/about.php" class="block py-2 hover:text-gold-500 transition-colors">
                    <?php echo t('about'); ?>
                </a>
                <a href="<?php echo SITE_URL; ?>/contact.php" class="block py-2 hover:text-gold-500 transition-colors">
                    <?php echo t('contact'); ?>
                </a>
                <div class="flex items-center space-x-4 rtl:space-x-reverse pt-4">
                    <button onclick="switchLanguage()" class="flex items-center gap-3 px-4 py-2 bg-gray-100 dark:bg-gray-800 rounded-lg">
                        <span class="flag-icon flag-icon-<?php echo $lang === 'ar' ? 'fr' : 'dz'; ?> text-xl shadow-sm rounded-sm"></span>
                        <span class="font-bold"><?php echo $lang === 'ar' ? 'FR' : 'AR'; ?></span>
                    </button>
                    <button onclick="toggleTheme()" class="px-4 py-2 bg-gray-100 dark:bg-gray-800 rounded-lg">
                        <i class="fas fa-moon dark:hidden"></i>
                        <i class="fas fa-sun hidden dark:inline"></i>
                    </button>
                </div>
                <a href="<?php echo SITE_URL; ?>/booking.php" class="block mt-4 px-6 py-3 bg-gradient-to-r from-gold-500 to-gold-600 text-white text-center rounded-lg">
                    <?php echo t('book_now'); ?>
                </a>
            </div>
        </div>
    </nav>
    
    <!-- Spacer for fixed nav -->
    <div class="h-20"></div>

    <script>
        function toggleMobileMenu() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        }

        function switchLanguage() {
            const currentLang = '<?php echo $lang; ?>';
            const newLang = currentLang === 'ar' ? 'fr' : 'ar';
            
            // Create form data
            const formData = new FormData();
            formData.append('lang', newLang);
            
            fetch('<?php echo SITE_URL; ?>/handlers/language-handler.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    location.reload();
                } else {
                    console.error('Language switch failed');
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Theme Toggle Logic
        // Check local storage or system preference
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        function toggleTheme() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            }
        }
    </script>

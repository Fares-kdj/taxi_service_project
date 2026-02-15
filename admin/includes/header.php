<?php
require_once __DIR__ . '/../handlers/auth-handler.php';
checkAdminAuth();

// Quick fix for session name persistence
if (isset($_SESSION['admin_name']) && ($_SESSION['admin_name'] === 'Administrator' || $_SESSION['admin_name'] === 'Admin' || $_SESSION['admin_name'] === 'Adel')) {
    $_SESSION['admin_name'] = 'ADEL';
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../logo/favicon.ico">
    <!-- Mobile App Icons -->
    <link rel="icon" type="image/png" sizes="192x192" href="../logo/logo_android.png">
    <link rel="apple-touch-icon" sizes="180x180" href="../logo/logo_android.png">
    <title>لوحة التحكم - فرسان الطريق</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                        gold: {
                            500: '#fb9605',
                            600: '#df7101',
                        }
                    },
                    fontFamily: {
                        sans: ['Cairo', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Cairo', 'Segoe UI Emoji', 'Noto Color Emoji', sans-serif; }
        .glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
        /* Hide scrollbar for Chrome, Safari and Opera */
        ::-webkit-scrollbar {
            display: none;
        }
        /* Hide scrollbar for IE, Edge and Firefox */
        html, body, nav, main, aside {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
    </style>
    <script>
        function toggleSidebar() {
            // Implementation for mobile sidebar toggle
            // This assumes sidebar.php has a sidebar element with specific ID or class
            // For now, let's just log or add a simple toggle if the sidebar has an ID
            const sidebar = document.querySelector('aside');
            if(sidebar) {
                 sidebar.classList.toggle('hidden');
                 sidebar.classList.toggle('lg:flex');
                 // This depends on how sidebar.php is structured
            }
        }
    </script>
</head>
<body class="bg-gray-50 text-gray-800">

<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <?php include __DIR__ . '/sidebar.php'; ?>
    
    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Header -->
        <header class="bg-white shadow-sm h-16 flex items-center justify-between px-6 z-10">
            <div class="flex items-center">
                <button onclick="toggleSidebar()" class="text-gray-500 hover:text-gray-700 focus:outline-none lg:hidden">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-semibold text-gray-700"><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'ADEL'); ?></span>
                    <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center text-primary-700">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Page Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">

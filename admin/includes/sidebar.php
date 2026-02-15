<aside id="sidebar" class="bg-gray-900 text-white w-64 flex-shrink-0 hidden lg:flex flex-col transition-all duration-300">
    <div class="h-24 flex items-center justify-center border-b border-gray-800">
        <div class="flex flex-col items-center justify-center w-full h-full py-4 relative">
            <button onclick="toggleSidebar()" class="lg:hidden absolute top-4 left-4 text-gray-400 hover:text-white transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
            <img src="../logo/white_logo.png" alt="Logo" class="max-h-12 w-auto object-contain mb-2">
            <span class="text-sm font-bold text-gray-400">لوحة التحكم</span>
        </div>
    </div>
    
    <nav class="flex-1 overflow-y-auto py-4">
        <ul class="space-y-1">
            <li>
                <a href="/monespace/index" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'bg-gray-800 text-white border-r-4 border-gold-500' : ''; ?>">
                    <i class="fas fa-home w-6 text-center"></i>
                    <span class="mr-3">الرئيسية</span>
                </a>
            </li>
            
            <li class="px-6 pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase">إدارة العمليات</li>
            
            <li>
                <a href="/monespace/bookings" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'bookings.php' ? 'bg-gray-800 text-white border-r-4 border-gold-500' : ''; ?>">
                    <i class="fas fa-calendar-alt w-6 text-center"></i>
                    <span class="mr-3">الحجوزات</span>
                </a>
            </li>
            
            <li>
                <a href="/monespace/services" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'services.php' ? 'bg-gray-800 text-white border-r-4 border-gold-500' : ''; ?>">
                    <i class="fas fa-cogs w-6 text-center"></i>
                    <span class="mr-3">الخدمات</span>
                </a>
            </li>
            
            <li>
                <a href="/monespace/pricing" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'pricing.php' ? 'bg-gray-800 text-white border-r-4 border-gold-500' : ''; ?>">
                    <i class="fas fa-tags w-6 text-center"></i>
                    <span class="mr-3">الأسعار</span>
                </a>
            </li>

            <li>
                <a href="/monespace/reports" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'bg-gray-800 text-white border-r-4 border-gold-500' : ''; ?>">
                    <i class="fas fa-chart-pie w-6 text-center"></i>
                    <span class="mr-3">التقارير</span>
                </a>
            </li>
            
            <li class="px-6 pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase">إدارة الموقع</li>
            
            <li>
                <a href="/monespace/testimonials" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'testimonials.php' ? 'bg-gray-800 text-white border-r-4 border-gold-500' : ''; ?>">
                    <i class="fas fa-comments w-6 text-center"></i>
                    <span class="mr-3">آراء العملاء</span>
                </a>
            </li>
            
            <li>
                <a href="/monespace/settings" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'bg-gray-800 text-white border-r-4 border-gold-500' : ''; ?>">
                    <i class="fas fa-sliders-h w-6 text-center"></i>
                    <span class="mr-3">الإعدادات</span>
                </a>
            </li>

            <li>
                <a href="/monespace/users" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'users.php' ? 'bg-gray-800 text-white border-r-4 border-gold-500' : ''; ?>">
                    <i class="fas fa-users w-6 text-center"></i>
                    <span class="mr-3">المستخدمين</span>
                </a>
            </li>

            <li class="px-6 pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase">الحساب</li>

            <li>
                <a href="/monespace/logout" class="flex items-center px-6 py-3 text-red-400 hover:bg-gray-800 hover:text-red-300 transition-colors">
                    <i class="fas fa-sign-out-alt w-6 text-center"></i>
                    <span class="mr-3">تسجيل الخروج</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('hidden');
        sidebar.classList.toggle('flex');
        sidebar.classList.toggle('fixed');
        sidebar.classList.toggle('inset-0');
        sidebar.classList.toggle('z-50');
    }
</script>

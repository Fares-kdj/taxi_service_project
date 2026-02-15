<?php include __DIR__ . '/includes/header.php'; ?>

<?php
// Get quick stats
$db = new Database();
$conn = $db->getConnection();

// Bookings Today
$stmt = $conn->prepare("SELECT COUNT(*) FROM bookings WHERE DATE(created_at) = CURDATE()");
$stmt->execute();
$bookings_today = $stmt->fetchColumn();

// New Bookings (pending)
$stmt = $conn->prepare("SELECT COUNT(*) FROM bookings WHERE status = 'new'");
$stmt->execute();
$pending_bookings = $stmt->fetchColumn();

// Total Services
$stmt = $conn->prepare("SELECT COUNT(*) FROM services");
$stmt->execute();
$total_services = $stmt->fetchColumn();

// Recent Bookings
$stmt = $conn->query("SELECT * FROM bookings ORDER BY created_at DESC LIMIT 5");
$recent_bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-800">مرحباً بك، <?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'ADEL'); ?> 👋</h1>
    <p class="text-gray-500">هذه نظرة عامة على أداء الخدمة اليوم</p>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stat Card -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600">
                <i class="fas fa-calendar-day text-xl"></i>
            </div>
            <span class="text-sm font-medium text-green-500 flex items-center gap-1">
                واليوم
            </span>
        </div>
        <h3 class="text-3xl font-bold text-gray-800 mb-1"><?php echo $bookings_today; ?></h3>
        <p class="text-gray-500 text-sm">حجوزات اليوم</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center text-yellow-600">
                <i class="fas fa-clock text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold text-gray-800 mb-1"><?php echo $pending_bookings; ?></h3>
        <p class="text-gray-500 text-sm">في الانتظار</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center text-purple-600">
                <i class="fas fa-taxi text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold text-gray-800 mb-1"><?php echo $total_services; ?></h3>
        <p class="text-gray-500 text-sm">الخدمات النشطة</p>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center text-green-600">
                <i class="fas fa-check-circle text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold text-gray-800 mb-1">24/7</h3>
        <p class="text-gray-500 text-sm">حالة النظام</p>
    </div>
</div>

<!-- Recent Bookings & Quick Actions -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Recent Table -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-lg font-bold text-gray-800">أحدث الحجوزات</h2>
            <a href="bookings.php" class="text-sm text-gold-600 hover:text-gold-700">عرض الكل</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-right">
                <thead class="bg-gray-50 text-gray-500 text-sm">
                    <tr>
                        <th class="px-6 py-3 font-medium">العميل</th>
                        <th class="px-6 py-3 font-medium">المسار</th>
                        <th class="px-6 py-3 font-medium">التاريخ</th>
                        <th class="px-6 py-3 font-medium">الحالة</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach ($recent_bookings as $booking): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 text-xs">
                                    <i class="fas fa-user"></i>
                                </div>
                                <span class="font-medium text-gray-800"><?php echo htmlspecialchars($booking['customer_name']); ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <?php echo htmlspecialchars($booking['pickup_location']); ?>
                            <i class="fas fa-arrow-left mx-2 text-gray-400 text-xs"></i>
                            <?php echo htmlspecialchars($booking['destination']); ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            <?php echo date('Y/m/d', strtotime($booking['created_at'])); ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php
                            $status_colors = [
                                'new' => 'bg-yellow-100 text-yellow-700',
                                'confirmed' => 'bg-blue-100 text-blue-700',
                                'completed' => 'bg-green-100 text-green-700',
                                'cancelled' => 'bg-red-100 text-red-700'
                            ];
                            $status_labels = [
                                'new' => 'جديد',
                                'confirmed' => 'مؤكد',
                                'completed' => 'مكتمل',
                                'cancelled' => 'ملغى'
                            ];
                            $status = $booking['status'] ?? 'new';
                            $color = $status_colors[$status] ?? 'bg-gray-100 text-gray-700';
                            $label = $status_labels[$status] ?? $status;
                            ?>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold <?php echo $color; ?>">
                                <?php echo $label; ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">روابط سريعة</h2>
        <div class="grid grid-cols-1 gap-4">
            <a href="bookings.php" class="p-4 rounded-xl border border-gray-100 hover:border-gold-500 hover:bg-gold-50 transition-all group">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center group-hover:bg-gold-500 group-hover:text-white transition-colors">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">إدارة الحجوزات</h3>
                        <p class="text-sm text-gray-500">التحقق من الطلبات الجديدة</p>
                    </div>
                </div>
            </a>
            
            <a href="services.php" class="p-4 rounded-xl border border-gray-100 hover:border-gold-500 hover:bg-gold-50 transition-all group">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center group-hover:bg-gold-500 group-hover:text-white transition-colors">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">إضافة خدمة</h3>
                        <p class="text-sm text-gray-500">إدراج خدمة نقل جديدة</p>
                    </div>
                </div>
            </a>
            
            <a href="pricing.php" class="p-4 rounded-xl border border-gray-100 hover:border-gold-500 hover:bg-gold-50 transition-all group">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-lg bg-green-100 text-green-600 flex items-center justify-center group-hover:bg-gold-500 group-hover:text-white transition-colors">
                        <i class="fas fa-tag"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">تحديث الأسعار</h3>
                        <p class="text-sm text-gray-500">تعديل تكاليف الرحلات</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

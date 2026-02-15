<?php include __DIR__ . '/includes/header.php'; ?>

<?php
$db = new Database();
$conn = $db->getConnection();

// --- 1. Quick Stats ---
// Daily Bookings
$stmt = $conn->prepare("SELECT COUNT(*) FROM bookings WHERE DATE(created_at) = CURDATE()");
$stmt->execute();
$daily_bookings = $stmt->fetchColumn();

// Monthly Bookings
$stmt = $conn->prepare("SELECT COUNT(*) FROM bookings WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())");
$stmt->execute();
$monthly_bookings = $stmt->fetchColumn();

// Cancelled Bookings (This Month)
$stmt = $conn->prepare("SELECT COUNT(*) FROM bookings WHERE status = 'cancelled' AND MONTH(created_at) = MONTH(CURDATE())");
$stmt->execute();
$cancelled_bookings = $stmt->fetchColumn();

// Unique Customers
$stmt = $conn->prepare("SELECT COUNT(DISTINCT phone) FROM bookings");
$stmt->execute();
$total_customers = $stmt->fetchColumn();


// --- 2. Top Services ---
$stmt = $conn->prepare("
    SELECT s.name_ar, s.name_fr, COUNT(b.id) as booking_count 
    FROM bookings b 
    JOIN services s ON b.service_id = s.id 
    WHERE b.status != 'cancelled'
    GROUP BY s.id 
    ORDER BY booking_count DESC 
    LIMIT 5
");
$stmt->execute();
$top_services = $stmt->fetchAll(PDO::FETCH_ASSOC);


// --- 3. Frequent Customers ---
$stmt = $conn->prepare("
    SELECT customer_name, phone, COUNT(*) as booking_count
    FROM bookings 
    WHERE status = 'completed'
    GROUP BY phone 
    ORDER BY booking_count DESC 
    LIMIT 5
");
$stmt->execute();
$frequent_customers = $stmt->fetchAll(PDO::FETCH_ASSOC);


// --- 4. Last 7 Days Activity ---
$stmt = $conn->prepare("
    SELECT DATE(created_at) as date, COUNT(*) as count 
    FROM bookings 
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY) 
    GROUP BY DATE(created_at) 
    ORDER BY date ASC
");
$stmt->execute();
$daily_activity = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // Date => Count

// Fill missing days with 0
$last_7_days = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $last_7_days[$date] = $daily_activity[$date] ?? 0;
}
?>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">التقارير والإحصائيات</h1>
    <p class="text-gray-500">نظرة شاملة على أداء الخدمة</p>
</div>

<!-- Quick Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600">
                <i class="fas fa-calendar-day text-xl"></i>
            </div>
            <span class="text-xs font-semibold text-gray-400">اليوم</span>
        </div>
        <h3 class="text-3xl font-bold text-gray-800 mb-1"><?php echo $daily_bookings; ?></h3>
        <p class="text-gray-500 text-sm">طلب جديد</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center text-green-600">
                <i class="fas fa-calendar-alt text-xl"></i>
            </div>
            <span class="text-xs font-semibold text-gray-400">هذا الشهر</span>
        </div>
        <h3 class="text-3xl font-bold text-gray-800 mb-1"><?php echo $monthly_bookings; ?></h3>
        <p class="text-gray-500 text-sm">طلب جديد</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center text-red-600">
                <i class="fas fa-times-circle text-xl"></i>
            </div>
            <span class="text-xs font-semibold text-gray-400">ملغاة (هذا الشهر)</span>
        </div>
        <h3 class="text-3xl font-bold text-gray-800 mb-1"><?php echo $cancelled_bookings; ?></h3>
        <p class="text-gray-500 text-sm">عملية إلغاء</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center text-purple-600">
                <i class="fas fa-users text-xl"></i>
            </div>
            <span class="text-xs font-semibold text-gray-400">النمو</span>
        </div>
        <h3 class="text-3xl font-bold text-gray-800 mb-1"><?php echo $total_customers; ?></h3>
        <p class="text-gray-500 text-sm">عميل مسجل</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Top Services -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-800">أكثر الخدمات طلباً</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-right">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-3 font-medium">الخدمة</th>
                        <th class="px-6 py-3 font-medium">الخدمة (FR)</th>
                        <th class="px-6 py-3 font-medium">الطلبات (غير الملغاة)</th>
                        <th class="px-6 py-3 font-medium">النسبة</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    <?php 
                    $total_top_bookings = array_sum(array_column($top_services, 'booking_count'));
                    foreach ($top_services as $service): 
                        $percentage = $total_top_bookings > 0 ? ($service['booking_count'] / $total_top_bookings) * 100 : 0;
                    ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-800"><?php echo htmlspecialchars($service['name_ar']); ?></td>
                        <td class="px-6 py-4 text-gray-600"><?php echo htmlspecialchars($service['name_fr']); ?></td>
                        <td class="px-6 py-4 font-bold"><?php echo $service['booking_count']; ?></td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-16 h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-gold-500 rounded-full" style="width: <?php echo $percentage; ?>%"></div>
                                </div>
                                <span class="text-xs text-gray-500"><?php echo round($percentage); ?>%</span>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($top_services)): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">لا توجد بيانات كافية</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Frequent Customers -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-800">الزبائن الأكثر ولاءً</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-right">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-3 font-medium">العميل</th>
                        <th class="px-6 py-3 font-medium">الرحلات المكتملة</th>
                        <th class="px-6 py-3 font-medium">حالة العميل</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    <?php foreach ($frequent_customers as $customer): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-800"><?php echo htmlspecialchars($customer['customer_name']); ?></div>
                            <div class="text-xs text-gray-500" dir="ltr"><?php echo htmlspecialchars($customer['phone']); ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold">
                                <?php echo $customer['booking_count']; ?> رحلة ناجحة
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-800">
                            <span class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded">VIP</span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($frequent_customers)): ?>
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">لا توجد بيانات كافية</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Last 7 Days Chart (Visual Representation using CSS) -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <h2 class="text-lg font-bold text-gray-800 mb-6">نشاط آخر 7 أيام</h2>
    <div class="flex items-end justify-between h-48 gap-2">
        <?php 
        $max_bookings = max($last_7_days) ?: 1;
        foreach ($last_7_days as $date => $count): 
            $height_percent = ($count / $max_bookings) * 100;
            $height_percent = max($height_percent, 5); // Minimum height for visibility
            $day_name = date('D', strtotime($date));
        ?>
        <div class="flex flex-col items-center flex-1 group">
            <div class="relative w-full bg-blue-50 rounded-t-lg overflow-hidden flex flex-col justify-end group-hover:bg-blue-100 transition-colors" style="height: 100%;">
                <div class="w-full bg-blue-500 rounded-t-lg transition-all duration-500 relative group-hover:bg-blue-600" style="height: <?php echo $height_percent; ?>%">
                    <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                        <?php echo $count; ?>
                    </div>
                </div>
            </div>
            <div class="mt-2 text-xs text-gray-500 text-center">
                <span class="block font-bold"><?php echo $day_name; ?></span>
                <span class="text-[10px]"><?php echo date('m/d', strtotime($date)); ?></span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

<?php include __DIR__ . '/includes/header.php'; ?>

<?php
$db = new Database();
$conn = $db->getConnection();

$status_filter = $_GET['status'] ?? 'all';
$query = "SELECT b.*, s.name_ar as service_name, s.price as service_price, s.price_euro as service_price_euro 
          FROM bookings b 
          LEFT JOIN services s ON b.service_id = s.id";
if ($status_filter !== 'all') {
    $query .= " WHERE b.status = :status";
}
$query .= " ORDER BY b.created_at DESC";

$stmt = $conn->prepare($query);
if ($status_filter !== 'all') {
    $stmt->bindParam(':status', $status_filter);
}
$stmt->execute();
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div class="flex items-center gap-4 w-full md:w-auto justify-between md:justify-start">
        <h1 class="text-2xl font-bold text-gray-800">إدارة الحجوزات</h1>
        <button id="bulkDeleteBtn" onclick="deleteSelected()" class="hidden px-4 py-2 bg-red-50 text-red-600 rounded-lg text-sm font-bold border border-red-100 hover:bg-red-100 transition-colors flex items-center gap-2">
            <i class="fas fa-trash-alt"></i>
            <span>حذف <span class="hidden sm:inline">المحدد</span> (<span id="selectedCount">0</span>)</span>
        </button>
    </div>
    
    <div class="flex gap-2 overflow-x-auto pb-2 md:pb-0 w-full md:w-auto no-scrollbar">
        <a href="?status=all" class="whitespace-nowrap px-4 py-2 rounded-lg text-sm font-medium <?php echo $status_filter == 'all' ? 'bg-gray-800 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'; ?>">الكل</a>
        <a href="?status=new" class="whitespace-nowrap px-4 py-2 rounded-lg text-sm font-medium <?php echo $status_filter == 'new' ? 'bg-yellow-500 text-white' : 'bg-white text-gray-600 hover:bg-yellow-50'; ?>">جديد</a>
        <a href="?status=confirmed" class="whitespace-nowrap px-4 py-2 rounded-lg text-sm font-medium <?php echo $status_filter == 'confirmed' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-blue-50'; ?>">مؤكد</a>
        <a href="?status=completed" class="whitespace-nowrap px-4 py-2 rounded-lg text-sm font-medium <?php echo $status_filter == 'completed' ? 'bg-green-600 text-white' : 'bg-white text-gray-600 hover:bg-green-50'; ?>">مكتمل</a>
        <a href="?status=cancelled" class="whitespace-nowrap px-4 py-2 rounded-lg text-sm font-medium <?php echo $status_filter == 'cancelled' ? 'bg-red-600 text-white' : 'bg-white text-gray-600 hover:bg-red-50'; ?>">ملغى</a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <!-- Desktop Table -->
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-right">
            <thead class="bg-gray-50 text-gray-500 text-sm border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 w-10">
                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll()" class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                    </th>
                    <th class="px-6 py-4 font-medium">الرقم</th>
                    <th class="px-6 py-4 font-medium">العميل</th>
                    <th class="px-6 py-4 font-medium">الخدمة</th>
                    <th class="px-6 py-4 font-medium">الرحلة</th>
                    <th class="px-6 py-4 font-medium">التاريخ</th>
                    <th class="px-6 py-4 font-medium">الحالة</th>
                    <th class="px-6 py-4 font-medium">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach ($bookings as $booking): ?>
                <tr class="hover:bg-gray-50">
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <input type="checkbox" name="booking_ids[]" value="<?php echo $booking['id']; ?>" onchange="updateBulkDeleteState()" class="booking-checkbox w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                    </td>
                    <td class="px-6 py-4 text-sm font-mono text-gray-500">#<?php echo $booking['id']; ?></td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-800"><?php echo htmlspecialchars($booking['customer_name']); ?></div>
                        <div class="text-sm text-gray-500" dir="ltr"><?php echo htmlspecialchars($booking['phone']); ?></div>
                        <div class="text-xs text-gray-400"><?php echo htmlspecialchars($booking['email'] ?? '-'); ?></div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-gray-800 font-medium"><?php echo htmlspecialchars($booking['service_name'] ?? 'غير محدد'); ?></div>
                        <div class="text-xs text-gray-500">
                            <i class="fas fa-users ml-1"></i> <?php echo $booking['passengers']; ?> ركاب
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col gap-1 text-sm">
                            <span class="text-gray-500 flex items-center gap-2">
                                <i class="fas fa-map-marker-alt text-green-500 text-xs"></i>
                                <?php echo htmlspecialchars($booking['pickup_location']); ?>
                            </span>
                            <span class="text-gray-500 flex items-center gap-2">
                                <i class="fas fa-map-marker-alt text-red-500 text-xs"></i>
                                <?php echo htmlspecialchars($booking['destination']); ?>
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <div><?php echo htmlspecialchars($booking['booking_date']); ?></div>
                        <div class="text-gray-400"><?php echo htmlspecialchars($booking['booking_time']); ?></div>
                    </td>

                    <td class="px-6 py-4">
                        <div class="relative group">
                            <select onchange="updateStatus(<?php echo $booking['id']; ?>, this.value)" 
                                    class="appearance-none pl-8 pr-4 py-1.5 rounded-full text-xs font-bold cursor-pointer border-0 outline-none ring-1 ring-inset transition-all
                                    <?php 
                                    echo match($booking['status']) {
                                        'new' => 'bg-yellow-50 text-yellow-700 ring-yellow-600/20 hover:bg-yellow-100',
                                        'confirmed' => 'bg-blue-50 text-blue-700 ring-blue-600/20 hover:bg-blue-100',
                                        'completed' => 'bg-green-50 text-green-700 ring-green-600/20 hover:bg-green-100',
                                        'cancelled' => 'bg-red-50 text-red-700 ring-red-600/20 hover:bg-red-100',
                                        default => 'bg-gray-50 text-gray-700 ring-gray-600/20'
                                    };
                                    ?>">
                                <option value="new" <?php echo $booking['status'] == 'new' ? 'selected' : ''; ?>>جديد</option>
                                <option value="confirmed" <?php echo $booking['status'] == 'confirmed' ? 'selected' : ''; ?>>مؤكد</option>
                                <option value="completed" <?php echo $booking['status'] == 'completed' ? 'selected' : ''; ?>>مكتمل</option>
                                <option value="cancelled" <?php echo $booking['status'] == 'cancelled' ? 'selected' : ''; ?>>ملغى</option>
                            </select>

                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <button type="button" 
                                data-booking='<?php echo htmlspecialchars(json_encode($booking), ENT_QUOTES, 'UTF-8'); ?>'
                                onclick="showDetails(this)" 
                                class="text-blue-500 hover:text-blue-700 p-2 rounded-lg hover:bg-blue-50 transition-colors" title="التفاصيل">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button type="button" onclick="deleteBooking(<?php echo $booking['id']; ?>)" class="text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-colors" title="حذف">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
                
                <?php if (empty($bookings)): ?>
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                        <p>لا توجد حجوزات <?php echo $status_filter !== 'all' ? 'بهذه الحالة' : ''; ?></p>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    </div>

    <!-- Mobile Card View -->
    <div class="md:hidden divide-y divide-gray-100">
        <!-- Bulk Select Header for Mobile -->
        <div class="p-4 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <input type="checkbox" id="selectAllMobile" onchange="toggleSelectAllMobile()" class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                <span class="text-sm font-bold text-gray-700">تحديد الكل</span>
            </div>
            <span class="text-xs text-gray-400">سحب لليسار للإجراءات</span>
        </div>

        <?php foreach ($bookings as $booking): ?>
        <div class="p-4 bg-white relative group">
            <div class="flex items-start gap-3">
                <div class="pt-1">
                    <input type="checkbox" name="booking_ids[]" value="<?php echo $booking['id']; ?>" onchange="updateBulkDeleteState()" class="booking-checkbox-mobile w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                </div>
                <div class="flex-1 space-y-3">
                    <!-- Header: ID + Status -->
                    <div class="flex justify-between items-start">
                        <div class="flex items-center gap-2">
                            <span class="font-mono text-sm text-gray-400">#<?php echo $booking['id']; ?></span>
                            <span class="text-sm font-bold text-gray-800"><?php echo htmlspecialchars($booking['customer_name']); ?></span>
                        </div>
                        <select onchange="updateStatus(<?php echo $booking['id']; ?>, this.value)" 
                                class="appearance-none px-3 py-1 rounded-full text-[10px] font-bold cursor-pointer border-0 outline-none ring-1 ring-inset transition-all w-24
                                <?php 
                                echo match($booking['status']) {
                                    'new' => 'bg-yellow-50 text-yellow-700 ring-yellow-600/20',
                                    'confirmed' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                                    'completed' => 'bg-green-50 text-green-700 ring-green-600/20',
                                    'cancelled' => 'bg-red-50 text-red-700 ring-red-600/20',
                                    default => 'bg-gray-50 text-gray-700 ring-gray-600/20'
                                };
                                ?>">
                            <option value="new" <?php echo $booking['status'] == 'new' ? 'selected' : ''; ?>>جديد</option>
                            <option value="confirmed" <?php echo $booking['status'] == 'confirmed' ? 'selected' : ''; ?>>مؤكد</option>
                            <option value="completed" <?php echo $booking['status'] == 'completed' ? 'selected' : ''; ?>>مكتمل</option>
                            <option value="cancelled" <?php echo $booking['status'] == 'cancelled' ? 'selected' : ''; ?>>ملغى</option>
                        </select>
                    </div>

                    <!-- Route -->
                    <div class="flex flex-col gap-1.5 text-sm p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-2">
                            <div class="w-1.5 h-1.5 rounded-full bg-green-500"></div>
                            <span class="text-gray-600 truncate"><?php echo htmlspecialchars($booking['pickup_location']); ?></span>
                        </div>
                        <div class="ml-[2.5px] border-r-2 border-gray-200 h-2"></div>
                        <div class="flex items-center gap-2">
                            <div class="w-1.5 h-1.5 rounded-full bg-red-500"></div>
                            <span class="text-gray-600 truncate"><?php echo htmlspecialchars($booking['destination']); ?></span>
                        </div>
                    </div>

                    <!-- Meta: Date + Service -->
                    <div class="flex justify-between items-center text-xs text-gray-500">
                        <div class="flex items-center gap-2">
                            <i class="far fa-calendar-alt"></i>
                            <span><?php echo htmlspecialchars($booking['booking_date']); ?></span>
                            <span class="text-gray-300">|</span>
                            <span><?php echo htmlspecialchars($booking['booking_time']); ?></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-taxi text-gray-400"></i>
                            <span><?php echo htmlspecialchars($booking['service_name'] ?? 'غير محدد'); ?></span>
                        </div>
                    </div>

                    <!-- Actions Panel -->
                    <div class="flex justify-end gap-2 pt-2 border-t border-gray-50">
                        <button type="button" 
                                data-booking='<?php echo htmlspecialchars(json_encode($booking), ENT_QUOTES, 'UTF-8'); ?>'
                                onclick="showDetails(this)" 
                                class="text-blue-600 bg-blue-50 px-3 py-1.5 rounded-md text-xs font-bold">
                            التفاصيل
                        </button>
                        <button type="button" 
                                onclick="deleteBooking(<?php echo $booking['id']; ?>)" 
                                class="text-red-600 bg-red-50 px-3 py-1.5 rounded-md text-xs font-bold">
                            حذف
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        
        <?php if (empty($bookings)): ?>
        <div class="p-8 text-center text-gray-500">
            <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
            <p>لا توجد حجوزات</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Booking Details Modal -->
<div id="bookingModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden transform transition-all">
            <!-- Header -->
            <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-info-circle text-gold-500"></i>
                        تفاصيل الحجز <span id="modalId" class="text-gray-400 font-mono text-base"></span>
                    </h3>
                </div>
                <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 text-gray-400 hover:bg-red-50 hover:text-red-500 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Body -->
            <div class="p-6 overflow-y-auto max-h-[80vh]">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Customer Info -->
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <i class="fas fa-user"></i> معلومات العميل
                        </h4>
                        <div class="space-y-3">
                            <div>
                                <label class="text-xs text-gray-500">الاسم الكامل</label>
                                <p id="modalCustomer" class="font-semibold text-gray-800"></p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500">رقم الهاتف</label>
                                <p id="modalPhone" class="font-mono text-gray-800 text-sm" dir="ltr"></p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500">البريد الإلكتروني</label>
                                <p id="modalEmail" class="text-gray-600 text-sm"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Trip Info -->
                    <div class="bg-blue-50/50 rounded-xl p-4 border border-blue-100">
                        <h4 class="text-xs font-bold text-blue-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <i class="fas fa-route"></i> تفاصيل الرحلة
                        </h4>
                        <div class="space-y-3">
                            <div>
                                <label class="text-xs text-gray-500">الخدمة</label>
                                <div class="flex items-center gap-2">
                                    <span id="modalService" class="font-semibold text-gray-800"></span>
                                    <span class="text-xs bg-white px-2 py-0.5 rounded border border-blue-100 text-blue-600">
                                        <i class="fas fa-users ml-1"></i><span id="modalPassengers"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="relative pl-4 border-r-2 border-gray-200 mr-1 pr-4">
                                <div class="mb-2 relative">
                                    <div class="absolute -right-[21px] top-1.5 w-3 h-3 rounded-full bg-green-500 border-2 border-white ring-1 ring-green-200"></div>
                                    <label class="text-[10px] text-gray-500">من</label>
                                    <p id="modalPickup" class="text-sm font-medium text-gray-700"></p>
                                </div>
                                <div class="relative">
                                    <div class="absolute -right-[21px] top-1.5 w-3 h-3 rounded-full bg-red-500 border-2 border-white ring-1 ring-red-200"></div>
                                    <label class="text-[10px] text-gray-500">إلى</label>
                                    <p id="modalDestination" class="text-sm font-medium text-gray-700"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Date & Status -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                    <div class="bg-gray-50 rounded-lg p-3 text-center">
                        <label class="block text-xs text-gray-400 mb-1">التاريخ</label>
                        <p id="modalDate" class="font-semibold text-gray-800"></p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 text-center">
                        <label class="block text-xs text-gray-400 mb-1">الوقت</label>
                        <p id="modalTime" class="font-semibold text-gray-800"></p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 text-center">
                        <label class="block text-xs text-gray-400 mb-1">السعر المقدر</label>
                        <p id="modalPrice" class="font-bold text-gray-800 text-lg"></p>
                    </div>
                </div>

                <!-- Notes -->
                <div class="mt-6">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">
                        <i class="fas fa-comment-alt ml-1"></i> ملاحظات إضافية
                    </label>
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 min-h-[80px]">
                        <p id="modalNotes" class="text-gray-600 text-sm leading-relaxed whitespace-pre-line"></p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-end gap-3">
                <button onclick="closeModal()" class="px-6 py-2 bg-white border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm font-medium">
                    إغلاق
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function showDetails(btn) {
    const booking = JSON.parse(btn.dataset.booking);
    document.getElementById('bookingModal').classList.remove('hidden');
    
    // Header
    document.getElementById('modalId').textContent = '#' + booking.id;
    
    // Customer
    document.getElementById('modalCustomer').textContent = booking.customer_name;
    document.getElementById('modalPhone').textContent = booking.phone;
    document.getElementById('modalEmail').textContent = booking.email || 'غير متوفر';
    
    // Trip
    document.getElementById('modalService').textContent = booking.service_name || 'غير محدد';
    document.getElementById('modalPassengers').textContent = booking.passengers;
    document.getElementById('modalPickup').textContent = booking.pickup_location;
    document.getElementById('modalDestination').textContent = booking.destination;
    
    // Date & Price
    document.getElementById('modalDate').textContent = booking.booking_date;
    document.getElementById('modalTime').textContent = booking.booking_time;
    
    // Display price - prioritize booking's saved price over service price
    const priceEl = document.getElementById('modalPrice');
    
    // Helper function for Euro formatting
    const formatEuroHTML = (amount) => {
        return ' <span class="text-gray-400 mx-1">|</span> <span class="bg-blue-100 text-blue-800 text-sm font-bold px-2 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800" dir="ltr">(' + parseFloat(amount).toFixed(2) + ' €)</span>';
    };

    // First, try to use the booking's saved price and price_euro
    if (booking.price && parseFloat(booking.price) > 0) {
        // Enforce LTR for DZD price: Number then Currency
        let priceHTML = '<span dir="ltr">' + parseFloat(booking.price).toFixed(2) + ' دج</span>';
        
        // Check if booking has price_euro saved
        if (booking.price_euro && parseFloat(booking.price_euro) > 0) {
            priceHTML += formatEuroHTML(booking.price_euro);
        } else {
            // Auto-calculate Euro price from DZD
            const autoEuro = (parseFloat(booking.price) / 220).toFixed(2);
            priceHTML += formatEuroHTML(autoEuro);
        }
        
        priceEl.innerHTML = priceHTML;
    } else if (booking.service_price && parseFloat(booking.service_price) > 0) {
        // Fallback to service price if booking price not available
        let priceHTML = '<span dir="ltr">' + parseFloat(booking.service_price).toFixed(2) + ' دج</span>';
        
        if (booking.service_price_euro && parseFloat(booking.service_price_euro) > 0) {
            priceHTML += formatEuroHTML(booking.service_price_euro);
        } else {
            const autoEuro = (parseFloat(booking.service_price) / 220).toFixed(2);
            priceHTML += formatEuroHTML(autoEuro);
        }
        
        priceEl.innerHTML = priceHTML;
    } else {
        priceEl.textContent = 'عند الطلب';
    }
    
    // Notes
    const notesEl = document.getElementById('modalNotes');
    if (booking.notes) {
        notesEl.textContent = booking.notes;
        notesEl.classList.remove('text-gray-400', 'italic');
    } else {
        notesEl.textContent = 'لا توجد ملاحظات إضافية من العميل';
        notesEl.classList.add('text-gray-400', 'italic');
    }
}

function closeModal() {
    document.getElementById('bookingModal').classList.add('hidden');
}

async function updateStatus(id, status) {
    try {
        const formData = new FormData();
        formData.append('action', 'update_status');
        formData.append('id', id);
        formData.append('status', status);

        const response = await fetch('handlers/booking-handler.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        if (data.success) {
            showToast('success', 'تم تحديث الحالة');
            // setTimeout(() => location.reload(), 1000); // Optional: reload if needed, or just let the toast show
            // For status change, maybe just update UI class? But reload is safer for consistency
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('error', 'فشل تحديث الحالة: ' + data.message);
        }
    } catch (error) {
        showToast('error', 'حدث خطأ في الاتصال');
    }
}

async function deleteBooking(id) {
    confirmAction(async () => {
        try {
            const formData = new FormData();
            formData.append('action', 'delete');
            formData.append('id', id);

            const response = await fetch('handlers/booking-handler.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            if (data.success) {
                showToast('success', 'تم حذف الحجز');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast('error', 'فشل الحذف: ' + data.message);
            }
        } catch (error) {
            showToast('error', 'حدث خطأ في الاتصال');
        }
    });
}

// Bulk Delete Logic
function toggleSelectAll() {
    const selectAllDetails = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.booking-checkbox, .booking-checkbox-mobile');
    checkboxes.forEach(cb => cb.checked = selectAllDetails.checked);
    
    // Sync mobile Select All if exists
    const selectAllMobile = document.getElementById('selectAllMobile');
    if(selectAllMobile) selectAllMobile.checked = selectAllDetails.checked;
    
    updateBulkDeleteState();
}

function toggleSelectAllMobile() {
    const selectAllMobile = document.getElementById('selectAllMobile');
    const checkboxes = document.querySelectorAll('.booking-checkbox, .booking-checkbox-mobile');
    checkboxes.forEach(cb => cb.checked = selectAllMobile.checked);
    
    // Sync desktop Select All
    const selectAllDesktop = document.getElementById('selectAll');
    if(selectAllDesktop) selectAllDesktop.checked = selectAllMobile.checked;
    
    updateBulkDeleteState();
}

function updateBulkDeleteState() {
    const checkboxes = document.querySelectorAll('.booking-checkbox:checked, .booking-checkbox-mobile:checked');
    const btn = document.getElementById('bulkDeleteBtn');
    const countSpan = document.getElementById('selectedCount');
    
    if (checkboxes.length > 0) {
        btn.classList.remove('hidden');
        countSpan.textContent = checkboxes.length;
    } else {
        btn.classList.add('hidden');
    }
}

async function deleteSelected() {
    const checkboxes = document.querySelectorAll('.booking-checkbox:checked, .booking-checkbox-mobile:checked');
    // Use Set to avoid duplicate IDs if elements are duplicated for responsive view (hiding/showing)
    // Though here we have separate lists, but just in case
    const ids = [...new Set(Array.from(checkboxes).map(cb => cb.value))];
    
    if (ids.length === 0) return;

    confirmAction(async () => {
        try {
            const formData = new FormData();
            formData.append('action', 'bulk_delete');
            // Append each ID
            ids.forEach(id => formData.append('ids[]', id));

            const response = await fetch('handlers/booking-handler.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            if (data.success) {
                showToast('success', 'تم حذف ' + ids.length + ' حجوزات بنجاح');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast('error', 'فشل الحذف: ' + data.message);
            }
        } catch (error) {
            showToast('error', 'حدث خطأ في الاتصال');
        }
    }, 'هل أنت متأكد من حذف ' + ids.length + ' حجوزات؟ لا يمكن التراجع عن هذا الإجراء.');
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>

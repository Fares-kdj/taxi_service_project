<?php include __DIR__ . '/includes/header.php'; ?>

<?php
$db = new Database();
$conn = $db->getConnection();

// Fetch pricing routes with service info
$stmt = $conn->query("SELECT p.*, s.name_ar as service_name FROM pricing p LEFT JOIN services s ON p.service_id = s.id ORDER BY p.id DESC");
$routes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch available services for dropdown
$services_stmt = $conn->query("SELECT id, name_ar FROM services WHERE active = TRUE ORDER BY display_order ASC");
$services = $services_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
    <h1 class="text-xl md:text-2xl font-bold text-gray-800">إدارة الأسعار والمسارات</h1>
    <button onclick="openModal()" class="w-full sm:w-auto px-4 md:px-6 py-2 bg-gold-600 hover:bg-gold-700 text-white rounded-lg shadow-lg transition-colors flex items-center justify-center gap-2">
        <i class="fas fa-plus"></i>
        <span>إضافة مسار</span>
    </button>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-right">
            <thead class="bg-gray-50 text-gray-500 text-sm border-b border-gray-100">
                <tr>
                    <th class="px-3 md:px-6 py-4 font-medium">من</th>
                    <th class="px-3 md:px-6 py-4 font-medium">إلى</th>
                    <th class="px-3 md:px-6 py-4 font-medium">السعر (د.ج)</th>
                    <th class="px-3 md:px-6 py-4 font-medium">السعر (€)</th>
                    <th class="px-3 md:px-6 py-4 font-medium hidden md:table-cell">المسافة</th>
                    <th class="px-3 md:px-6 py-4 font-medium hidden lg:table-cell">المدة</th>
                    <th class="px-3 md:px-6 py-4 font-medium">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach ($routes as $route): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-3 md:px-6 py-4 text-gray-800 font-medium">
                        <div class="text-xs text-gray-500 mb-1">AR: <?php echo htmlspecialchars($route['from_location_ar']); ?></div>
                        <div class="text-xs text-blue-500">FR: <?php echo htmlspecialchars($route['from_location_fr']); ?></div>
                    </td>
                    <td class="px-3 md:px-6 py-4 text-gray-800 font-medium">
                        <div class="text-xs text-gray-500 mb-1">AR: <?php echo htmlspecialchars($route['to_location_ar']); ?></div>
                        <div class="text-xs text-blue-500">FR: <?php echo htmlspecialchars($route['to_location_fr']); ?></div>
                    </td>
                    <td class="px-3 md:px-6 py-4 text-gold-600 font-bold whitespace-nowrap"><?php echo number_format($route['price'], 2, '.', ' '); ?></td>
                    <td class="px-3 md:px-6 py-4 font-semibold whitespace-nowrap" dir="ltr">
                        <?php 
                        if ($route['price_euro'] && $route['price_euro'] > 0) {
                            echo '<span class="text-blue-600">' . number_format($route['price_euro'], 2, '.', ' ') . '</span>';
                        } else {
                            echo '<span class="text-gray-400 text-xs">-</span>';
                        }
                        ?>
                    </td>
                    <td class="px-3 md:px-6 py-4 text-gray-500 text-sm hidden md:table-cell"><?php echo htmlspecialchars($route['distance_km']); ?></td>
                    <td class="px-3 md:px-6 py-4 text-gray-500 text-sm hidden lg:table-cell"><?php echo htmlspecialchars($route['duration']); ?></td>
                    <td class="px-3 md:px-6 py-4">
                        <div class="flex gap-2">
                            <button onclick='editRoute(<?php echo json_encode($route); ?>)' class="text-blue-500 hover:text-blue-700 p-2 hover:bg-blue-50 rounded-lg transition-colors">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteRoute(<?php echo $route['id']; ?>)" class="text-red-500 hover:text-red-700 p-2 hover:bg-red-50 rounded-lg transition-colors">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="routeModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal()"></div>
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-lg">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 id="modalTitle" class="text-lg font-bold text-gray-800">إضافة مسار جديد</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
            </div>
            
            <form id="routeForm" class="p-6 space-y-4">
                <input type="hidden" name="action" id="formAction" value="add">
                <input type="hidden" name="id" id="routeId" value="">
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">من (بالعربية)</label>
                        <input type="text" name="from_location_ar" id="from_ar" required class="w-full px-3 py-2 border rounded-lg focus:ring-gold-500" placeholder="مثال: مطار هواري بومدين">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">من (بالفرنسية)</label>
                        <input type="text" name="from_location_fr" id="from_fr" required class="w-full px-3 py-2 border rounded-lg focus:ring-gold-500" placeholder="Ex: Aéroport Houari Boumediene" dir="ltr">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">إلى (بالعربية)</label>
                        <input type="text" name="to_location_ar" id="to_ar" required class="w-full px-3 py-2 border rounded-lg focus:ring-gold-500" placeholder="مثال: الجزائر الوسطى">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">إلى (بالفرنسية)</label>
                        <input type="text" name="to_location_fr" id="to_fr" required class="w-full px-3 py-2 border rounded-lg focus:ring-gold-500" placeholder="Ex: Alger Centre" dir="ltr">
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">السعر (د.ج)</label>
                        <input type="number" name="price" id="price" required class="w-full px-3 py-2 border rounded-lg focus:ring-gold-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">السعر (أورو)</label>
                        <input type="number" step="0.01" name="price_euro" id="price_euro" class="w-full px-3 py-2 border rounded-lg focus:ring-gold-500">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">الخدمة <span class="text-red-500">*</span></label>
                    <select name="service_id" id="service_id" required class="w-full px-3 py-2 border rounded-lg focus:ring-gold-500">
                        <option value="">اختر الخدمة</option>
                        <?php foreach ($services as $service): ?>
                        <option value="<?php echo $service['id']; ?>"><?php echo htmlspecialchars($service['name_ar']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">المسافة (مثال: 50 km)</label>
                        <input type="text" name="distance" id="distance" class="w-full px-3 py-2 border rounded-lg focus:ring-gold-500" dir="ltr">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">المدة (مثال: 45 min)</label>
                        <input type="text" name="duration" id="duration" class="w-full px-3 py-2 border rounded-lg focus:ring-gold-500" dir="ltr">
                    </div>
                </div>

                <div class="flex justify-end pt-4 gap-3">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">إلغاء</button>
                    <button type="submit" class="px-6 py-2 bg-gold-600 hover:bg-gold-700 text-white rounded-lg shadow">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('routeModal').classList.remove('hidden');
    document.getElementById('routeForm').reset();
    document.getElementById('formAction').value = 'add';
    document.getElementById('modalTitle').textContent = 'إضافة مسار جديد';
}

function closeModal() {
    document.getElementById('routeModal').classList.add('hidden');
}

function editRoute(route) {
    openModal();
    document.getElementById('formAction').value = 'edit';
    document.getElementById('routeId').value = route.id;
    document.getElementById('modalTitle').textContent = 'تعديل المسار';
    document.getElementById('from_ar').value = route.from_location_ar;
    document.getElementById('from_fr').value = route.from_location_fr;
    document.getElementById('to_ar').value = route.to_location_ar;
    document.getElementById('to_fr').value = route.to_location_fr;
    document.getElementById('price').value = route.price;
    document.getElementById('price_euro').value = route.price_euro;
    document.getElementById('service_id').value = route.service_id || '';
    document.getElementById('distance').value = route.distance_km;
    document.getElementById('duration').value = route.duration;
}

document.getElementById('routeForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    try {
        const formData = new FormData(this);
        const response = await fetch('handlers/pricing-handler.php', { method: 'POST', body: formData });
        const data = await response.json();
        if (data.success) {
            showToast('success', 'تم حفظ المسار بنجاح');
            setTimeout(() => location.reload(), 1500);
        }
        else showToast('error', data.message);
    } catch (error) { showToast('error', 'حدث خطأ في النظام'); }
});

async function deleteRoute(id) {
    confirmAction(async () => {
        const formData = new FormData();
        formData.append('action', 'delete');
        formData.append('id', id);
        try {
            const response = await fetch('handlers/pricing-handler.php', { method: 'POST', body: formData });
            const data = await response.json();
            if (data.success) {
                showToast('success', 'تم حذف المسار');
                setTimeout(() => location.reload(), 1500);
            }
            else showToast('error', data.message);
        } catch (error) { showToast('error', 'حدث خطأ في النظام'); }
    });
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>

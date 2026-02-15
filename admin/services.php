<?php include __DIR__ . '/includes/header.php'; ?>

<?php
$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->query("SELECT * FROM services ORDER BY id DESC");
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="mb-6 flex justify-between items-center">
    <h1 class="text-xl md:text-2xl font-bold text-gray-800">إدارة الخدمات</h1>
    <button onclick="openModal()" class="px-3 py-1.5 md:px-6 md:py-2 text-sm md:text-base bg-gold-600 hover:bg-gold-700 text-white rounded-lg shadow-lg transition-colors flex items-center gap-2">
        <i class="fas fa-plus"></i>
        <span>إضافة خدمة</span>
    </button>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach ($services as $service): ?>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-md transition-all">
        <div class="h-40 overflow-hidden bg-gray-100 relative">
            <?php if ($service['image']): ?>
                <img src="../<?php echo htmlspecialchars($service['image']); ?>" alt="Service" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            <?php else: ?>
                <div class="w-full h-full flex items-center justify-center text-gray-300">
                    <i class="fas fa-taxi text-4xl"></i>
                </div>
            <?php endif; ?>
            <div class="absolute top-2 right-2 bg-white/90 backdrop-blur rounded p-1 shadow-sm">
                <i class="fas <?php echo htmlspecialchars($service['icon']); ?> text-gold-600"></i>
            </div>
        </div>
        
        <div class="p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($service['name_ar']); ?></h3>
            <p class="text-gray-500 text-sm mb-4 line-clamp-2"><?php echo htmlspecialchars($service['description_ar']); ?></p>
            
            <div class="flex justify-between items-center pt-4 border-t border-gray-50">
                <div class="text-xs text-gray-400 font-mono">ID: <?php echo $service['id']; ?></div>
                <div class="flex gap-2">
                    <button onclick='editService(<?php echo htmlspecialchars(json_encode($service), ENT_QUOTES); ?>)' class="text-blue-500 hover:text-blue-700 p-2 hover:bg-blue-50 rounded-lg transition-colors">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button onclick="deleteService(<?php echo $service['id']; ?>)" class="text-red-500 hover:text-red-700 p-2 hover:bg-red-50 rounded-lg transition-colors">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Add/Edit Modal -->
<div id="serviceModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal()"></div>
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden max-h-[90vh] overflow-y-auto">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 id="modalTitle" class="text-lg font-bold text-gray-800">إضافة خدمة جديدة</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
            </div>
            
            <form id="serviceForm" class="p-6 space-y-4">
                <input type="hidden" name="action" id="formAction" value="add">
                <input type="hidden" name="id" id="serviceId" value="">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">العنوان (عربي)</label>
                        <input type="text" name="name_ar" id="title_ar" required class="w-full px-3 py-2 border rounded-lg focus:ring-gold-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">العنوان (فرنسي)</label>
                        <input type="text" name="name_fr" id="title_fr" required class="w-full px-3 py-2 border rounded-lg focus:ring-gold-500 text-left" dir="ltr">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">الوصف (عربي)</label>
                        <textarea name="description_ar" id="description_ar" rows="3" class="w-full px-3 py-2 border rounded-lg focus:ring-gold-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">الوصف (فرنسي)</label>
                        <textarea name="description_fr" id="description_fr" rows="3" class="w-full px-3 py-2 border rounded-lg focus:ring-gold-500 text-left" dir="ltr"></textarea>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">أيقونة (FontAwesome)</label>
                        <div class="relative">
                            <input type="text" name="icon" id="icon" class="w-full px-3 py-2 border rounded-lg focus:ring-gold-500 text-left" dir="ltr" placeholder="fa-taxi">
                            <div class="absolute top-2 right-2 text-gray-400"><i class="fas fa-icons"></i></div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">صورة الخدمة</label>
                        <input type="file" name="image" id="image" accept="image/*" class="w-full px-3 py-2 border rounded-lg focus:ring-gold-500 text-left" dir="ltr">
                        <small class="text-gray-400">اترك فارغاً للإبقاء على الصورة الحالية</small>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">السعر (د.ج)</label>
                        <input type="number" step="0.01" name="price" id="price" class="w-full px-3 py-2 border rounded-lg focus:ring-gold-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">السعر (أورو)</label>
                        <input type="number" step="0.01" name="price_euro" id="price_euro" class="w-full px-3 py-2 border rounded-lg focus:ring-gold-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">نوع السعر</label>
                        <select name="price_type" id="price_type" class="w-full px-3 py-2 border rounded-lg focus:ring-gold-500">
                            <option value="fixed">ثابت</option>
                            <option value="starting_from">يبدأ من</option>
                            <option value="on_request">عند الطلب</option>
                        </select>
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
    document.getElementById('serviceModal').classList.remove('hidden');
    document.getElementById('serviceForm').reset();
    document.getElementById('formAction').value = 'add';
    document.getElementById('modalTitle').textContent = 'إضافة خدمة جديدة';
}

function closeModal() {
    document.getElementById('serviceModal').classList.add('hidden');
}

function editService(service) {
    openModal();
    document.getElementById('formAction').value = 'edit';
    document.getElementById('serviceId').value = service.id;
    document.getElementById('modalTitle').textContent = 'تعديل الخدمة';
    document.getElementById('title_ar').value = service.name_ar;
    document.getElementById('title_fr').value = service.name_fr;
    document.getElementById('description_ar').value = service.description_ar;
    document.getElementById('description_fr').value = service.description_fr;
    document.getElementById('icon').value = service.icon;
    document.getElementById('price').value = service.price;
    document.getElementById('price_euro').value = service.price_euro;
    document.getElementById('price_type').value = service.price_type || 'starting_from';
    // Image handling is different for file input, we leave it empty
}

document.getElementById('serviceForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    try {
        const formData = new FormData(this);
        // Debug
        console.log([...formData]);
        const response = await fetch('handlers/service-handler.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        if (data.success) {
            showToast('success', 'تم حفظ الخدمة بنجاح');
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast('error', 'فشل الحفظ: ' + data.message);
        }
    } catch (error) {
        showToast('error', 'حدث خطأ في الاتصال');
    }
});

async function deleteService(id) {
    confirmAction(async () => {
        const formData = new FormData();
        formData.append('action', 'delete');
        formData.append('id', id);

        try {
            const response = await fetch('handlers/service-handler.php', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            if (data.success) {
                showToast('success', 'تم حذف الخدمة');
                setTimeout(() => location.reload(), 1500);
            }
            else showToast('error', data.message);
        } catch (error) {
            showToast('error', 'حدث خطأ في النظام');
        }
    });
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>

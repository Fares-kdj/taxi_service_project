<?php include __DIR__ . '/includes/header.php'; ?>

<?php
$db = new Database();
$conn = $db->getConnection();
// Ensure table exists with multilingual support
$conn->exec("CREATE TABLE IF NOT EXISTS testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name_ar VARCHAR(100),
    name_fr VARCHAR(100),
    role_ar VARCHAR(100),
    role_fr VARCHAR(100),
    content_ar TEXT,
    content_fr TEXT,
    rating INT DEFAULT 5,
    active BOOLEAN DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Migrate old data if content column exists
try {
    $conn->exec("ALTER TABLE testimonials ADD COLUMN IF NOT EXISTS name_ar VARCHAR(100)");
    $conn->exec("ALTER TABLE testimonials ADD COLUMN IF NOT EXISTS name_fr VARCHAR(100)");
    $conn->exec("ALTER TABLE testimonials ADD COLUMN IF NOT EXISTS role_ar VARCHAR(100)");
    $conn->exec("ALTER TABLE testimonials ADD COLUMN IF NOT EXISTS role_fr VARCHAR(100)");
    $conn->exec("ALTER TABLE testimonials ADD COLUMN IF NOT EXISTS content_ar TEXT");
    $conn->exec("ALTER TABLE testimonials ADD COLUMN IF NOT EXISTS content_fr TEXT");
    // Copy old data to arabic columns if exists
    $conn->exec("UPDATE testimonials SET name_ar = name WHERE (name_ar IS NULL OR name_ar = '') AND name IS NOT NULL");
    $conn->exec("UPDATE testimonials SET role_ar = role WHERE (role_ar IS NULL OR role_ar = '') AND role IS NOT NULL");
    $conn->exec("UPDATE testimonials SET content_ar = content WHERE (content_ar IS NULL OR content_ar = '') AND content IS NOT NULL");
} catch (Exception $e) {
    // Columns already exist or migration not needed
}

$stmt = $conn->query("SELECT * FROM testimonials ORDER BY created_at DESC");
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-800">آراء العملاء</h1>
    <button onclick="openModal()" class="px-6 py-2 bg-gold-600 hover:bg-gold-700 text-white rounded-lg shadow-lg transition-colors flex items-center gap-2">
        <i class="fas fa-plus"></i>
        <span>إضافة رأي</span>
    </button>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach ($reviews as $review): ?>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 relative">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-xl font-bold text-gray-500">
                <?php 
                $display_name = $review['name_ar'] ?: $review['name_fr'] ?: '';
                echo mb_substr($display_name, 0, 1); 
                ?>
            </div>
            <div>
                <h3 class="font-bold text-gray-800"><?php echo htmlspecialchars($review['name_ar'] ?: $review['name_fr'] ?: ''); ?></h3>
                <p class="text-xs text-gray-500"><?php echo htmlspecialchars($review['role_ar'] ?: $review['role_fr'] ?: ''); ?></p>
            </div>
        </div>
        
        <div class="mb-4 text-yellow-500 text-sm">
            <?php for($i=0; $i<$review['rating']; $i++) echo '<i class="fas fa-star"></i>'; ?>
        </div>
        
        <p class="text-gray-600 text-sm mb-4 italic">"<?php echo htmlspecialchars($review['content_ar'] ?: $review['content_fr'] ?: ''); ?>"</p>
        
        <div class="flex justify-end gap-2 border-t pt-4">
            <button onclick='editReview(<?php echo htmlspecialchars(json_encode($review), ENT_QUOTES); ?>)' class="text-blue-500 p-2 hover:bg-blue-50 rounded"><i class="fas fa-edit"></i></button>
            <button onclick="deleteReview(<?php echo $review['id']; ?>)" class="text-red-500 p-2 hover:bg-red-50 rounded"><i class="fas fa-trash"></i></button>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Modal -->
<div id="reviewModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
        
        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-2xl text-right overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <div class="bg-white px-6 pt-5 pb-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 id="modalTitle" class="text-xl font-bold text-gray-900">إضافة تقييم جديد</h3>
                    <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="max-h-[70vh] overflow-y-auto pr-2">
                    <form id="reviewForm" class="space-y-4">
                        <input type="hidden" name="action" id="formAction" value="add">
                        <input type="hidden" name="id" id="reviewId" value="">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold mb-1 text-gray-700">الاسم بالعربية</label>
                                <input type="text" name="name_ar" id="name_ar" placeholder="الاسم بالعربية" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold mb-1 text-gray-700">الاسم بالفرنسية</label>
                                <input type="text" name="name_fr" id="name_fr" placeholder="Nom en français" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-transparent">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold mb-1 text-gray-700">الصفة بالعربية</label>
                                <input type="text" name="role_ar" id="role_ar" placeholder="الصفة (مثال: مدير شركة)" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold mb-1 text-gray-700">الصفة بالفرنسية</label>
                                <input type="text" name="role_fr" id="role_fr" placeholder="Poste (ex: Directeur)" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-transparent">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold mb-1 text-gray-700">التقييم (1-5)</label>
                            <input type="number" name="rating" id="rating" min="1" max="5" value="5" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold mb-1 text-gray-700">النص بالعربية</label>
                            <textarea name="content_ar" id="content_ar" rows="3" placeholder="نص التقييم بالعربية" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-transparent"></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold mb-1 text-gray-700">النص بالفرنسية</label>
                            <textarea name="content_fr" id="content_fr" rows="3" placeholder="Texte du témoignage en français" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-transparent"></textarea>
                        </div>
                        
                        <div class="flex gap-3 pt-4">
                            <button type="submit" class="flex-1 py-3 bg-gold-600 hover:bg-gold-700 text-white rounded-lg font-bold transition-colors">
                                <i class="fas fa-save mr-2"></i>حفظ
                            </button>
                            <button type="button" onclick="closeModal()" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-bold transition-colors">
                                إلغاء
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('reviewModal').classList.remove('hidden');
    document.getElementById('reviewForm').reset();
    document.getElementById('formAction').value = 'add';
}
function closeModal() { document.getElementById('reviewModal').classList.add('hidden'); }

function editReview(data) {
    openModal();
    document.getElementById('formAction').value = 'edit';
    document.getElementById('reviewId').value = data.id;
    document.getElementById('name_ar').value = data.name_ar || '';
    document.getElementById('name_fr').value = data.name_fr || '';
    document.getElementById('role_ar').value = data.role_ar || '';
    document.getElementById('role_fr').value = data.role_fr || '';
    document.getElementById('rating').value = data.rating;
    document.getElementById('content_ar').value = data.content_ar || '';
    document.getElementById('content_fr').value = data.content_fr || '';
}

document.getElementById('reviewForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const response = await fetch('handlers/testimonial-handler.php', { method: 'POST', body: formData });
    const data = await response.json();
    if (data.success) location.reload();
});

async function deleteReview(id) {
    confirmAction(async () => {
        const formData = new FormData();
        formData.append('action', 'delete');
        formData.append('id', id);
        const response = await fetch('handlers/testimonial-handler.php', { method: 'POST', body: formData });
        if ((await response.json()).success) {
             showToast('success', 'تم حذف الرأي');
             setTimeout(() => location.reload(), 1000);
        }
    });
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>

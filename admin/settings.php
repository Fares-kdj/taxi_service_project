<?php include __DIR__ . '/includes/header.php'; ?>

<?php
$db = new Database();
$conn = $db->getConnection();

// Fetch settings
$stmt = $conn->query("SELECT * FROM site_settings");
$settings_raw = $stmt->fetchAll(PDO::FETCH_ASSOC);
$settings = [];
foreach ($settings_raw as $s) {
    $settings[$s['setting_key']] = $s['setting_value'];
}
?>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">إعدادات الموقع</h1>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <form id="settingsForm" class="space-y-6">
        <input type="hidden" name="action" value="update_settings">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <h3 class="text-lg font-semibold border-b pb-2">معلومات التواصل</h3>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">البريد الإلكتروني</label>
                    <input type="email" name="company_email" value="<?php echo htmlspecialchars($settings['company_email'] ?? ''); ?>" class="w-full px-3 py-2 border rounded-lg">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">رقم الهاتف</label>
                    <input type="text" name="company_phone" value="<?php echo htmlspecialchars($settings['company_phone'] ?? ''); ?>" class="w-full px-3 py-2 border rounded-lg">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">العنوان (بالعربية)</label>
                    <input type="text" name="company_address_ar" value="<?php echo htmlspecialchars($settings['company_address_ar'] ?? ''); ?>" class="w-full px-3 py-2 border rounded-lg">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">العنوان (بالفرنسية)</label>
                    <input type="text" name="company_address_fr" value="<?php echo htmlspecialchars($settings['company_address_fr'] ?? ''); ?>" class="w-full px-3 py-2 border rounded-lg" dir="ltr">
                </div>
            </div>

            <div class="space-y-4">
                <h3 class="text-lg font-semibold border-b pb-2">روابط التواصل الاجتماعي</h3>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Facebook</label>
                    <input type="text" name="facebook_url" value="<?php echo htmlspecialchars($settings['facebook_url'] ?? ''); ?>" class="w-full px-3 py-2 border rounded-lg">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Instagram</label>
                    <input type="text" name="instagram_url" value="<?php echo htmlspecialchars($settings['instagram_url'] ?? ''); ?>" class="w-full px-3 py-2 border rounded-lg">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp</label>
                    <input type="text" name="company_whatsapp" value="<?php echo htmlspecialchars($settings['company_whatsapp'] ?? ''); ?>" class="w-full px-3 py-2 border rounded-lg">
                </div>
            </div>
        </div>

        <div class="pt-6 border-t flex justify-end">
            <button type="submit" class="px-8 py-3 bg-gold-600 hover:bg-gold-700 text-white rounded-lg shadow-lg font-bold">
                حفظ التغييرات
            </button>
        </div>
    </form>
</div>

<script>
document.getElementById('settingsForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    try {
        const formData = new FormData(this);
        const response = await fetch('handlers/settings-handler.php', { method: 'POST', body: formData });
        const data = await response.json();
        if (data.success) {
            showToast('success', 'تم حفظ الإعدادات بنجاح');
        } else {
            showToast('error', 'فشل الحفظ');
        }
    } catch (error) { showToast('error', 'حدث خطأ في الاتصال'); }
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>

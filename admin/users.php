<?php include __DIR__ . '/includes/header.php'; ?>

<?php
$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-800">إدارة المستخدمين</h1>
    <button onclick="openModal()" class="px-6 py-2 bg-gold-600 hover:bg-gold-700 text-white rounded-lg shadow-lg">
        <i class="fas fa-plus ml-2"></i> إضافة مستخدم
    </button>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-right">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-6 py-4">الاسم</th>
                <th class="px-6 py-4">البريد الإلكتروني</th>
                <th class="px-6 py-4">الدور</th>
                <th class="px-6 py-4">تاريخ الإنشاء</th>
                <th class="px-6 py-4">إجراءات</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            <?php foreach ($users as $user): ?>
            <tr>
                <td class="px-6 py-4 font-medium"><?php echo htmlspecialchars($user['username']); ?></td>
                <td class="px-6 py-4 text-gray-600"><?php echo htmlspecialchars($user['email']); ?></td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                        <?php echo htmlspecialchars($user['role']); ?>
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500"><?php echo $user['created_at']; ?></td>
                <td class="px-6 py-4 flex gap-2">
                    <button onclick='editUser(<?php echo htmlspecialchars(json_encode($user), ENT_QUOTES); ?>)' class="text-blue-500"><i class="fas fa-edit"></i></button>
                    <?php if($_SESSION['admin_email'] !== $user['email']): ?>
                    <button onclick="deleteUser(<?php echo $user['id']; ?>)" class="text-red-500"><i class="fas fa-trash"></i></button>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div id="userModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal()"></div>
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-xl p-6">
            <h3 id="modalTitle" class="text-lg font-bold mb-4">إضافة مستخدم</h3>
            <form id="userForm" class="space-y-4">
                <input type="hidden" name="action" id="formAction" value="add">
                <input type="hidden" name="id" id="userId" value="">
                
                <input type="text" name="username" id="username" placeholder="اسم المستخدم" required class="w-full px-3 py-2 border rounded">
                <input type="email" name="email" id="email" placeholder="البريد الإلكتروني" required class="w-full px-3 py-2 border rounded">
                <input type="password" name="password" id="password" placeholder="كلمة المرور (اتركها فارغة إذا لم ترد التغيير)" class="w-full px-3 py-2 border rounded">
                
                <select name="role" id="role" class="w-full px-3 py-2 border rounded">
                    <option value="admin">مسؤول (Admin)</option>
                    <option value="editor">محرر (Editor)</option>
                </select>
                
                <button type="submit" class="w-full py-2 bg-gold-600 text-white rounded font-bold">حفظ</button>
            </form>
        </div>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('userModal').classList.remove('hidden');
    document.getElementById('userForm').reset();
    document.getElementById('formAction').value = 'add';
}
function closeModal() { document.getElementById('userModal').classList.add('hidden'); }

function editUser(user) {
    openModal();
    document.getElementById('formAction').value = 'edit';
    document.getElementById('userId').value = user.id;
    document.getElementById('username').value = user.username;
    document.getElementById('email').value = user.email;
    document.getElementById('role').value = user.role;
}

document.getElementById('userForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const response = await fetch('handlers/user-handler.php', { method: 'POST', body: formData });
    if ((await response.json()).success) location.reload();
});

async function deleteUser(id) {
    if(!confirm('حذف؟')) return;
    const formData = new FormData();
    formData.append('action', 'delete');
    formData.append('id', id);
    const response = await fetch('handlers/user-handler.php', { method: 'POST', body: formData });
    if ((await response.json()).success) location.reload();
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>

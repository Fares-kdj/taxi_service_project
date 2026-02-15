<?php
session_start();
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - لوحة التحكم</title>
    <link rel="icon" type="image/x-icon" href="../logo/favicon.ico">
    <!-- Mobile App Icons -->
    <link rel="icon" type="image/png" sizes="192x192" href="../logo/logo_android.png">
    <link rel="apple-touch-icon" sizes="180x180" href="../logo/logo_android.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
        <div class="p-8 text-center bg-gray-50 border-b border-gray-100">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-yellow-100 text-yellow-600 mb-4">
                <i class="fas fa-taxi text-3xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">لوحة التحكم</h1>
            <p class="text-gray-500 mt-1">فرسان الطريق لخدمات النقل</p>
        </div>
        
        <div class="p-8">
            <div id="error-message" class="hidden bg-red-50 text-red-600 p-4 rounded-lg mb-6 text-sm border border-red-100 flex items-center">
                <i class="fas fa-exclamation-circle ml-2"></i>
                <span id="error-text"></span>
            </div>

            <form id="loginForm" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <input type="email" name="email" required 
                            class="block w-full pr-10 pl-3 py-3 border border-gray-300 rounded-lg focus:ring-yellow-500 focus:border-yellow-500 transition-colors"
                            placeholder="admin@taxi.com">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">كلمة المرور</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                            <i class="fas fa-lock"></i>
                        </div>
                        <input type="password" name="password" required 
                            class="block w-full pr-10 pl-3 py-3 border border-gray-300 rounded-lg focus:ring-yellow-500 focus:border-yellow-500 transition-colors"
                            placeholder="••••••••">
                    </div>
                </div>
                
                <button type="submit" 
                    class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center gap-2">
                    <span>تسجيل الدخول</span>
                    <i class="fas fa-arrow-left text-sm"></i>
                </button>
            </form>
            
            <div class="mt-6 text-center text-sm text-gray-400">
                &copy; <?php echo date('Y'); ?> جميع الحقوق محفوظة
            </div>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const btn = this.querySelector('button');
            const originalContent = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التحقق...';
            
            const formData = new FormData(this);
            
            try {
                const response = await fetch('handlers/login-process', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    const errorDiv = document.getElementById('error-message');
                    document.getElementById('error-text').textContent = data.message;
                    errorDiv.classList.remove('hidden');
                    btn.disabled = false;
                    btn.innerHTML = originalContent;
                }
            } catch (error) {
                console.error('Error:', error);
                // Since login.php might not have the footer included with admin.js yet, we use a simple fallback or check
                if (typeof showToast === 'function') {
                    showToast('error', 'حدث خطأ في الاتصال');
                } else {
                   // Fallback to the error div instead of alert
                   const errorDiv = document.getElementById('error-message');
                   document.getElementById('error-text').textContent = 'حدث خطأ في الاتصال بالخادم';
                   errorDiv.classList.remove('hidden');
                }
                btn.disabled = false;
                btn.innerHTML = originalContent;
            }
        });
    </script>
</body>
</html>

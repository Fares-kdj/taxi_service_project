/**
 * Admin Panel JavaScript
 * Handles toast notifications and global interactions
 */

// Toast Configuration
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

function showToast(icon, title) {
    Toast.fire({
        icon: icon,
        title: title
    });
}

// Override default alert (optional, but safer to use explicit calls)
// window.alert = function(message) { showToast('info', message); }

function closeModal() {
    const modals = document.querySelectorAll('[id$="Modal"]');
    modals.forEach(modal => modal.classList.add('hidden'));
}

/**
 * Generic Confirmation Modal
 * @param {Function} callback - Function to execute if confirmed
 * @param {string} title - Modal title
 * @param {string} text - Modal text
 * @param {string} confirmText - Confirm button text
 */
function confirmAction(callback, title = 'هل أنت متأكد؟', text = 'لا يمكن التراجع عن هذا الإجراء', confirmText = 'نعم، احذف') {
    Swal.fire({
        title: title,
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: confirmText,
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            callback();
        }
    });
}

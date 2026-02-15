/**
 * Main JavaScript File
 * Handles booking form submission and other interactions
 */

document.addEventListener('DOMContentLoaded', function () {

    // Booking Form Submission
    const bookingForm = document.getElementById('bookingForm');

    if (bookingForm) {
        bookingForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            // Disable button and show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';

            try {
                const formData = new FormData(this);

                const response = await fetch('handlers/booking-handler.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        title: data.title,
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: data.btn_text,
                        confirmButtonColor: '#fb9605'
                    });
                    bookingForm.reset();
                } else {
                    Swal.fire({
                        title: 'خطأ',
                        text: data.message,
                        icon: 'error',
                        confirmButtonText: 'حاول مرة أخرى',
                        confirmButtonColor: '#333'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    title: 'خطأ',
                    text: 'حدث خطأ في الاتصال، يرجى المحاولة لاحقاً',
                    icon: 'error',
                    confirmButtonText: 'حسناً'
                });
            } finally {
                // Restore button state
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    }

    // Initialize SweetAlert2 Toast if available (for future use)
    window.showToast = function (icon, title) {
        if (typeof Swal !== 'undefined') {
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

            Toast.fire({
                icon: icon,
                title: title
            });
        } else {
            // Fallback
            alert(title);
        }
    };
});

// Scroll to top functionality
function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

window.addEventListener('scroll', function () {
    const scrollBtn = document.getElementById('scroll-top');
    if (scrollBtn) {
        if (window.scrollY > 300) {
            scrollBtn.classList.remove('hidden');
            scrollBtn.classList.add('flex');
        } else {
            scrollBtn.classList.add('hidden');
            scrollBtn.classList.remove('flex');
        }
    }
});

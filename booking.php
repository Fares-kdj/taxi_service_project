<?php include __DIR__ . '/includes/header.php'; ?>
<?php
$pre_price = isset($_GET['price']) ? htmlspecialchars($_GET['price']) : '';
$pre_from = isset($_GET['from']) ? htmlspecialchars($_GET['from']) : '';
$pre_to = isset($_GET['to']) ? htmlspecialchars($_GET['to']) : '';
$pre_service = isset($_GET['service']) ? (int)$_GET['service'] : 0;

// Fetch Euro price if coming from pricing page
$pre_price_euro = null;
if (!empty($pre_price) && !empty($pre_from) && !empty($pre_to)) {
    $euro_query = "SELECT price_euro FROM pricing WHERE price = ? AND (from_location_ar = ? OR from_location_fr = ?) AND (to_location_ar = ? OR to_location_fr = ?) LIMIT 1";
    $euro_stmt = $db->prepare($euro_query);
    $euro_stmt->execute([$pre_price, $pre_from, $pre_from, $pre_to, $pre_to]);
    $euro_result = $euro_stmt->fetch(PDO::FETCH_ASSOC);
    if ($euro_result) {
        $pre_price_euro = $euro_result['price_euro'];
    }
}

// Get first available service ID for default fallback
$default_service_id = 1; // Fallback default
$default_service_query = "SELECT id FROM services WHERE active = TRUE ORDER BY display_order ASC, id ASC LIMIT 1";
$default_service_stmt = $db->prepare($default_service_query);
$default_service_stmt->execute();
$default_service_result = $default_service_stmt->fetch(PDO::FETCH_ASSOC);
if ($default_service_result) {
    $default_service_id = $default_service_result['id'];
}
?>

<section class="py-12 bg-gray-50 dark:bg-gray-800 min-h-screen">
    <div class="container mx-auto px-4">
        
        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-3xl md:text-4xl font-bold mb-4"><?php echo t('book_now'); ?></h1>
            <div class="w-24 h-1 bg-gradient-to-r from-gold-500 to-gold-600 mx-auto mb-4"></div>
            <p class="text-gray-600 dark:text-gray-400">
                <?php echo $lang === 'ar' ? 'أكمل خطوات الحجز في ثوانٍ' : 'Complétez les étapes de réservation en quelques secondes'; ?>
            </p>
        </div>

        <div class="max-w-4xl mx-auto bg-white dark:bg-gray-900 rounded-2xl shadow-xl overflow-hidden">
            
            <!-- Stepper -->
            <div class="bg-gray-50 dark:bg-gray-800 p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between relative" dir="<?php echo $lang === 'ar' ? 'rtl' : 'ltr'; ?>">
                    
                    <!-- Step 1 Indicator -->
                    <div class="step-indicator relative z-10 flex flex-col items-center cursor-pointer" onclick="goToStep(1)">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center bg-gold-500 text-white font-bold shadow-lg transition-colors step-circle active">1</div>
                        <span class="text-xs mt-2 font-medium text-gray-600 dark:text-gray-400 hidden md:block"><?php echo $lang === 'ar' ? 'الرحلة' : 'Trajet'; ?></span>
                    </div>

                    <!-- Connector 1-2 -->
                    <div class="flex-1 h-1 bg-gray-200 dark:bg-gray-700 mx-2 relative rounded-full overflow-hidden">
                        <div class="absolute top-0 bottom-0 <?php echo $lang === 'ar' ? 'right-0' : 'left-0'; ?> bg-gold-500 transition-all duration-300" id="line1" style="width: 0%"></div>
                    </div>
                    
                    <!-- Step 2 Indicator -->
                    <div class="step-indicator relative z-10 flex flex-col items-center cursor-pointer" onclick="goToStep(2)">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 font-bold shadow transition-colors step-circle">2</div>
                        <span class="text-xs mt-2 font-medium text-gray-500 dark:text-gray-500 hidden md:block"><?php echo $lang === 'ar' ? 'الموعد' : 'Date'; ?></span>
                    </div>

                    <!-- Connector 2-3 -->
                    <div class="flex-1 h-1 bg-gray-200 dark:bg-gray-700 mx-2 relative rounded-full overflow-hidden">
                        <div class="absolute top-0 bottom-0 <?php echo $lang === 'ar' ? 'right-0' : 'left-0'; ?> bg-gold-500 transition-all duration-300" id="line2" style="width: 0%"></div>
                    </div>
                    
                    <!-- Step 3 Indicator -->
                    <div class="step-indicator relative z-10 flex flex-col items-center cursor-pointer" onclick="goToStep(3)">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 font-bold shadow transition-colors step-circle">3</div>
                        <span class="text-xs mt-2 font-medium text-gray-500 dark:text-gray-500 hidden md:block"><?php echo $lang === 'ar' ? 'المعلومات' : 'Infos'; ?></span>
                    </div>

                    <!-- Connector 3-4 -->
                    <div class="flex-1 h-1 bg-gray-200 dark:bg-gray-700 mx-2 relative rounded-full overflow-hidden">
                        <div class="absolute top-0 bottom-0 <?php echo $lang === 'ar' ? 'right-0' : 'left-0'; ?> bg-gold-500 transition-all duration-300" id="line3" style="width: 0%"></div>
                    </div>

                    <!-- Step 4 Indicator -->
                    <div class="step-indicator relative z-10 flex flex-col items-center cursor-pointer" onclick="goToStep(4)">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 font-bold shadow transition-colors step-circle">4</div>
                        <span class="text-xs mt-2 font-medium text-gray-500 dark:text-gray-500 hidden md:block"><?php echo $lang === 'ar' ? 'تأكيد' : 'Confirm'; ?></span>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <form id="wizardBookingForm" class="p-8" onsubmit="event.preventDefault(); return false;">
                <input type="hidden" name="price" value="<?php echo $pre_price; ?>">
                
                <!-- Step 1: Ride Details -->
                <div class="step-content block" id="step1">
                    <h3 class="text-xl font-bold mb-6 flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-gold-500"></i>
                        <?php echo $lang === 'ar' ? 'تفاصيل الرحلة' : 'Détails du Trajet'; ?>
                    </h3>
                    
                    <div class="space-y-6">
                        <?php if (empty($pre_price)): ?>
                        <div>
                            <label class="block text-sm font-semibold mb-2"><?php echo t('select_service'); ?></label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <?php
                                $services_query = "SELECT id, name_{$lang} as name, icon, price, price_euro, price_type FROM services WHERE active = TRUE ORDER BY display_order ASC";
                                $services_stmt = $db->prepare($services_query);
                                $services_stmt->execute();
                                $services = $services_stmt->fetchAll();
                                
                                foreach ($services as $service):
                                ?>
                                <label class="cursor-pointer">
                                    <input type="radio" name="service_id" value="<?php echo $service['id']; ?>" class="peer sr-only" <?php echo ($pre_service > 0 && $pre_service == $service['id']) ? 'checked' : ''; ?> required>
                                    <div class="p-4 rounded-xl border-2 border-gray-200 dark:border-gray-700 hover:border-gold-500 peer-checked:border-gold-500 peer-checked:bg-gold-50 dark:peer-checked:bg-gray-800 transition-all">
                                        <div class="flex items-center gap-4 mb-3">
                                            <div class="w-10 h-10 rounded-full bg-gold-100 flex items-center justify-center text-gold-600">
                                                <i class="fas <?php echo $service['icon'] ?: 'fa-taxi'; ?>"></i>
                                            </div>
                                            <span class="font-semibold text-gray-700 dark:text-gray-200"><?php echo $service['name']; ?></span>
                                        </div>
                                        <?php if ($service['price_type'] !== 'on_request' && $service['price'] > 0): ?>
                                        <div class="text-sm font-bold text-gold-600 dark:text-gold-500" dir="ltr">
                                            <?php echo displayPrice($service['price'], $service['price_euro'] ?? null); ?>
                                        </div>
                                        <?php else: ?>
                                        <div class="text-xs text-gray-500">
                                            <?php echo t('on_request'); ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php else: ?>
                        <!-- Pre-selected Trip -->
                        <div class="bg-gold-50 dark:bg-gray-800 p-6 rounded-xl border border-gold-200 dark:border-gray-700 flex items-center justify-between">
                            <div>
                                <span class="block text-xs text-gray-500 uppercase tracking-wide mb-1"><?php echo $lang === 'ar' ? 'عرض خاص' : 'Offre Spéciale'; ?></span>
                                <h4 class="font-bold text-lg text-gray-800 dark:text-white flex items-center gap-2">
                                    <?php echo $pre_from; ?> 
                                    <i class="fas <?php echo $lang === 'ar' ? 'fa-arrow-left' : 'fa-arrow-right'; ?> text-gold-500 text-sm"></i>
                                    <?php echo $pre_to; ?>
                                </h4>
                            </div>
                            <div class="text-2xl font-bold text-gold-600" dir="ltr"><?php echo displayPrice($pre_price, $pre_price_euro); ?></div>
                            <input type="hidden" name="price" value="<?php echo $pre_price; ?>">
                            <input type="hidden" name="price_euro" value="<?php echo $pre_price_euro; ?>">
                            <input type="hidden" name="from_pricing" value="1"> <!-- Flag to indicate booking from pricing page -->
                        </div>
                        <?php endif; ?>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold mb-2"><?php echo t('pickup_location'); ?> <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400"><i class="fas fa-map-pin"></i></div>
                                    <input type="text" name="pickup_location" id="pickup_location" value="<?php echo $pre_from; ?>" required class="w-full pr-10 pl-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-gold-500 dark:bg-gray-800">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2"><?php echo t('destination'); ?></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400"><i class="fas fa-flag-checkered"></i></div>
                                    <input type="text" name="destination" id="destination" value="<?php echo $pre_to; ?>" class="w-full pr-10 pl-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-gold-500 dark:bg-gray-800">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Date & Time -->
                <div class="step-content hidden" id="step2">
                    <h3 class="text-xl font-bold mb-6 flex items-center gap-2">
                        <i class="fas fa-calendar-alt text-gold-500"></i>
                        <?php echo $lang === 'ar' ? 'الموعد' : 'Date et Heure'; ?>
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-sm font-semibold mb-2"><?php echo t('booking_date'); ?> <span class="text-red-500">*</span></label>
                            <input type="date" name="booking_date" id="booking_date" min="<?php echo date('Y-m-d'); ?>" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-gold-500 dark:bg-gray-800">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2"><?php echo t('booking_time'); ?> <span class="text-red-500">*</span></label>
                            <input type="time" name="booking_time" id="booking_time" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-gold-500 dark:bg-gray-800">
                        </div>
                    </div>
                </div>

                <!-- Step 3: Personal Info -->
                <div class="step-content hidden" id="step3">
                    <h3 class="text-xl font-bold mb-6 flex items-center gap-2">
                        <i class="fas fa-user text-gold-500"></i>
                        <?php echo $lang === 'ar' ? 'المعلومات الشخصية' : 'Informations Personnelles'; ?>
                    </h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-semibold mb-2"><?php echo t('your_name'); ?> <span class="text-red-500">*</span></label>
                            <input type="text" name="customer_name" id="customer_name" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-gold-500 dark:bg-gray-800">
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold mb-2"><?php echo t('your_phone'); ?> <span class="text-red-500">*</span></label>
                                <input type="tel" name="phone" id="phone" required placeholder="+213..." class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-gold-500 dark:bg-gray-800">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2"><?php echo t('passengers'); ?></label>
                                <div class="flex items-center border border-gray-300 dark:border-gray-700 rounded-lg overflow-hidden">
                                    <button type="button" onclick="adjustPassengers(-1)" class="px-4 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:text-white">-</button>
                                    <input type="number" name="passengers" id="passengers" value="1" min="1" max="8" readonly class="w-full text-center py-3 border-none focus:ring-0 dark:bg-gray-800">
                                    <button type="button" onclick="adjustPassengers(1)" class="px-4 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:text-white">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Confirm -->
                <div class="step-content hidden" id="step4">
                    <h3 class="text-xl font-bold mb-6 flex items-center gap-2">
                        <i class="fas fa-check-circle text-gold-500"></i>
                        <?php echo $lang === 'ar' ? 'مراجعة وتأكيد' : 'Révision et Confirmation'; ?>
                    </h3>
                    
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-6 mb-6 text-sm">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="block text-gray-500 mb-1"><?php echo t('pickup_location'); ?></span>
                                <strong class="text-gray-800 dark:text-white" id="confirm_pickup">-</strong>
                            </div>
                            <div>
                                <span class="block text-gray-500 mb-1"><?php echo t('destination'); ?></span>
                                <strong class="text-gray-800 dark:text-white" id="confirm_destination">-</strong>
                            </div>
                            <div>
                                <span class="block text-gray-500 mb-1"><?php echo t('booking_date'); ?></span>
                                <strong class="text-gray-800 dark:text-white" id="confirm_date">-</strong>
                            </div>
                            <div>
                                <span class="block text-gray-500 mb-1"><?php echo t('booking_time'); ?></span>
                                <strong class="text-gray-800 dark:text-white" id="confirm_time">-</strong>
                            </div>
                            <div>
                                <span class="block text-gray-500 mb-1"><?php echo t('your_name'); ?></span>
                                <strong class="text-gray-800 dark:text-white" id="confirm_name">-</strong>
                            </div>
                            <div>
                                <span class="block text-gray-500 mb-1"><?php echo t('your_phone'); ?></span>
                                <strong class="text-gray-800 dark:text-white" id="confirm_phone">-</strong>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2"><?php echo t('notes'); ?></label>
                        <textarea name="notes" rows="3" placeholder="<?php echo $lang === 'ar' ? 'أي ملاحظات إضافية...' : 'Remarques supplémentaires...'; ?>" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-gold-500 dark:bg-gray-800"></textarea>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800 flex justify-between">
                    <button type="button" id="prevBtn" onclick="prevStep()" class="px-6 py-2 rounded-lg text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800 transition-colors hidden font-semibold">
                        <?php echo $lang === 'ar' ? 'السابق' : 'Précédent'; ?>
                    </button>
                    
                    <button type="button" id="nextBtn" onclick="nextStep()" class="px-8 py-3 bg-gradient-to-r from-gold-500 to-gold-600 text-white rounded-lg hover:from-gold-600 hover:to-gold-700 transition-all shadow-lg font-bold">
                        <?php echo $lang === 'ar' ? 'التالي' : 'Suivant'; ?>
                    </button>
                    
                    <button type="button" id="submitBtn" onclick="submitBooking()" class="px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all shadow-lg font-bold hidden">
                        <?php echo t('submit_booking'); ?> <i class="fas fa-check ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
let currentStep = 1;

function updateStepper() {
    // Note: totalSteps = 4
    
    // Update Lines
    if (currentStep > 1) document.getElementById('line1').style.width = '100%';
    else document.getElementById('line1').style.width = '0%';
    
    if (currentStep > 2) document.getElementById('line2').style.width = '100%';
    else document.getElementById('line2').style.width = '0%';
    
    if (currentStep > 3) document.getElementById('line3').style.width = '100%';
    else document.getElementById('line3').style.width = '0%';
    
    // Update Circles
    for (let i = 1; i <= 4; i++) {
        // Select logic is different now because we have more children. Use querySelectorAll on container or logic
        // But the previous loop relied on :nth-child which might break with inserted divs.
        // It's safer to use querySelectorAll('.step-indicator .step-circle')
        const circles = document.querySelectorAll('.step-indicator .step-circle');
        const circle = circles[i-1];
        
        if (i < currentStep) {
            circle.classList.remove('bg-gray-200', 'text-gray-500', 'bg-gold-500', 'text-white');
            circle.classList.add('bg-green-500', 'text-white'); // Completed
            circle.innerHTML = '<i class="fas fa-check"></i>';
        } else if (i === currentStep) {
            circle.classList.remove('bg-gray-200', 'text-gray-500', 'bg-green-500', 'text-white');
            circle.classList.add('bg-gold-500', 'text-white'); // Active
            circle.innerHTML = i;
        } else {
            circle.classList.remove('bg-gold-500', 'text-white', 'bg-green-500');
            circle.classList.add('bg-gray-200', 'text-gray-500'); // Pending
            circle.innerHTML = i;
        }
    }
}

function showStep(step) {
    // Hide all steps
    document.querySelectorAll('.step-content').forEach(el => el.classList.add('hidden'));
    
    // Show current step
    document.getElementById(`step${step}`).classList.remove('hidden');
    
    // Buttons visibility
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    
    step === 1 ? prevBtn.classList.add('hidden') : prevBtn.classList.remove('hidden');
    
    if (step === 4) {
        nextBtn.classList.add('hidden');
        submitBtn.classList.remove('hidden');
        updateConfirmation();
    } else {
        nextBtn.classList.remove('hidden');
        submitBtn.classList.add('hidden');
    }
    
    currentStep = step;
    updateStepper();
}

function validateStep(step) {
    const stepEl = document.getElementById(`step${step}`);
    const inputs = stepEl.querySelectorAll('input[required], select[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value) {
            isValid = false;
            input.classList.add('border-red-500');
            input.addEventListener('input', () => input.classList.remove('border-red-500'), {once: true});
        }
    });
    
    return isValid;
}

function nextStep() {
    if (validateStep(currentStep)) {
        showStep(currentStep + 1);
    } else {
        Swal.fire({
            icon: 'warning',
            title: <?php echo json_encode($lang === 'ar' ? 'تنبيه' : 'Attention'); ?>,
            text: <?php echo json_encode($lang === 'ar' ? 'يرجى ملء جميع الحقول المطلوبة' : 'Veuillez remplir tous les champs obligatoires'); ?>,
            confirmButtonColor: '#eab308'
        });
    }
}

function prevStep() {
    if (currentStep > 1) {
        showStep(currentStep - 1);
    }
}

function goToStep(step) {
    // Only allow going back or to next step if current is valid
    if (step < currentStep) {
        showStep(step);
    }
}

function adjustPassengers(change) {
    const input = document.getElementById('passengers');
    let val = parseInt(input.value) + change;
    if (val >= 1 && val <= 8) input.value = val;
}

function updateConfirmation() {
    document.getElementById('confirm_pickup').textContent = document.getElementById('pickup_location').value;
    document.getElementById('confirm_destination').textContent = document.getElementById('destination').value || '-';
    document.getElementById('confirm_date').textContent = document.getElementById('booking_date').value;
    document.getElementById('confirm_time').textContent = document.getElementById('booking_time').value;
    document.getElementById('confirm_name').textContent = document.getElementById('customer_name').value;
    document.getElementById('confirm_phone').textContent = document.getElementById('phone').value;
}

async function submitBooking() {
    const btn = document.getElementById('submitBtn');
    const form = document.getElementById('wizardBookingForm');
    const originalContent = btn.innerHTML;
    
    // Validate Step 4 (Optional, usually notes only)
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ...';

    try {
        const formData = new FormData(form);
        // Add service_id if selecting from cards (radio buttons)
        const selectedService = document.querySelector('input[name="service_id"]:checked');
        if (selectedService && !formData.has('service_id')) {
            formData.append('service_id', selectedService.value);
        }

        const response = await fetch('handlers/booking-handler.php', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();
        
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: data.title || <?php echo json_encode($lang === 'ar' ? 'تم استلام طلبك!' : 'Demande Reçue!'); ?>,
                text: data.message || <?php echo json_encode($lang === 'ar' ? 'سنتواصل معك قريباً.' : 'Nous vous contacterons bientôt.'); ?>,
                confirmButtonText: data.btn_text || 'OK',
                confirmButtonColor: '#eab308'
            }).then(() => {
                window.location.href = 'index.php';
            });
        } else {
            throw new Error(data.message);
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: <?php echo json_encode($lang === 'ar' ? 'خطأ' : 'Erreur'); ?>,
            text: error.message || <?php echo json_encode($lang === 'ar' ? 'حدث خطأ غير متوقع' : 'Une erreur inattendue s\'est produite'); ?>,
            confirmButtonColor: '#eab308'
        });
        btn.disabled = false;
        btn.innerHTML = originalContent;
    }
}

// Initialize
updateStepper();
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>

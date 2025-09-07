 @php
    $methods = [
        ['code' => 'bca_va', 'name' => 'BCA Virtual Account', 'type' => 'bank_transfer', 'fee' => 4000],
        ['code' => 'bni_va', 'name' => 'BNI Virtual Account', 'type' => 'bank_transfer', 'fee' => 4000],
        ['code' => 'bri_va', 'name' => 'BRI Virtual Account', 'type' => 'bank_transfer', 'fee' => 4000],
        ['code' => 'qris', 'name' => 'QRIS', 'type' => 'e_wallet', 'fee' => 0],
        ['code' => 'gopay', 'name' => 'GoPay', 'type' => 'e_wallet', 'fee' => 0],
        ['code' => 'shopeepay', 'name' => 'ShopeePay', 'type' => 'e_wallet', 'fee' => 0],
        ['code' => 'indomaret', 'name' => 'Indomaret', 'type' => 'retail', 'fee' => 2500],
        ['code' => 'alfamart', 'name' => 'Alfamart', 'type' => 'retail', 'fee' => 2500],
    ];
@endphp 
<!-- Flip Payment Modal -->
<div id="flip-payment-modal" class="fixed inset-0 z-50 bg-black bg-opacity-60 backdrop-blur-sm hidden transition-all duration-300">
    <div class="flex items-center justify-center min-h-screen px-4 py-6">
        <div class="relative w-full max-w-2xl mx-auto bg-white rounded-2xl shadow-2xl transform transition-all duration-300 scale-95 opacity-0" id="modal-content">
            
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900">Pilih Metode Pembayaran</h3>
                    <p class="text-sm text-gray-600 mt-1">Bayar dengan mudah menggunakan berbagai metode</p>
                </div>
                <button type="button" onclick="closeFlipModal()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <span class="material-symbols-rounded">close</span>
                </button>
            </div>

            <!-- Content -->
            <div class="max-h-96 overflow-y-auto p-6">
                <form id="flip-payment-form" method="POST" action="{{ route('payment.online') }}">
                    @csrf
                    
                    <!-- Transfer input fields from main form -->
                    <div class="hidden">
                        <input type="hidden" name="user_id" id="flip-user-id">
                        <input type="hidden" name="name" id="flip-name">
                        <input type="hidden" name="email" id="flip-email">
                        <input type="hidden" name="phone" id="flip-phone">
                        <input type="hidden" name="room_id" id="flip-room-id">
                        <input type="hidden" name="check_in" id="flip-check-in">
                        <input type="hidden" name="check_out" id="flip-check-out">
                        <input type="hidden" name="payment_method_detail" id="flip-payment-method">
                        <div id="flip-accommodation-plans"></div>
                        <div id="flip-promos"></div>
                    </div>

                    <!-- Virtual Account Section -->
                    <div class="mb-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <span class="material-symbols-rounded mr-2 text-blue-600">account_balance</span>
                            Virtual Account
                        </h4>
                        <div class="grid grid-cols-2 gap-3">
                               
                            @foreach ($methods as $method)
                                @if ($method['type'] === 'bank_transfer')
                                    <label for="payment-{{ $method['code'] }}" class="payment-method-option cursor-pointer">
                                        <input type="radio" 
                                               name="selected_payment_method" 
                                               id="payment-{{ $method['code'] }}" 
                                               value="{{ $method['code'] }}" 
                                               class="hidden peer"
                                               data-name="{{ $method['name'] }}"
                                               data-fee="{{ $method['fee'] }}">
                                        <div class="flex items-center p-4 border-2 border-gray-200 rounded-xl hover:border-primary-300 peer-checked:border-primary-500 peer-checked:bg-primary-50 transition-all">
                                            <div class="w-10 h-10 mr-3 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <!-- Bank icon placeholder -->
                                                <span class="material-symbols-rounded text-gray-600">account_balance</span>
                                            </div>
                                            <div class="flex-1">
                                                <div class="text-sm font-medium text-gray-900">{{ $method['name'] }}</div>
                                                <div class="text-xs text-gray-500">
                                                    @if ($method['fee'] > 0)
                                                        Biaya admin: Rp {{ number_format($method['fee'], 0, ',', '.') }}
                                                    @else
                                                        Gratis biaya admin
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="ml-2">
                                                <div class="w-5 h-5 border-2 border-gray-300 rounded-full peer-checked:border-primary-500 peer-checked:bg-primary-500 flex items-center justify-center">
                                                    <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- E-Wallet Section -->
                    <div class="mb-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <span class="material-symbols-rounded mr-2 text-green-600">wallet</span>
                            E-Wallet
                        </h4>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach ($methods as $method)
                                @if ($method['type'] === 'e_wallet')
                                    <label for="payment-{{ $method['code'] }}" class="payment-method-option cursor-pointer">
                                        <input type="radio" 
                                               name="selected_payment_method" 
                                               id="payment-{{ $method['code'] }}" 
                                               value="{{ $method['code'] }}" 
                                               class="hidden peer"
                                               data-name="{{ $method['name'] }}"
                                               data-fee="{{ $method['fee'] }}">
                                        <div class="flex items-center p-4 border-2 border-gray-200 rounded-xl hover:border-primary-300 peer-checked:border-primary-500 peer-checked:bg-primary-50 transition-all">
                                            <div class="w-10 h-10 mr-3 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <!-- E-wallet icon placeholder -->
                                                <span class="material-symbols-rounded text-gray-600">
                                                    @if ($method['code'] === 'qris')
                                                        qr_code
                                                    @else
                                                        wallet
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="flex-1">
                                                <div class="text-sm font-medium text-gray-900">{{ $method['name'] }}</div>
                                                <div class="text-xs text-gray-500">
                                                    @if ($method['fee'] > 0)
                                                        Biaya admin: Rp {{ number_format($method['fee'], 0, ',', '.') }}
                                                    @else
                                                        Gratis biaya admin
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="ml-2">
                                                <div class="w-5 h-5 border-2 border-gray-300 rounded-full peer-checked:border-primary-500 peer-checked:bg-primary-500 flex items-center justify-center">
                                                    <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Retail Section -->
                    <div class="mb-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <span class="material-symbols-rounded mr-2 text-orange-600">store</span>
                            Retail/Minimarket
                        </h4>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach ($methods as $method)
                                @if ($method['type'] === 'retail')
                                    <label for="payment-{{ $method['code'] }}" class="payment-method-option cursor-pointer">
                                        <input type="radio" 
                                               name="selected_payment_method" 
                                               id="payment-{{ $method['code'] }}" 
                                               value="{{ $method['code'] }}" 
                                               class="hidden peer"
                                               data-name="{{ $method['name'] }}"
                                               data-fee="{{ $method['fee'] }}">
                                        <div class="flex items-center p-4 border-2 border-gray-200 rounded-xl hover:border-primary-300 peer-checked:border-primary-500 peer-checked:bg-primary-50 transition-all">
                                            <div class="w-10 h-10 mr-3 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <!-- Retail icon placeholder -->
                                                <span class="material-symbols-rounded text-gray-600">store</span>
                                            </div>
                                            <div class="flex-1">
                                                <div class="text-sm font-medium text-gray-900">{{ $method['name'] }}</div>
                                                <div class="text-xs text-gray-500">
                                                    @if ($method['fee'] > 0)
                                                        Biaya admin: Rp {{ number_format($method['fee'], 0, ',', '.') }}
                                                    @else
                                                        Gratis biaya admin
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="ml-2">
                                                <div class="w-5 h-5 border-2 border-gray-300 rounded-full peer-checked:border-primary-500 peer-checked:bg-primary-500 flex items-center justify-center">
                                                    <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="p-6 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <div class="text-sm text-gray-600">Total Pembayaran</div>
                        <div class="text-lg font-semibold text-gray-900" id="modal-total-amount">Rp 0</div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-600">Biaya Admin</div>
                        <div class="text-lg font-medium text-gray-700" id="modal-admin-fee">+ Rp 0</div>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <button type="button" 
                            onclick="closeFlipModal()" 
                            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 bg-white rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="button" 
                            onclick="processFlipPayment()" 
                            id="flip-submit-btn"
                            class="flex-1 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors disabled:bg-gray-300 disabled:cursor-not-allowed"
                            disabled>
                        Lanjutkan Pembayaran
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.payment-method-option input[type="radio"]:checked + div .w-5 {
    border-color: #5b3a1f;
    background-color: #5b3a1f;
}

.payment-method-option input[type="radio"]:checked + div .w-5 .w-2 {
    opacity: 1;
}

.payment-method-option input[type="radio"]:checked + div {
    border-color: #5b3a1f;
    background-color: #fef7ed;
}

#flip-payment-modal.show #modal-content {
    transform: scale(1);
    opacity: 1;
}
</style>

<script>
function openFlipModal() {
    // Copy form data from main checkout form
    copyFormDataToModal();
    
    // Show modal with animation
    const modal = document.getElementById('flip-payment-modal');
    const content = document.getElementById('modal-content');
    
    modal.classList.remove('hidden');
    
    // Trigger animation
    setTimeout(() => {
        modal.classList.add('show');
    }, 10);
    
    // Prevent body scroll
    document.body.classList.add('overflow-hidden');
}

function closeFlipModal() {
    const modal = document.getElementById('flip-payment-modal');
    const content = document.getElementById('modal-content');
    
    // Remove animation classes
    modal.classList.remove('show');
    
    // Hide modal after animation
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        
        // Reset form
        document.getElementById('flip-payment-form').reset();
        updateModalTotals();
    }, 300);
}

function copyFormDataToModal() {
    // Copy basic form data
    const mainForm = document.getElementById('payment-form');
    
    document.getElementById('flip-user-id').value = mainForm.querySelector('[name="user_id"]')?.value || '';
    document.getElementById('flip-name').value = mainForm.querySelector('[name="name"]')?.value || '';
    document.getElementById('flip-email').value = mainForm.querySelector('[name="email"]')?.value || '';
    document.getElementById('flip-phone').value = mainForm.querySelector('[name="phone"]')?.value || '';
    document.getElementById('flip-room-id').value = mainForm.querySelector('[name="room_id"]')?.value || '';
    document.getElementById('flip-check-in').value = mainForm.querySelector('[name="check_in"]')?.value || '';
    document.getElementById('flip-check-out').value = mainForm.querySelector('[name="check_out"]')?.value || '';
    
    // Copy accommodation plans
    const accommodationPlansContainer = document.getElementById('flip-accommodation-plans');
    accommodationPlansContainer.innerHTML = '';
    
    mainForm.querySelectorAll('[name="accomodation_plan_id[]"]:checked').forEach((checkbox, index) => {
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'accomodation_plan_id[]';
        hiddenInput.value = checkbox.value;
        accommodationPlansContainer.appendChild(hiddenInput);
    });
    
    // Copy promos
    const promosContainer = document.getElementById('flip-promos');
    promosContainer.innerHTML = '';
    
    mainForm.querySelectorAll('[name="promo_id[]"]:checked').forEach((checkbox, index) => {
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'promo_id[]';
        hiddenInput.value = checkbox.value;
        promosContainer.appendChild(hiddenInput);
    });
    
    // Update modal totals
    updateModalTotals();
}

function updateModalTotals() {
    const totalAmountElement = document.getElementById('total-price');
    const totalAmount = totalAmountElement ? totalAmountElement.textContent.replace(/[^0-9]/g, '') : '0';
    
    document.getElementById('modal-total-amount').textContent = 
        totalAmount ? `Rp ${parseInt(totalAmount).toLocaleString('id-ID')}` : 'Rp 0';
    
    // Update admin fee based on selected payment method
    const selectedMethod = document.querySelector('[name="selected_payment_method"]:checked');
    const adminFee = selectedMethod ? parseInt(selectedMethod.dataset.fee) : 0;
    
    document.getElementById('modal-admin-fee').textContent = `+ Rp ${adminFee.toLocaleString('id-ID')}`;
}

function processFlipPayment() {
    const selectedMethod = document.querySelector('[name="selected_payment_method"]:checked');
    
    if (!selectedMethod) {
        alert('Silakan pilih metode pembayaran terlebih dahulu');
        return;
    }
    
    // Set payment method detail
    document.getElementById('flip-payment-method').value = selectedMethod.value;
    
    // Show loading state
    const submitBtn = document.getElementById('flip-submit-btn');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Memproses...';
    
    // Submit form
    document.getElementById('flip-payment-form').submit();
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Listen for payment method changes
    document.querySelectorAll('[name="selected_payment_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            updateModalTotals();
            
            // Enable submit button
            const submitBtn = document.getElementById('flip-submit-btn');
            submitBtn.disabled = false;
            
            // Update payment method name display
            const methodName = this.dataset.name;
            submitBtn.textContent = `Bayar dengan ${methodName}`;
        });
    });
    
    // Close modal on outside click
    document.getElementById('flip-payment-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeFlipModal();
        }
    });
    
    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('flip-payment-modal').classList.contains('hidden')) {
            closeFlipModal();
        }
    });
});
</script>
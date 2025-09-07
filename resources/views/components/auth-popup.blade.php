<!-- Auth Popup Modal -->
<div id="authModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 backdrop-blur-sm">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b">
                <h3 id="modalTitle" class="text-xl font-semibold text-gray-900">Masuk ke Akun Anda</h3>
                <button id="closeAuthModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <!-- Login Form -->
                <form id="loginForm" class="space-y-4">
                    @csrf
                    <div id="loginError" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded"></div>
                    
                    <div>
                        <label for="loginPhone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                        <input type="text" id="loginPhone" name="phone" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>

                    <div>
                        <label for="loginPassword" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <input type="password" id="loginPassword" name="password" required 
                                   class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            <button type="button" id="toggleLoginPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <svg id="loginEyeOpen" class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg id="loginEyeClosed" class="h-5 w-5 text-gray-400 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" id="loginSubmit" 
                            class="w-full bg-primary-600 text-white py-2 px-4 rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition duration-200">
                        <span id="loginButtonText">Masuk</span>
                        <div id="loginSpinner" class="hidden inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin ml-2"></div>
                    </button>
                </form>

                <!-- Register Form -->
                <form id="registerForm" class="space-y-4 hidden">
                    @csrf
                    <div id="registerError" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded"></div>
                    
                    <div>
                        <label for="registerName" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" id="registerName" name="name" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>

                    <div>
                        <label for="registerEmail" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" id="registerEmail" name="email" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>

                    <div>
                        <label for="registerPhone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                        <input type="text" id="registerPhone" name="phone" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>

                    <div>
                        <label for="registerPassword" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <input type="password" id="registerPassword" name="password" required 
                                   class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            <button type="button" id="toggleRegisterPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <svg id="registerEyeOpen" class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg id="registerEyeClosed" class="h-5 w-5 text-gray-400 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label for="registerPasswordConfirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                        <div class="relative">
                            <input type="password" id="registerPasswordConfirmation" name="password_confirmation" required 
                                   class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            <button type="button" id="toggleRegisterPasswordConfirmation" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <svg id="registerConfirmEyeOpen" class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg id="registerConfirmEyeClosed" class="h-5 w-5 text-gray-400 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" id="registerSubmit" 
                            class="w-full bg-primary-600 text-white py-2 px-4 rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition duration-200">
                        <span id="registerButtonText">Daftar</span>
                        <div id="registerSpinner" class="hidden inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin ml-2"></div>
                    </button>
                </form>

                <!-- OTP Verification Form -->
                <form id="otpForm" class="space-y-4 hidden">
                    @csrf
                    <div id="otpError" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded"></div>
                    <div id="otpSuccess" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded"></div>
                    
                    <div class="text-center">
                        <p class="text-sm text-gray-600 mb-4">Kode OTP telah dikirim ke nomor telepon Anda. Silahkan masukkan kode untuk melanjutkan.</p>
                    </div>

                    <div>
                        <label for="otpCode" class="block text-sm font-medium text-gray-700 mb-2">Kode OTP</label>
                        <input type="text" id="otpCode" name="otp" required maxlength="6" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-center text-lg tracking-widest">
                    </div>

                    <button type="submit" id="otpSubmit" 
                            class="w-full bg-primary-600 text-white py-2 px-4 rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition duration-200">
                        <span id="otpButtonText">Verifikasi</span>
                        <div id="otpSpinner" class="hidden inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin ml-2"></div>
                    </button>
                </form>

                <!-- Toggle between Login and Register -->
                <div class="mt-6 text-center" id="authToggle">
                    <p class="text-sm text-gray-600">
                        <span id="toggleText">Belum punya akun?</span>
                        <button type="button" id="toggleAuthMode" class="text-primary-600 hover:text-primary-700 font-medium">
                            <span id="toggleButtonText">Daftar di sini</span>
                        </button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modal elements
    const authModal = document.getElementById('authModal');
    const closeAuthModal = document.getElementById('closeAuthModal');
    const modalTitle = document.getElementById('modalTitle');
    
    // Forms
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const otpForm = document.getElementById('otpForm');
    
    // Toggle elements
    const toggleAuthMode = document.getElementById('toggleAuthMode');
    const toggleText = document.getElementById('toggleText');
    const toggleButtonText = document.getElementById('toggleButtonText');
    const authToggle = document.getElementById('authToggle');
    
    // Error elements
    const loginError = document.getElementById('loginError');
    const registerError = document.getElementById('registerError');
    const otpError = document.getElementById('otpError');
    const otpSuccess = document.getElementById('otpSuccess');
    
    let isLoginMode = true;
    let currentUserId = null;
    let redirectUrl = null;

    // Password toggle functionality
    function setupPasswordToggle(toggleId, inputId, eyeOpenId, eyeClosedId) {
        const toggle = document.getElementById(toggleId);
        const input = document.getElementById(inputId);
        const eyeOpen = document.getElementById(eyeOpenId);
        const eyeClosed = document.getElementById(eyeClosedId);
        
        toggle.addEventListener('click', function() {
            if (input.type === 'password') {
                input.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        });
    }

    // Setup password toggles
    setupPasswordToggle('toggleLoginPassword', 'loginPassword', 'loginEyeOpen', 'loginEyeClosed');
    setupPasswordToggle('toggleRegisterPassword', 'registerPassword', 'registerEyeOpen', 'registerEyeClosed');
    setupPasswordToggle('toggleRegisterPasswordConfirmation', 'registerPasswordConfirmation', 'registerConfirmEyeOpen', 'registerConfirmEyeClosed');

    // Show modal function
    window.showAuthModal = function(redirect = null) {
        redirectUrl = redirect;
        authModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    };

    // Hide modal function
    function hideAuthModal() {
        authModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        resetForms();
    }

    // Reset forms
    function resetForms() {
        loginForm.reset();
        registerForm.reset();
        otpForm.reset();
        hideErrors();
        showLoginForm();
    }

    // Hide all error messages
    function hideErrors() {
        loginError.classList.add('hidden');
        registerError.classList.add('hidden');
        otpError.classList.add('hidden');
        otpSuccess.classList.add('hidden');
    }

    // Show error message
    function showError(element, message) {
        element.textContent = message;
        element.classList.remove('hidden');
    }

    // Show success message
    function showSuccess(element, message) {
        element.textContent = message;
        element.classList.remove('hidden');
    }

    // Toggle between login and register
    function toggleAuthForm() {
        hideErrors();
        
        if (isLoginMode) {
            showRegisterForm();
        } else {
            showLoginForm();
        }
    }

    function showLoginForm() {
        isLoginMode = true;
        modalTitle.textContent = 'Masuk ke Akun Anda';
        loginForm.classList.remove('hidden');
        registerForm.classList.add('hidden');
        otpForm.classList.add('hidden');
        authToggle.classList.remove('hidden');
        toggleText.textContent = 'Belum punya akun?';
        toggleButtonText.textContent = 'Daftar di sini';
    }

    function showRegisterForm() {
        isLoginMode = false;
        modalTitle.textContent = 'Buat Akun Baru';
        loginForm.classList.add('hidden');
        registerForm.classList.remove('hidden');
        otpForm.classList.add('hidden');
        authToggle.classList.remove('hidden');
        toggleText.textContent = 'Sudah punya akun?';
        toggleButtonText.textContent = 'Masuk di sini';
    }

    function showOtpForm() {
        modalTitle.textContent = 'Verifikasi OTP';
        loginForm.classList.add('hidden');
        registerForm.classList.add('hidden');
        otpForm.classList.remove('hidden');
        authToggle.classList.add('hidden');
    }

    // Event listeners
    closeAuthModal.addEventListener('click', hideAuthModal);
    toggleAuthMode.addEventListener('click', toggleAuthForm);

    // Close modal when clicking outside
    authModal.addEventListener('click', function(e) {
        if (e.target === authModal) {
            hideAuthModal();
        }
    });

    // Login form submission
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        hideErrors();
        
        const submitButton = document.getElementById('loginSubmit');
        const buttonText = document.getElementById('loginButtonText');
        const spinner = document.getElementById('loginSpinner');
        
        // Show loading state
        submitButton.disabled = true;
        buttonText.textContent = 'Memproses...';
        spinner.classList.remove('hidden');
        
        const formData = new FormData(loginForm);
        if (redirectUrl) {
            formData.append('redirect_url', redirectUrl);
        }
        
        fetch('{{ route("ajax.login") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirect to checkout or specified URL
                window.location.href = data.redirect_url;
            } else {
                showError(loginError, data.message);
            }
        })
        .catch(error => {
            showError(loginError, 'Terjadi kesalahan. Silahkan coba lagi.');
        })
        .finally(() => {
            // Reset loading state
            submitButton.disabled = false;
            buttonText.textContent = 'Masuk';
            spinner.classList.add('hidden');
        });
    });

    // Register form submission
    registerForm.addEventListener('submit', function(e) {
        e.preventDefault();
        hideErrors();
        
        const submitButton = document.getElementById('registerSubmit');
        const buttonText = document.getElementById('registerButtonText');
        const spinner = document.getElementById('registerSpinner');
        
        // Show loading state
        submitButton.disabled = true;
        buttonText.textContent = 'Memproses...';
        spinner.classList.remove('hidden');
        
        const formData = new FormData(registerForm);
        
        fetch('{{ route("ajax.register") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                currentUserId = data.user_id;
                showSuccess(otpSuccess, data.message);
                showOtpForm();
            } else {
                showError(registerError, data.message);
            }
        })
        .catch(error => {
            showError(registerError, 'Terjadi kesalahan. Silahkan coba lagi.');
        })
        .finally(() => {
            // Reset loading state
            submitButton.disabled = false;
            buttonText.textContent = 'Daftar';
            spinner.classList.add('hidden');
        });
    });

    // OTP form submission
    otpForm.addEventListener('submit', function(e) {
        e.preventDefault();
        hideErrors();
        
        const submitButton = document.getElementById('otpSubmit');
        const buttonText = document.getElementById('otpButtonText');
        const spinner = document.getElementById('otpSpinner');
        
        // Show loading state
        submitButton.disabled = true;
        buttonText.textContent = 'Memverifikasi...';
        spinner.classList.remove('hidden');
        
        const formData = new FormData(otpForm);
        formData.append('user_id', currentUserId);
        if (redirectUrl) {
            formData.append('redirect_url', redirectUrl);
        }
        
        fetch('{{ route("ajax.verify.otp") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess(otpSuccess, data.message);
                setTimeout(() => {
                    window.location.href = data.redirect_url;
                }, 1500);
            } else {
                showError(otpError, data.message);
            }
        })
        .catch(error => {
            showError(otpError, 'Terjadi kesalahan. Silahkan coba lagi.');
        })
        .finally(() => {
            // Reset loading state
            submitButton.disabled = false;
            buttonText.textContent = 'Verifikasi';
            spinner.classList.add('hidden');
        });
    });
});
</script>

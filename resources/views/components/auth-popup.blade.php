{{-- Auth Modal Popup --}}
<div id="authModal" class="fixed inset-0 z-50 bg-black bg-opacity-60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-auto relative">
        {{-- Close Button --}}
        <button id="closeAuthModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
            <span class="material-symbols-rounded text-2xl">close</span>
        </button>

        {{-- Modal Content --}}
        <div class="p-8">
            {{-- Modal Title --}}
            <h2 id="modalTitle" class="text-2xl font-bold text-primary-700 mb-6 text-center">Masuk ke Akun Anda</h2>

            {{-- Login Form --}}
            <form id="loginForm" class="space-y-4">
                @csrf
                <div id="loginError" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"></div>
                
                <div>
                    <label for="loginPhone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                    <input type="tel" id="loginPhone" name="phone" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                           placeholder="Masukkan nomor telepon">
                </div>
                
                <div>
                    <label for="loginPassword" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    {{-- Added password toggle functionality --}}
                    <div class="relative">
                        <input type="password" id="loginPassword" name="password" required 
                               class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                               placeholder="Masukkan password">
                        <button type="button" id="toggleLoginPassword" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                            <span class="material-symbols-rounded text-xl">visibility</span>
                        </button>
                    </div>
                </div>
                
                <button type="submit" class="w-full bg-primary-500 text-white py-3 rounded-lg font-medium hover:bg-primary-600 transition-colors">
                    Masuk
                </button>
                
                <p class="text-center text-sm text-gray-600 mt-4">
                    Belum punya akun? 
                    <button type="button" id="showRegister" class="text-primary-500 hover:text-primary-600 font-medium">Daftar di sini</button>
                </p>
            </form>

            {{-- Register Form --}}
            <form id="registerForm" class="space-y-4 hidden">
                @csrf
                <div id="registerError" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"></div>
                
                <div>
                    <label for="registerName" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" id="registerName" name="name" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                           placeholder="Masukkan nama lengkap">
                </div>
                
                <div>
                    <label for="registerEmail" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" id="registerEmail" name="email" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                           placeholder="Masukkan email">
                </div>
                
                <div>
                    <label for="registerPhone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                    <input type="tel" id="registerPhone" name="phone" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                           placeholder="Masukkan nomor telepon">
                </div>
                
                <div>
                    <label for="registerPassword" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    {{-- Added password toggle functionality --}}
                    <div class="relative">
                        <input type="password" id="registerPassword" name="password" required 
                               class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                               placeholder="Masukkan password">
                        <button type="button" id="toggleRegisterPassword" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                            <span class="material-symbols-rounded text-xl">visibility</span>
                        </button>
                    </div>
                </div>
                
                <div>
                    <label for="registerPasswordConfirm" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                    {{-- Added password toggle functionality --}}
                    <div class="relative">
                        <input type="password" id="registerPasswordConfirm" name="password_confirmation" required 
                               class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                               placeholder="Konfirmasi password">
                        <button type="button" id="toggleRegisterPasswordConfirm" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                            <span class="material-symbols-rounded text-xl">visibility</span>
                        </button>
                    </div>
                </div>
                
                <button type="submit" class="w-full bg-primary-500 text-white py-3 rounded-lg font-medium hover:bg-primary-600 transition-colors">
                    Daftar
                </button>
                
                <p class="text-center text-sm text-gray-600 mt-4">
                    Sudah punya akun? 
                    <button type="button" id="showLogin" class="text-primary-500 hover:text-primary-600 font-medium">Masuk di sini</button>
                </p>
            </form>

            {{-- OTP Verification Form --}}
            <form id="otpForm" class="space-y-4 hidden">
                @csrf
                <div id="otpError" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"></div>
                
                <div class="text-center mb-4">
                    <p class="text-sm text-gray-600">Kode OTP telah dikirim ke nomor telepon Anda</p>
                </div>
                
                <div>
                    <label for="otpCode" class="block text-sm font-medium text-gray-700 mb-2">Kode OTP</label>
                    <input type="text" id="otpCode" name="otp" required maxlength="6"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-center text-2xl tracking-widest"
                           placeholder="000000">
                </div>
                
                <input type="hidden" id="otpUserId" name="user_id">
                
                <button type="submit" class="w-full bg-primary-500 text-white py-3 rounded-lg font-medium hover:bg-primary-600 transition-colors">
                    Verifikasi
                </button>
                
                <p class="text-center text-sm text-gray-600 mt-4">
                    Tidak menerima kode? 
                    <button type="button" id="resendOtp" class="text-primary-500 hover:text-primary-600 font-medium">Kirim ulang</button>
                </p>
            </form>
        </div>
    </div>
</div>

{{-- Added JavaScript for password toggle functionality --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password toggle functionality
    function setupPasswordToggle(toggleButtonId, passwordInputId) {
        const toggleButton = document.getElementById(toggleButtonId);
        const passwordInput = document.getElementById(passwordInputId);
        const icon = toggleButton.querySelector('.material-symbols-rounded');
        
        if (toggleButton && passwordInput) {
            toggleButton.addEventListener('click', function() {
                const isPassword = passwordInput.type === 'password';
                passwordInput.type = isPassword ? 'text' : 'password';
                icon.textContent = isPassword ? 'visibility_off' : 'visibility';
            });
        }
    }
    
    // Setup password toggles for all password fields
    setupPasswordToggle('toggleLoginPassword', 'loginPassword');
    setupPasswordToggle('toggleRegisterPassword', 'registerPassword');
    setupPasswordToggle('toggleRegisterPasswordConfirm', 'registerPasswordConfirm');
});
</script>

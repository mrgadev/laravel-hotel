<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Saldo;
use App\Traits\Fonnte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Exception;

class AjaxAuthController extends Controller
{
    use Fonnte; // Add the Fonnte trait

    /**
     * Handle AJAX login request
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'phone' => ['required', 'string', 'min:10', 'max:20'],
                'password' => ['required', 'string', 'min:1'],
            ], [
                'phone.required' => 'Nomor telepon harus diisi.',
                'phone.min' => 'Nomor telepon minimal 10 karakter.',
                'phone.max' => 'Nomor telepon maksimal 20 karakter.',
                'password.required' => 'Password harus diisi.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak valid.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $credentials = $request->only('phone', 'password');

            // Check if user exists and is verified
            $user = User::where('phone', $credentials['phone'])->first();
            
            if ($user && $user->access === 'no') {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun Anda belum diverifikasi. Silakan verifikasi terlebih dahulu.',
                    'need_verification' => true,
                    'user_id' => $user->id,
                    'phone' => $user->phone
                ], 401);
            }

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                
                // Build redirect URL with booking data
                $redirectUrl = $this->buildCheckoutUrl($request);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Login berhasil!',
                    'redirect_url' => $redirectUrl,
                    'user' => Auth::user()
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Nomor telepon atau password salah.'
            ], 401);

        } catch (Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat login. Silahkan coba lagi.'
            ], 500);
        }
    }

    /**
     * Handle AJAX register request
     */
    public function register(Request $request)
    {
        try {
            // Log the incoming request for debugging
            Log::info('Register request:', $request->all());

            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255', 'min:2'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
                'phone' => ['required', 'string', 'max:20', 'min:10', 'unique:users,phone'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'password_confirmation' => ['required', 'string', 'min:8'],
            ], [
                'name.required' => 'Nama lengkap harus diisi.',
                'name.min' => 'Nama lengkap minimal 2 karakter.',
                'name.max' => 'Nama lengkap maksimal 255 karakter.',
                'email.required' => 'Email harus diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah terdaftar.',
                'phone.required' => 'Nomor telepon harus diisi.',
                'phone.min' => 'Nomor telepon minimal 10 karakter.',
                'phone.max' => 'Nomor telepon maksimal 20 karakter.',
                'phone.unique' => 'Nomor telepon sudah terdaftar.',
                'password.required' => 'Password harus diisi.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
                'password_confirmation.required' => 'Konfirmasi password harus diisi.',
            ]);

            if ($validator->fails()) {
                Log::info('Validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak valid.',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Generate OTP (same pattern as RegisteredUserController)
            $otp = rand(111111, 999999);
            
            $user = User::create([
                'name' => trim($request->name),
                'email' => trim(strtolower($request->email)),
                'phone' => trim($request->phone),
                'avatar' => 'storage/default/user.png',
                'password' => Hash::make($request->password),
                'otp' => $otp,
                'access' => 'no', // Set access to 'no' initially
                'phone_verified_at' => null,
            ]);

            // Assign user role (same as RegisteredUserController)
            $user->assignRole('user');

            // Create initial saldo record (same as RegisteredUserController)
            Saldo::create([
                'user_id' => $user->id,
                'credit' => 0,
                'debit' => 0,
                'amount' => 0,
                'description' => 'Saldo Awal',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Log::info('User created successfully:', ['user_id' => $user->id, 'phone' => $user->phone]);

            // Send OTP via WhatsApp using Fonnte (same pattern as RegisteredUserController)
            $pesan = "Halo ". $user->name . "\n\n Silahkan Masukkan Kode OTP Untuk Melanjutkan Registrasi \n\n *$user->otp*";
            $this->send_message($request->phone, $pesan);

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil! Kode OTP telah dikirim ke WhatsApp Anda.',
                'user_id' => $user->id,
                'phone' => $user->phone
            ]);

        } catch (Exception $e) {
            Log::error('Register error: ' . $e->getMessage());
            Log::error('Register error trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat registrasi. Silahkan coba lagi.',
                'error_details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Handle OTP verification (same pattern as RegisteredUserController)
     */
    public function verifyOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => ['required', 'exists:users,id'],
                'otp' => ['required', 'string', 'size:6'],
            ], [
                'user_id.required' => 'User ID harus diisi.',
                'user_id.exists' => 'User tidak ditemukan.',
                'otp.required' => 'Kode OTP harus diisi.',
                'otp.size' => 'Kode OTP harus 6 digit.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak valid.',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Find user by ID and OTP (same pattern as RegisteredUserController)
            $user = User::where('id', $request->user_id)
                       ->where('otp', $request->otp)
                       ->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode OTP salah atau sudah kadaluarsa.'
                ], 422);
            }

            // Check if user is already verified (same pattern as RegisteredUserController)
            if ($user->access === 'yes') {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun sudah diverifikasi sebelumnya!'
                ], 422);
            }

            // Verify user and clear OTP (same pattern as RegisteredUserController)
            $user->update([
                'phone_verified_at' => now(),
                'otp' => null,
                'access' => 'yes'
            ]);

            // Login the user (same pattern as RegisteredUserController)
            Auth::login($user);
            $request->session()->regenerate();

            // Build redirect URL with booking data
            $redirectUrl = $this->buildCheckoutUrl($request);

            return response()->json([
                'success' => true,
                'message' => 'Verifikasi berhasil! Anda akan diarahkan ke halaman checkout.',
                'redirect_url' => $redirectUrl,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone
                ]
            ]);

        } catch (Exception $e) {
            Log::error('OTP verification error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat verifikasi OTP. Silahkan coba lagi.'
            ], 500);
        }
    }

    /**
     * Resend OTP (same pattern as RegisteredUserController)
     */
    public function resendOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'phone' => ['required', 'string', 'exists:users,phone'],
            ], [
                'phone.required' => 'Nomor telepon harus diisi.',
                'phone.exists' => 'Nomor telepon tidak ditemukan.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nomor telepon tidak valid.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = User::where('phone', $request->phone)->first();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan.'
                ], 404);
            }

            // Generate new OTP (same pattern as RegisteredUserController)
            $otp = rand(111111, 999999);
            $user->update(['otp' => $otp]);

            // Send OTP via WhatsApp using Fonnte (same pattern as RegisteredUserController)
            $pesan = "Halo ". $user->name . "\n\n Silahkan Masukkan Kode OTP Untuk Melanjutkan Registrasi \n\n *$user->otp*";
            $this->send_message($request->phone, $pesan);

            return response()->json([
                'success' => true,
                'message' => 'Kode OTP baru telah dikirim ke WhatsApp Anda.'
            ]);

        } catch (Exception $e) {
            Log::error('Resend OTP error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengirim ulang OTP. Silahkan coba lagi.'
            ], 500);
        }
    }

    /**
     * Handle unverified user login attempt - trigger OTP resend
     */
    public function triggerVerification(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => ['required', 'exists:users,id'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak valid.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = User::find($request->user_id);
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan.'
                ], 404);
            }

            if ($user->access === 'yes') {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun sudah diverifikasi.'
                ], 422);
            }

            // Generate new OTP (same pattern as RegisteredUserController)
            $otp = rand(111111, 999999);
            $user->update(['otp' => $otp]);

            // Send OTP via WhatsApp (same pattern as RegisteredUserController)
            $pesan = "Halo ". $user->name . "\n\n Silahkan Masukkan Kode OTP Untuk Melanjutkan Registrasi \n\n *$user->otp*";
            $this->send_message($user->phone, $pesan);

            return response()->json([
                'success' => true,
                'message' => 'Kode OTP telah dikirim ke WhatsApp Anda.',
                'user_id' => $user->id
            ]);

        } catch (Exception $e) {
            Log::error('Trigger verification error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. Silahkan coba lagi.'
            ], 500);
        }
    }

    /**
     * Build checkout URL with booking parameters
     */
    private function buildCheckoutUrl(Request $request)
    {
        $roomId = $request->input('room_id');
        $checkIn = $request->input('check_in');
        $checkOut = $request->input('check_out');
        
        if ($roomId) {
            $params = [];
            if ($checkIn) $params['check_in'] = $checkIn;
            if ($checkOut) $params['check_out'] = $checkOut;
            
            $queryString = !empty($params) ? '?' . http_build_query($params) : '';
            return route('frontpage.checkout', $roomId) . $queryString;
        }
        
        return $request->input('redirect_url', route('frontpage.index'));
    }
}
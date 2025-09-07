<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Exception;

class AjaxAuthController extends Controller
{
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
                'password' => ['required', 'string', 'min:8', 'confirmed'],
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
                'password.min' => 'Password minimal 8 karakter.',
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

            // Generate OTP
            $otp = rand(100000, 999999);
            
            $user = User::create([
                'name' => trim($request->name),
                'email' => trim(strtolower($request->email)),
                'phone' => trim($request->phone),
                'password' => Hash::make($request->password),
                'otp' => $otp,
                'phone_verified_at' => null,
            ]);

            Log::info('User created successfully:', ['user_id' => $user->id, 'phone' => $user->phone]);

            // Send OTP via SMS
            $this->sendOTP($request->phone, $otp);

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil! Kode OTP telah dikirim ke nomor telepon Anda.',
                'user_id' => $user->id
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
     * Handle OTP verification
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

            $user = User::find($request->user_id);

            if (!$user || $user->otp !== $request->otp) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode OTP salah atau sudah kadaluarsa.'
                ], 400);
            }

            // Verify user and clear OTP
            $user->update([
                'phone_verified_at' => now(),
                'otp' => null
            ]);

            // Login the user
            Auth::login($user);
            $request->session()->regenerate();

            // Build redirect URL with booking data
            $redirectUrl = $this->buildCheckoutUrl($request);

            return response()->json([
                'success' => true,
                'message' => 'Verifikasi berhasil! Anda akan diarahkan ke halaman checkout.',
                'redirect_url' => $redirectUrl,
                'user' => $user
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
     * Resend OTP
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

            // Generate new OTP
            $otp = rand(100000, 999999);
            $user->update(['otp' => $otp]);

            // Send OTP via SMS
            $this->sendOTP($request->phone, $otp);

            return response()->json([
                'success' => true,
                'message' => 'Kode OTP baru telah dikirim.'
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

    /**
     * Send OTP via SMS
     */
    private function sendOTP($phone, $otp)
    {
        // Log OTP for development
        Log::info("OTP for {$phone}: {$otp}");
        
        // TODO: Implement SMS service integration
        // Example: Twilio, Nexmo, or local SMS gateway
        
        return true; // Return success for now
    }
}
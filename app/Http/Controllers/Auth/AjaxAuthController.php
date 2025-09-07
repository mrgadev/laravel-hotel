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

class AjaxAuthController extends Controller
{
    /**
     * Handle AJAX login request
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'string'],
            'password' => ['required', 'string'],
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
    }

    /**
     * Handle AJAX register request
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Generate OTP
            $otp = rand(100000, 999999);
            
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'otp' => $otp,
                'phone_verified_at' => null,
            ]);

            // Send OTP via SMS
            $this->sendOTP($request->phone, $otp);

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil! Kode OTP telah dikirim ke nomor telepon Anda.',
                'user_id' => $user->id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat registrasi. Silahkan coba lagi.'
            ], 500);
        }
    }

    /**
     * Handle OTP verification
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'exists:users,id'],
            'otp' => ['required', 'string', 'size:6'],
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
    }

    /**
     * Resend OTP
     */
    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'string', 'exists:users,phone'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor telepon tidak valid.'
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
    }
}

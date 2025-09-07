<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Traits\Fonnte;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    use Fonnte;
    public function forgotPasswordFormPhone() {
        return view('auth.forgot-password.forgot-password');
    }

    public function forgotPasswordFormEmail() {
        return view('auth.forgot-password.forgot-password-email');
    }

    public function forgotPasswordProcess(Request $request) {
        $phone = $request->phone;
        $user = User::where('phone', $phone)->first();
        if($user) {
            $otp = rand(000000,999999);
            $user->otp = $otp;
            $user->save();
            $random_url = Str::random(32);
            // kirim pesan wa berupa kode otp untuk reset password
            $message = "Halo *$user->name*,\n\nSilahkan masukkan kode OTP berikut ini untuk mereset kata sandi Anda, \n\n*$otp*";
            $this->send_message($phone, $message);
            session(['user' => $user]);
            return redirect()->route('forgot.password.verify', ['phone' => $phone, 'random_url' => $random_url])->with('success', 'Kode OTP berhasil dikirim');
        }
    }

    public function forgotPasswordVerify(Request $request, $phone, $random_url) {
        if(session('user')) {
            $user = session('user');
        } else {
            $user = User::where('phone', $phone);
        }
        return view('auth.forgot-password.verify', compact('user'));
    }

    public function forgotPasswordVerifyProcess(Request $request) {
        $otp = $request->otp;
        $user = user::where('otp', $otp)->first();
        if($user) {
            return redirect()->route('forgot.password.reset', ['phone' => $user->phone]);
        } else {
            return redirect()->back()->with('error', 'Kode OTP yang Anda masukkan salah.');
        }
    }

    public function forgotPasswordReset(Request $request) {
        // dd($request->phone);
        $phone = $request->phone;
        if(session('user')) {
            $user = session('user');
        } else {
            $user = User::where('phone', $phone)->first();
        }
        $user = User::where('phone', $phone)->first();

        return view('auth.forgot-password.reset-password', ['user' => $user]);
    }

    public function forgotPasswordResetProcess(Request $request) {
        $data = $request->validate([
            'password' => 'min:6|required|confirmed',
        ]);
        $phone = $request->phone;
        if(session('user')) {
            $user = session('user');
        } else {
            $user = User::where('phone', $phone)->first();
        }
        $data['password'] = Hash::make($data['password']);
        $user->password = $data['password'];
        $user->save();
        return redirect()->route('login')->with('success', 'Password berhasil diubah!');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Traits\Fonnte;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use App\Models\Saldo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use Storage;

class RegisteredUserController extends Controller
{
    use Fonnte;

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if($request->hasFile('avatar')){
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }else{
            $avatarPath = 'storage/default/user.png';
        }

        $otp = rand(111111,999999);

        $access = $data['access'] = 'no';

        $phone = $data['phone'];

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'avatar' => $avatarPath,
            'phone' => $data['phone'],
            'otp' => $otp,
            'access' => $access,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('user');

        Saldo::create([
            'user_id' => $user->id,
            'credit' => 0,
            'debit' => 0,
            'amount' => 0,
            'description' => 'Saldo Awal',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // dd($user);
        // Auth::login($user);
        $random_url = Str::random(64);

        $pesan = "Halo ". $user->name . "\n\n Silahkan Masukkan Kode OTP Untuk Melanjutkan Registrasi \n\n *$user->otp*";
        $pesan1 = $this->send_message($request->phone, $pesan);

        session(['user' => $user]);
        return redirect()->route('verify', compact('phone', 'random_url'));
    }

    public function ajaxRegister(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'unique:users,phone'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        try {
            $otp = rand(111111,999999);
            
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'avatar' => 'storage/default/user.png',
                'phone' => $data['phone'],
                'otp' => $otp,
                'access' => 'no',
                'password' => Hash::make($request->password),
            ]);

            $user->assignRole('user');

            Saldo::create([
                'user_id' => $user->id,
                'credit' => 0,
                'debit' => 0,
                'amount' => 0,
                'description' => 'Saldo Awal',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Send OTP message
            $pesan = "Halo ". $user->name . "\n\n Silahkan Masukkan Kode OTP Untuk Melanjutkan Registrasi \n\n *$user->otp*";
            $this->send_message($request->phone, $pesan);

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil! Silahkan cek SMS untuk kode OTP.',
                'user_id' => $user->id,
                'phone' => $user->phone
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat registrasi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function ajaxVerifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric',
            'user_id' => 'required|exists:users,id'
        ]);

        $user = User::where('id', $request->user_id)
                   ->where('otp', $request->otp)
                   ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP salah!'
            ], 422);
        }

        if ($user->access == 'yes') {
            return response()->json([
                'success' => false,
                'message' => 'Akun sudah diverifikasi sebelumnya!'
            ], 422);
        }

        $user->update(['access' => 'yes']);
        Auth::login($user);

        return response()->json([
            'success' => true,
            'message' => 'Verifikasi berhasil! Anda akan diarahkan ke halaman checkout.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone
            ],
            'redirect_url' => $request->input('redirect_url', route('frontpage.index'))
        ]);
    }

    public function verify(){
        $user = session('user');
        return view('auth.verify', compact('user'));
    }

    public function verify_process(Request $request){
        //ambil otp yang di kirimkan
        $otp = $request->otp;
        //cari user berdasarkan otp
        $user = User::where('otp', $otp)->first();
        //jika pengguna tidak di temukan
        if(!$user)
        {
            return redirect()->back()->withErrors(['otp' => 'OTP Salah']);
        }

        //jika pengguna di temukan
        if ($user->access == 'no'){
            $user->update([
                'access' => 'yes'
            ]);

            Auth::login($user);

            return redirect()->route('dashboard.home');
        }else{
            return redirect()->back()->withErrors(['otp' => 'token sudah kadarluarsa']);
        }
    }

    public function resend(Request $request){
        $phone = $request->phone;

        $user = User::where('phone', $phone)->first();

        $rand = rand(111111,999999);

        $user->update([
            'otp' => $rand
        ]);
        $pesan = "Halo ". $user->name . "\n\n Silahkan Masukkan Kode OTP Untuk Melanjutkan Registrasi \n\n *$user->otp*";
        $pesan1 = $this->send_message($request->phone, $pesan);
        $random_url = Str::random(64);

        return redirect()->route('verify', compact('phone', 'random_url'))->with('success', 'Kode OTP berhasil dikirim ulang!');
    }
}

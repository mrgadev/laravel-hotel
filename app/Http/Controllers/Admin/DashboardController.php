<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\User;
use App\Models\Regency;
use App\Models\Saldo;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    // public function index() {
    //     try {
    //         $bookedRooms = DB::table('transactions')
    //             ->where('checkin_status', 'Sudah Checkin')
    //             ->count();

    //         $totalRooms = DB::table('rooms')->sum('available_rooms');
    //         $availableRooms = max(0, $totalRooms - $bookedRooms);
            
    //         $transactions = Transaction::latest()->limit(5)->get();

    //         // Debug - lihat apa yang sebenarnya terjadi
    //         Log::info('Dashboard data:', [
    //             'bookedRooms' => $bookedRooms,
    //             'availableRooms' => $availableRooms, 
    //             'transactions_count' => $transactions->count()
    //         ]);

    //         return view('dashboard', [
    //             'availableRooms' => 0,
    //             'bookedRooms' => 0, 
    //             'transactions' => collect([])
    //         ]);

    //     } catch (\Exception $e) {
    //         Log::error('Dashboard error: ' . $e->getMessage());
    //         dd('Error in controller: ' . $e->getMessage());
    //     }
    // }
    public function index()
    {
        try {
            // Data untuk admin/staff
            $adminData = [];
            if (auth()->user()->hasRole(['admin', 'staff'])) {
                $adminData = [
                    'bookedRooms' => DB::table('transactions')
                        ->where('checkin_status', 'Sudah Check-in')
                        ->count(),
                    'availableRooms' => max(0, 
                        DB::table('rooms')->sum('available_rooms') - 
                        DB::table('transactions')->where('checkin_status', 'Sudah Check-in')->count()
                    ),
                    'transactions' => Transaction::with('user')->latest()->limit(5)->get()
                ];
            }

            // Data untuk user - dengan null checking
            $userData = [];
            if (auth()->user()->hasRole('user')) {
                $userTransaction = Transaction::where('user_id', auth()->id())->latest()->first();
                $userData = [
                    'user_transaction' => $userTransaction,
                    'user_transactions' => Transaction::where('user_id', auth()->id())->latest()->limit(5)->get(),
                    'wallet' => Saldo::where('user_id', auth()->id())->latest()->first(),
                    'seconds' => $userTransaction && $userTransaction->payment_deadline 
                        ? Carbon::parse($userTransaction->payment_deadline)->diffInSeconds(now()) 
                        : 0
                ];
            }

            return view('dashboard', array_merge($adminData, $userData));

        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage());
            
            // Return view dengan data default kosong
            return view('dashboard', [
                'bookedRooms' => 0,
                'availableRooms' => 0,
                'transactions' => collect([]),
                'user_transaction' => null,
                'user_transactions' => collect([]),
                'wallet' => null,
                'seconds' => 0
            ]);
        }
    }

    public function editProfile(Request $request)
    {
        $regencies = Regency::all();

        $user = Auth::user();

        $banks = Bank::all();

        $bankName = Bank::where('id', $user->bank_id)->first();

        return view('dashboard.profile.edit', [
            'user' => $request->user(),
            'regencies' => $regencies,
            'bankName' => $bankName,
            'banks' => $banks,
        ]);
    }

    public function updateProfile(Request $request, User $user) {
        $message = [
            'avatar.image' => 'File yang diupload harus berformat gambar.',
            'avatar.mimes' => 'File yang diupload harus berformat jpeg, png, jpg, svg, avif, atau webp.',
            'birth.date' => 'Tanggal lahir harus dalam format tanggal.',
            'password.confirmed' => 'Password baru dan konfirmasi password harus sama.',
            'password.min' => 'Password baru minimal 6 karakter.',
            'email.email' => 'Format email tidak valid!',
        ];
        $data = $request->validate([
            'avatar' => 'image|mimes:jpeg,png,jpg,svg,avif,webp|nullable',
            'name' => 'string|max:255|nullable',
            'birth' => 'date|nullable',
            'email' => 'string|max:255|nullable',
            'phone' => 'string|max:255|nullable',
            'password' => 'nullable|confirmed|min:6'
        ], $message);
        if($request->hasFile('avatar')) {
            $avatar = $request->file('avatar')->store('users', 'public');
            $data['avatar'] = $avatar;
        }

        if($request->has('password')) {
            $data['password'] = bcrypt($request->password);
        } else {
            $data['password'] = $user->password;
        }

        $user->update($data);
        return redirect()->route('dashboard.profile.edit')->with('success', 'Profile berhasil diupdate.');
    }
}

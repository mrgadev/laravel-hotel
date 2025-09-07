<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Saldo;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index() {
        $transactions = Transaction::latest()->get();
        return view('dashboard.admin.transaction.index', compact('transactions'));
    }

    public function show(Transaction $transaction) {
        return view('dashboard.admin.transaction.detail', compact('transaction'));
    }

    public function changeCheckInStatus(Request $request, Transaction $transaction) {
        $data = $request->validate([
            'checkin_status' => 'required'
        ]);
        if($data['checkin_status'] == 'Sudah Check-in') {
            $data['checkin_date'] = now();
        } elseif($data['checkin_status'] == 'Sudah Checkout') {
            $data['checkout_date'] = now();
            $transaction->room->incrementAvailableRooms();
        }
        $transaction->update($data);
        $room = Room::where('id', $transaction->room_id)->first();
        // if($data['checkin_status'] == 'Sudah') {
        //     $room->available_rooms -= 1;
        //     $room->save();
        // } else {
        //     $room->available_rooms += 1;
        //     $room->save();
        // }

        return view('dashboard.admin.transaction.detail', compact('transaction'));
    }

    public function changePaymentStatus(Request $request, Transaction $transaction) {
        $data = $request->validate([
            'payment_status' => 'required|string'
        ]);

        try {
            // Update status transaksi
            $transaction->update($data);

            // Cek jika metode pembayaran adalah split payment
            if($transaction->payment_method == 'Split Payment (Saldo & Cash)'){
                if($data['payment_status'] == 'CANCELLED'){
                    // Dapatkan saldo terakhir user
                    $lastBalance = Saldo::where('user_id', $transaction->user_id)
                        ->latest()
                        ->first();

                    // Tentukan saldo saat ini (default 0 jika tidak ada saldo sebelumnya)
                    $currentAmount = $lastBalance ? $lastBalance->amount : 0;

                    // Buat entri saldo kredit (pengurangan saldo)
                    Saldo::create([
                        'user_id' => $transaction->user_id,
                        'transaction_id' => $transaction->id,
                        'debit' => 0,
                        'credit' => $lastBalance->credit,
                        'amount' => 0, // Saldo setelah dikurangi
                        'description' => 'Reservasi Kamar Dibatalkan',
                    ]);
                }
            }

            // Redirect dengan pesan sukses
            return redirect()->back()
                ->with('success', 'Status pembayaran berhasil diperbarui');

        } catch (\Exception $e) {
            // Tangani jika terjadi error
            return back()->with('error', 'Gagal memperbarui status pembayaran: ' . $e->getMessage());
        }
    }

    public function getMonthlyRevenue()
    {
        // Get selected year (default to current year)
        $selectedYear = request('year', date('Y'));

        // Fetch monthly revenue data
        $monthlyRevenue = DB::table('transactions')
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_price) as total_revenue')
            )
            ->whereYear('created_at', $selectedYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            // Transform to include month names
            ->map(function ($item) {
                $item->month_name = date('F', mktime(0, 0, 0, $item->month, 1));
                return $item;
            });

        // Get available years
        $years = DB::table('transactions')
            ->select(DB::raw('YEAR(created_at) as year'))
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        return response()->json([
            'monthly_revenue' => $monthlyRevenue,
            'available_years' => $years,
            'selected_year' => $selectedYear
        ]);
    }
}

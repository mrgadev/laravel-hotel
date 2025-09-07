<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\RoomReview;
use App\Models\Transaction;
use App\Models\SiteSettings;
use Illuminate\Http\Request;
use Spatie\LaravelPdf\Facades\Pdf;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index() {
        $transactions = Transaction::where('user_id', Auth::user()->id)->latest()->get();
        return view('dashboard.user.bookings.index', compact('transactions'));
    }

    public function detail(Transaction $transaction) {
        $room_review = RoomReview::where('transaction_id', $transaction->id)->first();
        return view('dashboard.user.bookings.detail', compact('transaction', 'room_review'));
    }

    public function export(Transaction $transaction) {
        $room_review = RoomReview::where('transaction_id', $transaction->id)->first();
        $filename = "Invoice-".$transaction->invoice."-".date("d-m-Y");
        return Pdf::view('dashboard.user.bookings.export', ['transaction' => $transaction])
            ->format('a4')
            ->name($filename.'.pdf')
            ->margins(20, 20, 20, 20) // left, top, right, bottom
            ->noSandbox()
            ->download();
        // return view('dashboard.user.bookings.export', compact('transaction', 'room_review'));
    }

    public function checkOut(Request $request, Transaction $transaction)
    {
        $data = $request->validate([
            'checkout_date' => 'required'
        ]);
        $site_setting = SiteSettings::where('id',1)->first();
        $checkin = Carbon::parse($transaction->check_in);
        $checkout_date = Carbon::parse($data['checkout_date']);
        // if($checkout_date->isSameDay($checkin)) {
        //     return redirect()->back()->with('error', 'Tidak bisa check out di hari yang sama');
        // } else {
        // }
        if($checkout_date->format('H:i') <= Carbon::parse($site_setting->checkout_time)->format('H:i')) {
            return redirect()->back()->with('error', 'Checkout hanya dibolehkan di atas jam '.Carbon::parse($site_setting->checkout_time)->format('H:i'));
        }

        $transaction->checkin_status = 'Sudah Check-out';
        $transaction->checkout_date = $checkout_date;
        $transaction->save();
        $transaction->room->incrementAvailableRooms();
        return redirect()->back()->with('success', 'Berhasil check-out');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function checkIn(Request $request, Transaction $transaction)
    {
        $data = $request->validate([
            'checkin_date' => 'required'
        ]);
        $site_setting = SiteSettings::where('id',1)->first();
        $checkin = Carbon::parse($transaction->check_in);
        $checkin_date = Carbon::parse($data['checkin_date']);
        // dd($site_setting->checkin_time->format('H:i'));
        if(!$checkin_date->isSameDay($checkin)) {
            return redirect()->back()->with('error', 'Checkin hanya dibolehkan di hari yang sama');
        } else {
            if($checkin_date->format('H:i') <= Carbon::parse($site_setting->checkin_time)->format('H:i')) {
                return redirect()->back()->with('error', 'Checkin hanya dibolehkan di atas jam '.Carbon::parse($site_setting->checkin_time)->format('H:i'));
            }
            $transaction->checkin_status = 'Sudah Check-in';
            $transaction->checkin_date = $checkin_date;
            $transaction->save();
            return redirect()->back()->with('success', 'Berhasil check-in');
        }
    }
}

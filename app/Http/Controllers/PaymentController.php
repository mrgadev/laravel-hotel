<?php

namespace App\Http\Controllers;

// use Flip SDK atau HTTP Client
use Carbon\Carbon;
use App\Models\Room;
use App\Models\Saldo;
use App\Traits\Fonnte;
use App\Models\Transaction;
use App\Models\SiteSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    use Fonnte;
    private $site_settings;
    private $flip_secret_key;
    
    public function __construct() {
        $this->site_settings = SiteSettings::where('id', 1)->first();
        $this->flip_secret_key = config('services.flip.secret_key');
    }

    // Method untuk payment dengan Flip
    public function onlinePayment(Request $request) {
        $data = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'accomodation_plan_id' => 'nullable|array',
            'accomodation_plan_id.*' => 'exists:accomodation_plans,id',
            'promo_id' => 'nullable|array',
            'promo_id.*' => 'exists:promos,id',
        ]);

        // PERBAIKAN 1: Validasi tanggal overlap dengan existing bookings
        if (!$this->isRoomAvailableForDates($data['room_id'], $data['check_in'], $data['check_out'])) {
            return back()->with('error', 'Kamar tidak tersedia untuk tanggal yang dipilih.');
        }

        return DB::transaction(function () use ($data, $request) {
            // PERBAIKAN 2: Atomic decrement dengan row locking
            $room = Room::where('id', $data['room_id'])
                       ->where('available_rooms', '>', 0)
                       ->lockForUpdate()
                       ->first();

            if (!$room) {
                return back()->with('error', 'Kamar tidak tersedia saat ini.');
            }

            // Generate invoice
            $data['invoice'] = $this->generateUniqueInvoice(); 
            $nights = date_diff(date_create($data['check_in']), date_create($data['check_out']))->format("%a");
            
            $checkIn = Carbon::parse($data['check_in']);
            $checkOut = Carbon::parse($data['check_out']);

            // Buat transaction baru
            $transaction = new Transaction();
            $transaction->user_id = $data['user_id'];
            $transaction->name = $data['name'];
            $transaction->email = $data['email'];
            $transaction->phone = $data['phone'];
            $transaction->room_id = $data['room_id'];
            $transaction->check_in = $data['check_in'];
            $transaction->check_out = $data['check_out'];
            $transaction->invoice = $data['invoice'];
            $transaction->payment_method = 'Flip';
            $transaction->payment_deadline = now()->addHours($this->site_settings->payment_deadline);
            
            $transaction->save();
            
            // Sinkronisasi data accomodation_plan_id dan promo_id
            if (!empty($request->accomodation_plan_id)) {
                $transaction->accomodation_plans()->sync($request->accomodation_plan_id);
            }
            if (!empty($request->promo_id)) {
                $transaction->promos()->sync($request->promo_id);
            }
            
            // Hitung total accommodation plan
            $accomodation_plan_amount = 0;
            foreach($transaction->accomodation_plans as $accomodation_plan) {
                $accomodation_plan_amount += $accomodation_plan->price;
            }
            
            // Hitung total promo
            $promo_amount = 0;
            foreach($transaction->promos as $promo) {
                $promo_amount += $promo->amount;
            }
            
            $base_price = $nights * $transaction->room->price;
            $promo_price = (int) $base_price * ($promo_amount / 100);
            $total_amount = $base_price + $accomodation_plan_amount - $promo_price;
            
            $expiredDate = $transaction->payment_deadline->format('Y-m-d H:i');
            
            // Log untuk debugging
            Log::info('Flip Payment Data:', [
                'invoice' => $transaction->invoice,
                'amount' => $total_amount,
                'expired_date_formatted' => $expiredDate,
            ]);

            // Buat bill dengan Flip
            $billData = [
                'title' => 'Pembayaran '.$transaction->room->name.' - UNS Inn Hotel',
                'amount' => $total_amount,
                'type' => 'SINGLE',
                'expired_date' => $expiredDate,
                'redirect_url' => url('/payment/success/' . $transaction->invoice),
                'sender_name' => $transaction->name,
                'sender_email' => $transaction->email,
                'sender_phone_number' => $transaction->phone,
            ];

            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Basic ' . base64_encode($this->flip_secret_key . ':'),
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ])->asForm()->post('https://bigflip.id/big_sandbox_api/v2/pwf/bill', $billData);

                if ($response->successful()) {
                    $result = $response->json();
                    
                    $transaction->payment_status = "PENDING";
                    
                    // Fix URL
                    $paymentUrl = $result['link_url'];
                    if (!str_starts_with($paymentUrl, 'http://') && !str_starts_with($paymentUrl, 'https://')) {
                        $paymentUrl = 'https://' . $paymentUrl;
                    } elseif (str_starts_with($paymentUrl, 'http://')) {
                        $paymentUrl = str_replace('http://', 'https://', $paymentUrl);
                    }
                    $transaction->payment_url = $paymentUrl;
                    
                    $transaction->total_price = $total_amount;
                    $transaction->flip_bill_id = $result['link_id'];
                    $transaction->flip_response = $result;
                    
                    if (isset($result['expired_date'])) {
                        try {
                            $transaction->flip_expired_date = Carbon::parse($result['expired_date']);
                        } catch (\Exception $e) {
                            $transaction->flip_expired_date = $transaction->payment_deadline;
                        }
                    } else {
                        $transaction->flip_expired_date = $transaction->payment_deadline;
                    }
                    
                    $transaction->save();
                    
                    // PERBAIKAN 3: Decrement dilakukan di dalam transaction setelah semua berhasil
                    $room->decrementAvailableRooms();
                    
                    // Send booking confirmation message
                    $this->sendBookingConfirmationMessage($transaction);
                    
                    return redirect()->route('payment.bill', $transaction->invoice);
                } else {
                    Log::error('Flip API Error: ' . $response->body());
                    throw new \Exception('Flip API Error');
                }
            } catch(\Exception $e) {
                Log::error('Flip Payment Error: ' . $e->getMessage());
                throw $e; // Akan rollback transaction
            }
        });
    }

    public function cashPayment(Request $request) {
        $data = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required',
            'check_out' => 'required',
            'accomodation_plan_id' => 'nullable|array',
            'accomodation_plan_id.*' => 'exists:accomodation_plans,id',
            'promo_id' => 'nullable|array',
            'promo_id.*' => 'exists:promos,id',
        ]);
        
        $data['invoice'] = $this->generateUniqueInvoice(); 
        $nights = date_diff(date_create($data['check_in']), date_create($data['check_out']))->format("%a");

        $transaction = new Transaction();
        $transaction->user_id = $data['user_id'];
        $transaction->name = $data['name'];
        $transaction->email = $data['email'];
        $transaction->phone = $data['phone'];
        $transaction->room_id = $data['room_id'];
        $transaction->check_in = $data['check_in'];
        $transaction->check_out = $data['check_out'];
        $transaction->invoice = $data['invoice'];
        $transaction->payment_method = 'Cash';
        $transaction->payment_deadline = now()->addHours($this->site_settings->payment_deadline);
        $transaction->save();
        
        $transaction->accomodation_plans()->sync($request->accomodation_plan_id);
        $transaction->promos()->sync($request->promo_id);
        
        $accomodation_plan_amount = 0;
        foreach($transaction->accomodation_plans as $accomodation_plan) {
            $accomodation_plan_amount += $accomodation_plan->price;
        }
        
        $promo_amount = 0;
        foreach($transaction->promos as $promo) {
            $promo_amount += $promo->amount;
        }
        
        $base_price = $nights * $transaction->room->price;
        $promo_price = (int) $base_price * ($promo_amount / 100);
        $total_amount = $base_price + $accomodation_plan_amount - $promo_price;

        $transaction->payment_status = "PENDING";
        $transaction->payment_url = '';
        $transaction->total_price = $total_amount;
        $transaction->save();

        // Send cash payment booking confirmation
        $this->sendCashPaymentBookingMessage($transaction);

        return redirect()->route('payment.success', $transaction->invoice);
    }

    public function creditPayment(Request $request){
        $data = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required',
            'check_out' => 'required',
            'accomodation_plan_id' => 'nullable|array',
            'accomodation_plan_id.*' => 'exists:accomodation_plans,id',
            'promo_id' => 'nullable|array',
            'promo_id.*' => 'exists:promos,id',
        ]);
        
        $data['invoice'] = $this->generateUniqueInvoice(); 
        $nights = date_diff(date_create($data['check_in']), date_create($data['check_out']))->format("%a");

        $transaction = new Transaction();
        $transaction->user_id = $data['user_id'];
        $transaction->name = $data['name'];
        $transaction->email = $data['email'];
        $transaction->phone = $data['phone'];
        $transaction->room_id = $data['room_id'];
        $transaction->check_in = $data['check_in'];
        $transaction->check_out = $data['check_out'];
        $transaction->invoice = $data['invoice'];
        $transaction->payment_method = 'Saldo';
        $transaction->save();
        
        $transaction->accomodation_plans()->sync($request->accomodation_plan_id);
        $transaction->promos()->sync($request->promo_id);
        
        $accomodation_plan_amount = 0;
        foreach($transaction->accomodation_plans as $accomodation_plan) {
            $accomodation_plan_amount += $accomodation_plan->price;
        }
        
        $promo_amount = 0;
        foreach($transaction->promos as $promo) {
            $promo_amount += $promo->amount;
        }
        
        $base_price = $nights * $transaction->room->price;
        $promo_price = (int) $base_price * ($promo_amount / 100);
        $total_amount = $base_price + $accomodation_plan_amount - $promo_price;

        $transaction->payment_status = "PENDING";
        $transaction->payment_url = '';
        $transaction->total_price = $total_amount;
        $transaction->payment_deadline = now()->addHours($this->site_settings->payment_deadline);
        $transaction->save();
        
        $room = Room::where('id', $transaction->room_id)->first();
        $room->decrementAvailableRooms();

        $lastBalance = Saldo::where('user_id', $transaction->user_id)
                        ->latest()
                        ->first();

        $newAmount = $lastBalance ? $lastBalance->amount - $transaction->total_price : $transaction->total_price;

        Saldo::create([
            'user_id' => $transaction->user_id,
            'transaction_id' => $transaction->id,
            'debit' => 0,
            'credit' => $transaction->total_price,
            'amount' => $newAmount,
            'description' => 'Reservasi Kamar'
        ]);

        // Send credit payment confirmation
        $this->sendSaldoPaymentConfirmationMessage($transaction);

        return redirect()->route('payment.bill', $transaction->invoice);
    }

    public function bill(Transaction $transaction) {
        if($transaction->payment_deadline < Carbon::now()) {
            // Send timeout notification before redirecting
            $this->sendPaymentTimeoutMessage($transaction);
            return redirect()->route('payment.timeout', $transaction->invoice);
        }

        return view('frontpage.payment.bill', compact('transaction'));
    }

    public function timeout(Transaction $transaction) {
        return DB::transaction(function () use ($transaction) {
            $room = Room::where('id', $transaction->room_id)->lockForUpdate()->firstOrFail();
            
            if($transaction->payment_status == "PENDING") {
                $transaction->payment_status = "CANCELLED";
                $transaction->save();
                $room->incrementAvailableRooms();
                
                // Send cancellation notification
                $this->sendBookingCancellationMessage($transaction);
                
                return view('frontpage.payment.timeout');
            } elseif($transaction->payment_status == 'PAID') {
                return redirect()->route('payment.success', $transaction->invoice);
            }
        });
    }

    public function success(Transaction $transaction)
    {
        $room = Room::where('id', $transaction->room_id)->firstOrFail();
        
        return DB::transaction(function () use ($transaction, $room) {
            if(($transaction->payment_method == 'Flip')) {
                $transaction->payment_status = "PAID";
                
                // PERBAIKAN 2: Assign proper room number
                $roomNumber = $this->assignAvailableRoomNumber(
                    $transaction->room_id, 
                    $transaction->check_in, 
                    $transaction->check_out
                );
                
                if (!$roomNumber) {
                    Log::error('No available room number for transaction: ' . $transaction->invoice);
                    return back()->with('error', 'Terjadi kesalahan dalam assign nomor kamar.');
                }
                
                $transaction->room_number = $roomNumber;
                $transaction->payment_deadline = NULL;
                $transaction->save();
                
                // Send payment success notification
                $this->sendPaymentSuccessMessage($transaction);
                
            } elseif($transaction->payment_method == 'Saldo') {
                $transaction->payment_status = "PAID";
                
                // PERBAIKAN 2: Assign proper room number
                $roomNumber = $this->assignAvailableRoomNumber(
                    $transaction->room_id, 
                    $transaction->check_in, 
                    $transaction->check_out
                );
                
                if (!$roomNumber) {
                    return back()->with('error', 'Terjadi kesalahan dalam assign nomor kamar.');
                }
                
                $transaction->room_number = $roomNumber;
                $transaction->payment_deadline = NULL;
                $transaction->save();

                // Handle saldo deduction (only if not already handled)
                $existingSaldoEntry = Saldo::where('transaction_id', $transaction->id)->exists();
                if (!$existingSaldoEntry) {
                    $lastBalance = Saldo::where('user_id', $transaction->user_id)->latest()->first();
                    $newAmount = $lastBalance ? $lastBalance->amount - $transaction->total_price : -$transaction->total_price;

                    Saldo::create([
                        'user_id' => $transaction->user_id,
                        'transaction_id' => $transaction->id,
                        'debit' => 0,
                        'credit' => $transaction->total_price,
                        'amount' => $newAmount,
                        'description' => 'Reservasi Kamar'
                    ]);
                }
                
                // Send saldo payment success notification
                $this->sendSaldoPaymentSuccessMessage($transaction);
                
            } elseif($transaction->payment_method == 'Cash'){
                $transaction->payment_status = "PENDING";
                
                // PERBAIKAN 2: Assign proper room number
                $roomNumber = $this->assignAvailableRoomNumber(
                    $transaction->room_id, 
                    $transaction->check_in, 
                    $transaction->check_out
                );
                
                if (!$roomNumber) {
                    return back()->with('error', 'Terjadi kesalahan dalam assign nomor kamar.');
                }
                
                $transaction->room_number = $roomNumber;
                $transaction->payment_deadline = NULL;
                $transaction->save();
                
                // Send cash payment reminder
                $this->sendCashPaymentReminderMessage($transaction);
                
            } elseif($transaction->payment_method == 'Split Payment (Saldo & Cash)'){
                $transaction->payment_status = "PENDING";
                
                // PERBAIKAN 2: Assign proper room number
                $roomNumber = $this->assignAvailableRoomNumber(
                    $transaction->room_id, 
                    $transaction->check_in, 
                    $transaction->check_out
                );
                
                if (!$roomNumber) {
                    return back()->with('error', 'Terjadi kesalahan dalam assign nomor kamar.');
                }
                
                $transaction->room_number = $roomNumber;
                $transaction->payment_deadline = NULL;
                $transaction->save();
                
                // Send split payment confirmation
                $this->sendSplitPaymentCashConfirmationMessage($transaction);
                
            } elseif($transaction->payment_method == 'Split Payment (Saldo & Flip)'){
                $transaction->payment_status = "PAID";
                
                // PERBAIKAN 2: Assign proper room number
                $roomNumber = $this->assignAvailableRoomNumber(
                    $transaction->room_id, 
                    $transaction->check_in, 
                    $transaction->check_out
                );
                
                if (!$roomNumber) {
                    return back()->with('error', 'Terjadi kesalahan dalam assign nomor kamar.');
                }
                
                $transaction->room_number = $roomNumber;
                $transaction->payment_deadline = NULL;
                $transaction->save();

                // Handle saldo deduction (only if not already handled)
                $existingSaldoEntry = Saldo::where('transaction_id', $transaction->id)->exists();
                if (!$existingSaldoEntry) {
                    $lastBalance = Saldo::where('user_id', $transaction->user_id)->latest()->first();
                    $newAmount = $lastBalance ? $lastBalance->amount - $transaction->total_price : -$transaction->total_price;

                    Saldo::create([
                        'user_id' => $transaction->user_id,
                        'transaction_id' => $transaction->id,
                        'debit' => 0,
                        'credit' => $transaction->total_price,
                        'amount' => $newAmount,
                        'description' => 'Reservasi Kamar'
                    ]);
                }
                
                // Send split payment success notification
                $this->sendSplitPaymentFlipSuccessMessage($transaction);
            }
            
            return view('frontpage.payment.success', compact('transaction'));
        });
    }

    public function failed($id)
    {
        return DB::transaction(function () use ($id) {
            $transaction = Transaction::where('invoice', $id)->lockForUpdate()->firstOrFail();
            $room = Room::where('id', $transaction->room_id)->lockForUpdate()->firstOrFail();
            
            if($transaction->payment_status == "PENDING") {
                $transaction->payment_status = "CANCELLED";
                $transaction->save();
                $room->incrementAvailableRooms();
                
                // Send payment failed notification
                $this->sendPaymentFailedMessage($transaction);
            }
            
            return view('frontpage.payment.failed', compact('transaction'));
        });
    }

    public function addCash(Request $request){
        $data = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required',
            'check_out' => 'required',
            'accomodation_plan_id' => 'nullable|array',
            'accomodation_plan_id.*' => 'exists:accomodation_plans,id',
            'promo_id' => 'nullable|array',
            'promo_id.*' => 'exists:promos,id',
        ]);
        
        $data['invoice'] = $this->generateUniqueInvoice(); 
        $nights = date_diff(date_create($data['check_in']), date_create($data['check_out']))->format("%a");

        $transaction = new Transaction();
        $transaction->user_id = $data['user_id'];
        $transaction->name = $data['name'];
        $transaction->email = $data['email'];
        $transaction->phone = $data['phone'];
        $transaction->room_id = $data['room_id'];
        $transaction->check_in = $data['check_in'];
        $transaction->check_out = $data['check_out'];
        $transaction->invoice = $data['invoice'];
        $transaction->payment_method = 'Split Payment (Saldo & Cash)';
        $transaction->save();
        
        $transaction->accomodation_plans()->sync($request->accomodation_plan_id);
        $transaction->promos()->sync($request->promo_id);
        
        $accomodation_plan_amount = 0;
        foreach($transaction->accomodation_plans as $accomodation_plan) {
            $accomodation_plan_amount += $accomodation_plan->price;
        }
        
        $promo_amount = 0;
        foreach($transaction->promos as $promo) {
            $promo_amount += $promo->amount;
        }
        
        $base_price = $nights * $transaction->room->price;
        $promo_price = (int) $base_price * ($promo_amount / 100);
        $saldo = Saldo::where('user_id', Auth::user()->id)->latest()->first();

        $total_amount = $base_price + $accomodation_plan_amount - $promo_price - $saldo->amount;

        $transaction->payment_status = "PENDING";
        $transaction->payment_url = '';
        $transaction->total_price = $total_amount;
        $transaction->save();

        $lastBalance = Saldo::where('user_id', Auth::user()->id)->latest()->first();

        Saldo::create([
            'amount' => 0,
            'user_id' => Auth::user()->id,
            'credit' => $lastBalance->amount,
            'debit' => 0,
            'description' => 'Reservasi Kamar',
        ]);

        // Send split payment cash booking confirmation
        $this->sendSplitPaymentCashBookingMessage($transaction, $saldo->amount, $total_amount);

        return redirect()->route('payment.success', $transaction->invoice);
    }

    public function addFlip(Request $request){
        $data = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'accomodation_plan_id' => 'nullable|array',
            'accomodation_plan_id.*' => 'exists:accomodation_plans,id',
            'promo_id' => 'nullable|array',
            'promo_id.*' => 'exists:promos,id',
        ]);
        
        $data['invoice'] = $this->generateUniqueInvoice();
        $nights = date_diff(date_create($data['check_in']), date_create($data['check_out']))->format("%a");
        
        $transaction = new Transaction();
        $transaction->user_id = $data['user_id'];
        $transaction->name = $data['name'];
        $transaction->email = $data['email'];
        $transaction->phone = $data['phone'];
        $transaction->room_id = $data['room_id'];
        $transaction->check_in = $data['check_in'];
        $transaction->check_out = $data['check_out'];
        $transaction->invoice = $data['invoice'];
        $transaction->payment_method = 'Split Payment (Saldo & Flip)';
        $transaction->payment_deadline = now()->addHours($this->site_settings->payment_deadline);
        $transaction->save();
        
        if (!empty($request->accomodation_plan_id)) {
            $transaction->accomodation_plans()->sync($request->accomodation_plan_id);
        }
        if (!empty($request->promo_id)) {
            $transaction->promos()->sync($request->promo_id);
        }
        
        $accomodation_plan_amount = 0;
        foreach($transaction->accomodation_plans as $accomodation_plan) {
            $accomodation_plan_amount += $accomodation_plan->price;
        }
        
        $promo_amount = 0;
        foreach($transaction->promos as $promo) {
            $promo_amount += $promo->amount;
        }

        $base_price = $nights * $transaction->room->price;
        $promo_price = (int) $base_price * ($promo_amount / 100);
        $saldo = Saldo::where('user_id', Auth::user()->id)->latest()->first();

        $total_amount = $base_price + $accomodation_plan_amount - $promo_price - ($saldo ? $saldo->amount : 0);

        // Format expired date sesuai dokumentasi Flip: "YYYY-MM-DD HH:MM" (tanpa detik)
        $expiredDate = $transaction->payment_deadline->format('Y-m-d H:i');

        // Buat bill dengan Flip
        $billData = [
            'title' => 'Pembayaran '.$transaction->room->name.' - UNS Inn Hotel (Split Payment)',
            'amount' => $total_amount,
            'type' => 'SINGLE',
            'expired_date' => $expiredDate, // Format yang diperbaiki
            'redirect_url' => url('/payment/success/' . $transaction->invoice), // Menggunakan url() helper
            'sender_name' => $transaction->name,
            'sender_email' => $transaction->email,
            'sender_phone_number' => $transaction->phone,
        ];
        
        Log::info('Split Payment Flip Data:', $billData);
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode($this->flip_secret_key . ':'),
                'Content-Type' => 'application/x-www-form-urlencoded'
            ])->asForm()->post('https://bigflip.id/big_sandbox_api/v2/pwf/bill', $billData);

            if ($response->successful()) {
                $result = $response->json();
                
                $transaction->payment_status = "PENDING";
                
                // FIX: Pastikan URL memiliki https://
                $paymentUrl = $result['link_url'];
                if (!str_starts_with($paymentUrl, 'http://') && !str_starts_with($paymentUrl, 'https://')) {
                    $paymentUrl = 'https://' . $paymentUrl;
                } elseif (str_starts_with($paymentUrl, 'http://')) {
                    // Konversi http ke https untuk keamanan
                    $paymentUrl = str_replace('http://', 'https://', $paymentUrl);
                }
                $transaction->payment_url = $paymentUrl;
                
                $transaction->total_price = $total_amount;
                $transaction->flip_bill_id = $result['link_id'];
                $transaction->flip_response = $result;
                
                if (isset($result['expired_date'])) {
                    try {
                        $transaction->flip_expired_date = Carbon::parse($result['expired_date']);
                    } catch (\Exception $e) {
                        $transaction->flip_expired_date = $transaction->payment_deadline;
                    }
                } else {
                    $transaction->flip_expired_date = $transaction->payment_deadline;
                }
                
                $transaction->save();

                // Log URL yang telah diperbaiki untuk split payment
                Log::info('Fixed Split Payment URL:', [
                    'original_url' => $result['link_url'],
                    'fixed_url' => $transaction->payment_url
                ]);

                // Handle saldo deduction
                if ($saldo && $saldo->amount > 0) {
                    Saldo::create([
                        'amount' => 0,
                        'user_id' => Auth::user()->id,
                        'transaction_id' => $transaction->id,
                        'credit' => $saldo->amount,
                        'debit' => 0,
                        'description' => 'Reservasi Kamar (Split Payment)',
                    ]);
                }

                $room = Room::where('id', $transaction->room_id)->first();
                $room->decrementAvailableRooms();
                
                // Send split payment booking confirmation with Flip
                $this->sendSplitPaymentFlipBookingMessage($transaction, $saldo ? $saldo->amount : 0, $total_amount);
                
                return redirect()->route('payment.bill', $transaction->invoice);
            } else {
                Log::error('Flip API Error (Split Payment): ' . $response->body());
                return back()->with('error', 'Terjadi kesalahan saat membuat pembayaran. Silakan coba lagi.');
            }
        } catch(\Exception $e) {
            Log::error('Flip Payment Error (Split Payment): ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat membuat pembayaran. Silakan coba lagi.');
        }
    }

    // Callback untuk menangani response dari Flip setelah pembayaran
    public function flipCallback(Request $request)
    {
        try {
            // Log semua data yang diterima dari Flip
            Log::info('Flip Callback received:', $request->all());

            $billId = $request->input('id');
            $status = $request->input('status');
            $amount = $request->input('amount');

            // Validasi apakah data yang diperlukan ada
            if (!$billId || !$status) {
                Log::error('Flip Callback: Missing required data');
                return response()->json(['status' => 'error', 'message' => 'Missing data'], 400);
            }

            // Cari transaction berdasarkan flip_bill_id
            $transaction = Transaction::where('flip_bill_id', $billId)->first();

            if (!$transaction) {
                Log::error('Flip Callback: Transaction not found for bill ID: ' . $billId);
                return response()->json(['status' => 'error', 'message' => 'Transaction not found'], 404);
            }

            // Validasi amount jika diperlukan
            if ($amount && $transaction->total_price != $amount) {
                Log::warning('Flip Callback: Amount mismatch', [
                    'expected' => $transaction->total_price,
                    'received' => $amount
                ]);
            }

            // Update status transaction berdasarkan status dari Flip
            switch ($status) {
                case 'SUCCESSFUL':
                case 'success':
                case 'paid':
                    if ($transaction->payment_status !== 'PAID') {
                        $transaction->payment_status = 'PAID';
                        $transaction->room_number = rand(1, $transaction->room->total_rooms);
                        $transaction->payment_deadline = null;
                        $transaction->save();

                        // Update flip_response dengan data callback
                        $callbackData = $request->all();
                        $existingResponse = $transaction->flip_response ?? [];
                        $transaction->flip_response = array_merge($existingResponse, ['callback' => $callbackData]);
                        $transaction->save();

                        // Send payment success callback notification
                        $this->sendFlipCallbackSuccessMessage($transaction);

                        Log::info('Flip Callback: Transaction marked as PAID', ['invoice' => $transaction->invoice]);
                    }
                    break;

                case 'FAILED':
                case 'failed':
                case 'CANCELLED':
                case 'cancelled':
                case 'EXPIRED':
                case 'expired':
                    if ($transaction->payment_status === 'PENDING') {
                        $transaction->payment_status = 'CANCELLED';
                        $transaction->save();

                        // Kembalikan available rooms
                        $room = Room::find($transaction->room_id);
                        if ($room) {
                            $room->incrementAvailableRooms();
                        }

                        // Send payment failure callback notification
                        $this->sendFlipCallbackFailureMessage($transaction);

                        Log::info('Flip Callback: Transaction cancelled', ['invoice' => $transaction->invoice]);
                    }
                    break;

                default:
                    Log::warning('Flip Callback: Unknown status received', ['status' => $status]);
                    break;
            }

            return redirect()->route('payment.success', $transaction->invoice);

        } catch (\Exception $e) {
            Log::error('Flip Callback Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return response()->json(['status' => 'error', 'message' => 'Internal server error'], 500);
        }
    }

    // Webhook untuk menangani notifikasi dari Flip (berbeda dengan callback)
    public function flipWebhook(Request $request)
    {
        try {
            // Verifikasi signature jika Flip menyediakan
            $signature = $request->header('X-Flip-Signature');
            $payload = $request->getContent();
            
            // Uncomment jika Flip menyediakan signature validation
            // if ($signature && !$this->validateFlipSignature($payload, $signature)) {
            //     Log::error('Flip Webhook: Invalid signature');
            //     return response()->json(['status' => 'error'], 401);
            // }

            $data = json_decode($payload, true);
            Log::info('Flip Webhook received:', $data);

            if (isset($data['id']) && isset($data['status'])) {
                $transaction = Transaction::where('flip_bill_id', $data['id'])->first();
                
                if ($transaction) {
                    // Update flip_response
                    $existingResponse = $transaction->flip_response ?? [];
                    $transaction->flip_response = array_merge($existingResponse, ['webhook' => $data]);
                    $transaction->save();

                    // Process status update similar to callback
                    $this->processFlipStatusUpdate($transaction, $data['status']);
                }
            }

            return response()->json(['status' => 'success'], 200);
        } catch (\Exception $e) {
            Log::error('Flip Webhook Error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    // Helper method untuk memproses update status
    private function processFlipStatusUpdate($transaction, $status)
    {
        switch ($status) {
            case 'SUCCESSFUL':
            case 'success':
            case 'paid':
                if ($transaction->payment_status !== 'PAID') {
                    $transaction->payment_status = 'PAID';
                    $transaction->room_number = rand(1, $transaction->room->total_rooms);
                    $transaction->payment_deadline = null;
                    $transaction->save();
                    
                    // Send webhook success notification
                    $this->sendFlipWebhookSuccessMessage($transaction);
                }
                break;

            case 'FAILED':
            case 'failed':
            case 'CANCELLED':
            case 'cancelled':
            case 'EXPIRED':
            case 'expired':
                if ($transaction->payment_status === 'PENDING') {
                    $transaction->payment_status = 'CANCELLED';
                    $transaction->save();

                    $room = Room::find($transaction->room_id);
                    if ($room) {
                        $room->incrementAvailableRooms();
                    }
                    
                    // Send webhook failure notification
                    $this->sendFlipWebhookFailureMessage($transaction);
                }
                break;
        }
    }

    // Helper method untuk validasi signature (jika Flip menyediakan)
    private function validateFlipSignature($payload, $signature)
    {
        $validationToken = config('services.flip.validation_token');
        $expectedSignature = hash_hmac('sha256', $payload, $validationToken);
        return hash_equals($expectedSignature, $signature);
    }

    // ===============================
    // FONNTE NOTIFICATION METHODS
    // ===============================

    /**
     * Send booking confirmation message for online payment
     */
    private function sendBookingConfirmationMessage($transaction)
    {
        try {
            $checkIn = $this->formatDateTimeForUser($transaction->check_in, 'l, d M Y');
            $checkOut = $this->formatDateTimeForUser($transaction->check_out, 'l, d M Y');
            $paymentDeadline = $this->formatDateTimeForUser($transaction->payment_deadline, 'd M Y H:i');
            $paymentUrl = $transaction->payment_url;

            $message = "🏨 *KONFIRMASI PEMESANAN - UNS Inn Hotel* 🏨\n\n";
            $message .= "Halo {$transaction->name}!\n";
            $message .= "Terima kasih telah melakukan pemesanan kamar di UNS Inn Hotel.\n\n";
            $message .= "📋 *Detail Reservasi:*\n";
            $message .= "• Invoice: *{$transaction->invoice}*\n";
            $message .= "• Tipe Kamar: *{$transaction->room->name}*\n";
            $message .= "• Check-in: *{$checkIn}*\n";
            $message .= "• Check-out: *{$checkOut}*\n";
            $message .= "• Total Pembayaran: *Rp " . number_format($transaction->total_price, 0, ',', '.') . "*\n";
            $message .= "• Metode Pembayaran: *{$transaction->payment_method}*\n\n";
            $message .= "⏰ *Batas Waktu Pembayaran:*\n";
            $message .= "{$paymentDeadline} WIB\n\n";
            $message .= "💳 Silakan lakukan pembayaran melalui link yang telah dikirimkan.\n\n";
            $message .= "{$paymentUrl}\n\n";
            $message .= "Jika ada pertanyaan, jangan ragu untuk menghubungi kami.\n";
            $message .= "Terima kasih! 😊";

            $this->send_message($transaction->phone, $message);
            
            Log::info('Booking confirmation message sent', [
                'invoice' => $transaction->invoice,
                'phone' => $transaction->phone
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send booking confirmation message: ' . $e->getMessage(), [
                'invoice' => $transaction->invoice
            ]);
        }
    }

    /**
     * Send cash payment booking confirmation message
     */
    private function sendCashPaymentBookingMessage($transaction)
    {
        try {
            $checkIn = $this->formatDateTimeForUser($transaction->check_in, 'l, d M Y');
            $checkOut = $this->formatDateTimeForUser($transaction->check_out, 'l, d M Y');
            
            $message = "🏨 *KONFIRMASI PEMESANAN CASH - UNS Inn Hotel* 🏨\n\n";
            $message .= "Halo {$transaction->name}!\n";
            $message .= "Terima kasih telah melakukan pemesanan kamar di UNS Inn Hotel.\n\n";
            $message .= "📋 *Detail Reservasi:*\n";
            $message .= "• Invoice: *{$transaction->invoice}*\n";
            $message .= "• Tipe Kamar: *{$transaction->room->name}*\n";
            $message .= "• Check-in: *{$checkIn}*\n";
            $message .= "• Check-out: *{$checkOut}*\n";
            $message .= "• Total Pembayaran: *Rp " . number_format($transaction->total_price, 0, ',', '.') . "*\n";
            $message .= "• Metode Pembayaran: *Cash*\n\n";
            $message .= "💰 *Pembayaran Cash:*\n";
            $message .= "Silakan lakukan pembayaran secara langsung di front desk UNS Inn Hotel saat check-in atau sebelumnya.\n\n";
            $message .= "Pastikan untuk membawa invoice ini sebagai bukti reservasi.\n\n";
            $message .= "Terima kasih! 😊";

            $this->send_message($transaction->phone, $message);
            
            Log::info('Cash payment booking message sent', [
                'invoice' => $transaction->invoice,
                'phone' => $transaction->phone
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send cash payment booking message: ' . $e->getMessage(), [
                'invoice' => $transaction->invoice
            ]);
        }
    }

    /**
     * Send saldo payment confirmation message
     */
    private function sendSaldoPaymentConfirmationMessage($transaction)
    {
        try {
            $checkIn = $this->formatDateTimeForUser($transaction->check_in, 'l, d M Y');
            $checkOut = $this->formatDateTimeForUser($transaction->check_out, 'l, d M Y');
            
            $message = "🏨 *KONFIRMASI PEMBAYARAN SALDO - UNS Inn Hotel* 🏨\n\n";
            $message .= "Halo {$transaction->name}!\n";
            $message .= "Pembayaran dengan saldo Anda telah berhasil diproses.\n\n";
            $message .= "📋 *Detail Reservasi:*\n";
            $message .= "• Invoice: *{$transaction->invoice}*\n";
            $message .= "• Tipe Kamar: *{$transaction->room->name}*\n";
            $message .= "• Check-in: *{$checkIn}*\n";
            $message .= "• Check-out: *{$checkOut}*\n";
            $message .= "• Total Pembayaran: *Rp " . number_format($transaction->total_price, 0, ',', '.') . "*\n";
            $message .= "• Metode Pembayaran: *Saldo*\n\n";
            $message .= "✅ Status: *PENDING* (Menunggu konfirmasi)\n\n";
            $message .= "Reservasi Anda sedang diproses. Kami akan mengirimkan konfirmasi final segera.\n\n";
            $message .= "Terima kasih! 😊";

            $this->send_message($transaction->phone, $message);
            
            Log::info('Saldo payment confirmation message sent', [
                'invoice' => $transaction->invoice,
                'phone' => $transaction->phone
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send saldo payment confirmation message: ' . $e->getMessage(), [
                'invoice' => $transaction->invoice
            ]);
        }
    }

    /**
     * Send payment timeout notification
     */
    private function sendPaymentTimeoutMessage($transaction)
    {
        try {
            $message = "⚠️ *WAKTU PEMBAYARAN HABIS - UNS Inn Hotel* ⚠️\n\n";
            $message .= "Halo {$transaction->name},\n\n";
            $message .= "Mohon maaf, waktu pembayaran untuk reservasi Anda telah habis.\n\n";
            $message .= "📋 *Detail Reservasi yang Dibatalkan:*\n";
            $message .= "• Invoice: *{$transaction->invoice}*\n";
            $message .= "• Tipe Kamar: *{$transaction->room->name}*\n";
            $message .= "• Total: *Rp " . number_format($transaction->total_price, 0, ',', '.') . "*\n\n";
            $message .= "Jika Anda masih ingin melakukan reservasi, silakan lakukan pemesanan ulang melalui website kami.\n\n";
            $message .= "Terima kasih atas pengertiannya. 😊";

            $this->send_message($transaction->phone, $message);
            
            Log::info('Payment timeout message sent', [
                'invoice' => $transaction->invoice,
                'phone' => $transaction->phone
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send payment timeout message: ' . $e->getMessage(), [
                'invoice' => $transaction->invoice
            ]);
        }
    }

    /**
     * Send booking cancellation notification
     */
    private function sendBookingCancellationMessage($transaction)
    {
        try {
            $message = "❌ *PEMBATALAN RESERVASI - UNS Inn Hotel* ❌\n\n";
            $message .= "Halo {$transaction->name},\n\n";
            $message .= "Reservasi Anda telah dibatalkan karena melewati batas waktu pembayaran.\n\n";
            $message .= "📋 *Detail Reservasi yang Dibatalkan:*\n";
            $message .= "• Invoice: *{$transaction->invoice}*\n";
            $message .= "• Tipe Kamar: *{$transaction->room->name}*\n";
            $message .= "• Total: *Rp " . number_format($transaction->total_price, 0, ',', '.') . "*\n\n";
            $message .= "Kamar yang sempat direservasi telah dikembalikan ke sistem dan tersedia untuk tamu lain.\n\n";
            $message .= "Silakan lakukan pemesanan ulang jika Anda masih membutuhkan kamar.\n\n";
            $message .= "Terima kasih! 😊";

            $this->send_message($transaction->phone, $message);
            
            Log::info('Booking cancellation message sent', [
                'invoice' => $transaction->invoice,
                'phone' => $transaction->phone
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send booking cancellation message: ' . $e->getMessage(), [
                'invoice' => $transaction->invoice
            ]);
        }
    }

    /**
     * Send payment success notification
     */
    private function sendPaymentSuccessMessage($transaction)
    {
        try {
            $checkIn = $this->formatDateTimeForUser($transaction->check_in, 'l, d M Y');
            $checkOut = $this->formatDateTimeForUser($transaction->check_out, 'l, d M Y');
            
            $message = "✅ *PEMBAYARAN BERHASIL - UNS Inn Hotel* ✅\n\n";
            $message .= "Halo {$transaction->name}!\n";
            $message .= "Selamat! Pembayaran Anda telah berhasil dikonfirmasi.\n\n";
            $message .= "🎉 *Detail Reservasi Terkonfirmasi:*\n";
            $message .= "• Invoice: *{$transaction->invoice}*\n";
            $message .= "• Nomor Kamar: *{$transaction->room_number}*\n";
            $message .= "• Tipe Kamar: *{$transaction->room->name}*\n";
            $message .= "• Check-in: *{$checkIn}*\n";
            $message .= "• Check-out: *{$checkOut}*\n";
            $message .= "• Total Dibayar: *Rp " . number_format($transaction->total_price, 0, ',', '.') . "*\n\n";
            $message .= "📍 *Informasi Check-in:*\n";
            $message .= "Silakan datang ke front desk UNS Inn Hotel dengan membawa:\n";
            $message .= "• Bukti pembayaran ini\n";
            $message .= "• Kartu identitas (KTP/SIM/Paspor)\n\n";
            $message .= "Kami menantikan kedatangan Anda!\n";
            $message .= "Semoga menginap yang menyenangkan! 😊🏨";

            $this->send_message($transaction->phone, $message);
            
            Log::info('Payment success message sent', [
                'invoice' => $transaction->invoice,
                'phone' => $transaction->phone,
                'room_number' => $transaction->room_number
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send payment success message: ' . $e->getMessage(), [
                'invoice' => $transaction->invoice
            ]);
        }
    }

    /**
     * Send saldo payment success notification
     */
    private function sendSaldoPaymentSuccessMessage($transaction)
    {
        try {
            $checkIn = $this->formatDateTimeForUser($transaction->check_in, 'l, d M Y');
            $checkOut = $this->formatDateTimeForUser($transaction->check_out, 'l, d M Y');
            
            $message = "✅ *PEMBAYARAN SALDO BERHASIL - UNS Inn Hotel* ✅\n\n";
            $message .= "Halo {$transaction->name}!\n";
            $message .= "Pembayaran dengan saldo Anda telah berhasil diproses dan dikonfirmasi.\n\n";
            $message .= "🎉 *Detail Reservasi Terkonfirmasi:*\n";
            $message .= "• Invoice: *{$transaction->invoice}*\n";
            $message .= "• Nomor Kamar: *{$transaction->room_number}*\n";
            $message .= "• Tipe Kamar: *{$transaction->room->name}*\n";
            $message .= "• Check-in: *{$checkIn}*\n";
            $message .= "• Check-out: *{$checkOut}*\n";
            $message .= "• Total Dibayar: *Rp " . number_format($transaction->total_price, 0, ',', '.') . "* (Saldo)\n\n";
            $message .= "💰 Saldo Anda telah dipotong sesuai dengan total pembayaran.\n\n";
            $message .= "📍 *Informasi Check-in:*\n";
            $message .= "Silakan datang ke front desk UNS Inn Hotel dengan membawa kartu identitas.\n\n";
            $message .= "Terima kasih telah menggunakan layanan saldo kami!\n";
            $message .= "Semoga menginap yang menyenangkan! 😊🏨";

            $this->send_message($transaction->phone, $message);
            
            Log::info('Saldo payment success message sent', [
                'invoice' => $transaction->invoice,
                'phone' => $transaction->phone,
                'room_number' => $transaction->room_number
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send saldo payment success message: ' . $e->getMessage(), [
                'invoice' => $transaction->invoice
            ]);
        }
    }

    /**
     * Send cash payment reminder notification
     */
    private function sendCashPaymentReminderMessage($transaction)
    {
        try {
            $checkIn = $this->formatDateTimeForUser($transaction->check_in, 'l, d M Y');
            $checkOut = $this->formatDateTimeForUser($transaction->check_out, 'l, d M Y');
            
            $message = "💰 *PENGINGAT PEMBAYARAN CASH - UNS Inn Hotel* 💰\n\n";
            $message .= "Halo {$transaction->name}!\n";
            $message .= "Reservasi Anda telah dikonfirmasi dengan metode pembayaran Cash.\n\n";
            $message .= "🎯 *Detail Reservasi:*\n";
            $message .= "• Invoice: *{$transaction->invoice}*\n";
            $message .= "• Nomor Kamar: *{$transaction->room_number}*\n";
            $message .= "• Tipe Kamar: *{$transaction->room->name}*\n";
            $message .= "• Check-in: *{$checkIn}*\n";
            $message .= "• Check-out: *{$checkOut}*\n";
            $message .= "• Total Pembayaran: *Rp " . number_format($transaction->total_price, 0, ',', '.') . "*\n\n";
            $message .= "💡 *Pengingat Penting:*\n";
            $message .= "Silakan lakukan pembayaran secara langsung di front desk UNS Inn Hotel saat check-in atau sebelumnya.\n\n";
            $message .= "📋 Pastikan membawa:\n";
            $message .= "• Invoice ini sebagai bukti reservasi\n";
            $message .= "• Uang tunai sesuai total pembayaran\n";
            $message .= "• Kartu identitas\n\n";
            $message .= "Kami menantikan kedatangan Anda! 😊🏨";

            $this->send_message($transaction->phone, $message);
            
            Log::info('Cash payment reminder message sent', [
                'invoice' => $transaction->invoice,
                'phone' => $transaction->phone,
                'room_number' => $transaction->room_number
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send cash payment reminder message: ' . $e->getMessage(), [
                'invoice' => $transaction->invoice
            ]);
        }
    }

    /**
     * Send payment failed notification
     */
    private function sendPaymentFailedMessage($transaction)
    {
        try {
            $message = "❌ *PEMBAYARAN GAGAL - UNS Inn Hotel* ❌\n\n";
            $message .= "Halo {$transaction->name},\n\n";
            $message .= "Mohon maaf, pembayaran untuk reservasi Anda tidak dapat diproses.\n\n";
            $message .= "📋 *Detail Reservasi:*\n";
            $message .= "• Invoice: *{$transaction->invoice}*\n";
            $message .= "• Tipe Kamar: *{$transaction->room->name}*\n";
            $message .= "• Total: *Rp " . number_format($transaction->total_price, 0, ',', '.') . "*\n\n";
            $message .= "🔄 *Langkah Selanjutnya:*\n";
            $message .= "• Coba lakukan pembayaran ulang\n";
            $message .= "• Periksa saldo atau limit kartu Anda\n";
            $message .= "• Hubungi customer service jika masalah berlanjut\n\n";
            $message .= "Jika Anda masih ingin melakukan reservasi, silakan lakukan pemesanan ulang.\n\n";
            $message .= "Terima kasih atas pengertiannya. 😊";

            $this->send_message($transaction->phone, $message);
            
            Log::info('Payment failed message sent', [
                'invoice' => $transaction->invoice,
                'phone' => $transaction->phone
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send payment failed message: ' . $e->getMessage(), [
                'invoice' => $transaction->invoice
            ]);
        }
    }

    /**
     * Send split payment cash confirmation message
     */
    private function sendSplitPaymentCashConfirmationMessage($transaction)
    {
        try {
            $checkIn = $this->formatDateTimeForUser($transaction->check_in, 'l, d M Y');
            $checkOut = $this->formatDateTimeForUser($transaction->check_out, 'l, d M Y');
            
            $message = "🏨 *SPLIT PAYMENT CONFIRMATION - UNS Inn Hotel* 🏨\n\n";
            $message .= "Halo {$transaction->name}!\n";
            $message .= "Split payment (Saldo + Cash) Anda telah dikonfirmasi.\n\n";
            $message .= "🎯 *Detail Reservasi:*\n";
            $message .= "• Invoice: *{$transaction->invoice}*\n";
            $message .= "• Nomor Kamar: *{$transaction->room_number}*\n";
            $message .= "• Tipe Kamar: *{$transaction->room->name}*\n";
            $message .= "• Check-in: *{$checkIn}*\n";
            $message .= "• Check-out: *{$checkOut}*\n\n";
            $message .= "💰 *Rincian Pembayaran:*\n";
            $message .= "• Total: *Rp " . number_format($transaction->total_price, 0, ',', '.') . "*\n";
            $message .= "• Metode: *Saldo + Cash*\n\n";
            $message .= "📝 *Pengingat:*\n";
            $message .= "Sebagian pembayaran telah dipotong dari saldo, sisanya dapat dibayar cash saat check-in.\n\n";
            $message .= "Terima kasih! 😊";

            $this->send_message($transaction->phone, $message);
        } catch (\Exception $e) {
            Log::error('Failed to send split payment cash confirmation message: ' . $e->getMessage());
        }
    }

    /**
     * Send split payment Flip success notification
     */
    private function sendSplitPaymentFlipSuccessMessage($transaction)
    {
        try {
            $checkIn = $this->formatDateTimeForUser($transaction->check_in, 'l, d M Y');
            $checkOut = $this->formatDateTimeForUser($transaction->check_out, 'l, d M Y');
            
            $message = "✅ *SPLIT PAYMENT BERHASIL - UNS Inn Hotel* ✅\n\n";
            $message .= "Halo {$transaction->name}!\n";
            $message .= "Split payment (Saldo + Flip) Anda telah berhasil diproses.\n\n";
            $message .= "🎉 *Detail Reservasi Terkonfirmasi:*\n";
            $message .= "• Invoice: *{$transaction->invoice}*\n";
            $message .= "• Nomor Kamar: *{$transaction->room_number}*\n";
            $message .= "• Tipe Kamar: *{$transaction->room->name}*\n";
            $message .= "• Check-in: *{$checkIn}*\n";
            $message .= "• Check-out: *{$checkOut}*\n\n";
            $message .= "💰 *Rincian Pembayaran:*\n";
            $message .= "• Total Dibayar: *Rp " . number_format($transaction->total_price, 0, ',', '.') . "*\n";
            $message .= "• Metode: *Saldo + Online Payment*\n\n";
            $message .= "Sebagian dari saldo Anda dan pembayaran online telah berhasil diproses.\n\n";
            $message .= "Kami menantikan kedatangan Anda! Semoga menginap yang menyenangkan! 😊🏨";

            $this->send_message($transaction->phone, $message);
        } catch (\Exception $e) {
            Log::error('Failed to send split payment Flip success message: ' . $e->getMessage());
        }
    }

    /**
     * Send split payment cash booking message
     */
    private function sendSplitPaymentCashBookingMessage($transaction, $saldoAmount, $cashAmount)
    {
        try {
            $checkIn = $this->formatDateTimeForUser($transaction->check_in, 'l, d M Y');
            $checkOut = $this->formatDateTimeForUser($transaction->check_out, 'l, d M Y');
            
            $message = "🏨 *SPLIT PAYMENT BOOKING - UNS Inn Hotel* 🏨\n\n";
            $message .= "Halo {$transaction->name}!\n";
            $message .= "Reservasi Anda dengan split payment (Saldo + Cash) telah dikonfirmasi.\n\n";
            $message .= "📋 *Detail Reservasi:*\n";
            $message .= "• Invoice: *{$transaction->invoice}*\n";
            $message .= "• Tipe Kamar: *{$transaction->room->name}*\n";
            $message .= "• Check-in: *{$checkIn}*\n";
            $message .= "• Check-out: *{$checkOut}*\n\n";
            $message .= "💰 *Rincian Pembayaran:*\n";
            $message .= "• Dari Saldo: *Rp " . number_format($saldoAmount, 0, ',', '.') . "*\n";
            $message .= "• Sisa (Cash): *Rp " . number_format($cashAmount, 0, ',', '.') . "*\n";
            $message .= "• Total: *Rp " . number_format($saldoAmount + $cashAmount, 0, ',', '.') . "*\n\n";
            $message .= "📝 Saldo Anda telah dipotong, sisanya dapat dibayar cash saat check-in.\n\n";
            $message .= "Terima kasih! 😊";

            $this->send_message($transaction->phone, $message);
        } catch (\Exception $e) {
            Log::error('Failed to send split payment cash booking message: ' . $e->getMessage());
        }
    }

    /**
     * Send split payment Flip booking message
     */
    private function sendSplitPaymentFlipBookingMessage($transaction, $saldoAmount, $flipAmount)
    {
        try {
            $checkIn = $this->formatDateTimeForUser($transaction->check_in, 'l, d M Y');
            $checkOut = $this->formatDateTimeForUser($transaction->check_out, 'l, d M Y');
            $paymentDeadline = $this->formatDateTimeForUser($transaction->payment_deadline, 'd M Y H:i');
            
            $message = "🏨 *SPLIT PAYMENT BOOKING - UNS Inn Hotel* 🏨\n\n";
            $message .= "Halo {$transaction->name}!\n";
            $message .= "Reservasi Anda dengan split payment (Saldo + Online) telah dikonfirmasi.\n\n";
            $message .= "📋 *Detail Reservasi:*\n";
            $message .= "• Invoice: *{$transaction->invoice}*\n";
            $message .= "• Tipe Kamar: *{$transaction->room->name}*\n";
            $message .= "• Check-in: *{$checkIn}*\n";
            $message .= "• Check-out: *{$checkOut}*\n\n";
            $message .= "💰 *Rincian Pembayaran:*\n";
            $message .= "• Dari Saldo: *Rp " . number_format($saldoAmount, 0, ',', '.') . "*\n";
            $message .= "• Sisa (Online): *Rp " . number_format($flipAmount, 0, ',', '.') . "*\n";
            $message .= "• Total: *Rp " . number_format($saldoAmount + $flipAmount, 0, ',', '.') . "*\n\n";
            $message .= "⏰ *Batas Waktu Pembayaran Online:*\n";
            $message .= "{$paymentDeadline} WIB\n\n";
            $message .= "📝 Sebagian dari saldo Anda telah dipotong. Silakan selesaikan pembayaran online untuk sisa tagihan.\n\n";
            $message .= "Terima kasih! 😊";

            $this->send_message($transaction->phone, $message);
        } catch (\Exception $e) {
            Log::error('Failed to send split payment Flip booking message: ' . $e->getMessage());
        }
    }

    /**
     * Send Flip callback success notification
     */
    private function sendFlipCallbackSuccessMessage($transaction)
    {
        try {
            $message = "🎉 *KONFIRMASI OTOMATIS - UNS Inn Hotel* 🎉\n\n";
            $message .= "Halo {$transaction->name}!\n";
            $message .= "Pembayaran Anda telah berhasil dikonfirmasi secara otomatis oleh sistem.\n\n";
            $message .= "✅ *Status:* PAID\n";
            $message .= "• Invoice: *{$transaction->invoice}*\n";
            $message .= "• Nomor Kamar: *{$transaction->room_number}*\n";
            $message .= "• Total: *Rp " . number_format($transaction->total_price, 0, ',', '.') . "*\n\n";
            $message .= "Reservasi Anda telah terkonfirmasi. Selamat datang di UNS Inn Hotel! 😊";

            $this->send_message($transaction->phone, $message);
        } catch (\Exception $e) {
            Log::error('Failed to send Flip callback success message: ' . $e->getMessage());
        }
    }

    /**
     * Send Flip callback failure notification
     */
    private function sendFlipCallbackFailureMessage($transaction)
    {
        try {
            $message = "⚠️ *NOTIFIKASI OTOMATIS - UNS Inn Hotel* ⚠️\n\n";
            $message .= "Halo {$transaction->name},\n\n";
            $message .= "Sistem telah mendeteksi bahwa pembayaran Anda tidak dapat diproses.\n\n";
            $message .= "❌ *Status:* CANCELLED\n";
            $message .= "• Invoice: *{$transaction->invoice}*\n";
            $message .= "• Total: *Rp " . number_format($transaction->total_price, 0, ',', '.') . "*\n\n";
            $message .= "Reservasi telah dibatalkan dan kamar dikembalikan ke sistem.\n\n";
            $message .= "Silakan lakukan pemesanan ulang jika diperlukan. Terima kasih! 😊";

            $this->send_message($transaction->phone, $message);
        } catch (\Exception $e) {
            Log::error('Failed to send Flip callback failure message: ' . $e->getMessage());
        }
    }

    /**
     * Send Flip webhook success notification
     */
    private function sendFlipWebhookSuccessMessage($transaction)
    {
        try {
            $message = "🔔 *KONFIRMASI WEBHOOK - UNS Inn Hotel* 🔔\n\n";
            $message .= "Halo {$transaction->name}!\n";
            $message .= "Pembayaran Anda telah berhasil diverifikasi melalui webhook.\n\n";
            $message .= "✅ Status pembayaran: *CONFIRMED*\n";
            $message .= "• Invoice: *{$transaction->invoice}*\n";
            $message .= "• Nomor Kamar: *{$transaction->room_number}*\n\n";
            $message .= "Reservasi Anda telah final. Terima kasih! 😊";

            $this->send_message($transaction->phone, $message);
        } catch (\Exception $e) {
            Log::error('Failed to send Flip webhook success message: ' . $e->getMessage());
        }
    }

    /**
     * Send Flip webhook failure notification
     */
    private function sendFlipWebhookFailureMessage($transaction)
    {
        try {
            $message = "🔔 *NOTIFIKASI WEBHOOK - UNS Inn Hotel* 🔔\n\n";
            $message .= "Halo {$transaction->name},\n\n";
            $message .= "Webhook telah mengkonfirmasi bahwa pembayaran tidak berhasil.\n\n";
            $message .= "❌ Status: *CANCELLED*\n";
            $message .= "• Invoice: *{$transaction->invoice}*\n\n";
            $message .= "Kamar telah dikembalikan ke sistem. Silakan lakukan pemesanan ulang jika diperlukan.\n\n";
            $message .= "Terima kasih! 😊";

            $this->send_message($transaction->phone, $message);
        } catch (\Exception $e) {
            Log::error('Failed to send Flip webhook failure message: ' . $e->getMessage());
        }
    }

    /**
     * Send check-in reminder (can be called by scheduler)
     */
    public function sendCheckInReminder($transaction)
    {
        try {
            $checkIn = $this->formatDateTimeForUser($transaction->check_in, 'l, d M Y');
            $today = Carbon::now()->format('Y-m-d');
            $checkInDate = Carbon::parse($transaction->check_in)->format('Y-m-d');
            
            // Send reminder 1 day before check-in
            if (Carbon::parse($checkInDate)->subDay()->format('Y-m-d') == $today) {
                $message = "🔔 *PENGINGAT CHECK-IN - UNS Inn Hotel* 🔔\n\n";
                $message .= "Halo {$transaction->name}!\n";
                $message .= "Pengingat bahwa check-in Anda di UNS Inn Hotel adalah besok.\n\n";
                $message .= "📅 *Detail Check-in:*\n";
                $message .= "• Tanggal: *{$checkIn}*\n";
                $message .= "• Nomor Kamar: *{$transaction->room_number}*\n";
                $message .= "• Invoice: *{$transaction->invoice}*\n\n";
                $message .= "📋 *Jangan lupa bawa:*\n";
                $message .= "• Kartu identitas (KTP/SIM/Paspor)\n";
                $message .= "• Bukti reservasi ini\n\n";
                $message .= "Kami menantikan kedatangan Anda! 😊";

                $this->send_message($transaction->phone, $message);
                
                Log::info('Check-in reminder sent', [
                    'invoice' => $transaction->invoice,
                    'check_in_date' => $checkInDate
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send check-in reminder: ' . $e->getMessage());
        }
    }

    /**
     * Send check-out reminder (can be called by scheduler)
     */
    public function sendCheckOutReminder($transaction)
    {
        try {
            $checkOut = $this->formatDateTimeForUser($transaction->check_out, 'l, d M Y');
            $today = Carbon::now()->format('Y-m-d');
            $checkOutDate = Carbon::parse($transaction->check_out)->format('Y-m-d');
            
            // Send reminder on check-out day
            if ($checkOutDate == $today) {
                $message = "🔔 *PENGINGAT CHECK-OUT - UNS Inn Hotel* 🔔\n\n";
                $message .= "Halo {$transaction->name}!\n";
                $message .= "Hari ini adalah hari check-out Anda dari UNS Inn Hotel.\n\n";
                $message .= "📅 *Detail Check-out:*\n";
                $message .= "• Tanggal: *{$checkOut}*\n";
                $message .= "• Nomor Kamar: *{$transaction->room_number}*\n";
                $message .= "• Invoice: *{$transaction->invoice}*\n\n";
                $message .= "⏰ *Waktu Check-out:* Sebelum 12:00 WIB\n\n";
                $message .= "📝 Silakan selesaikan proses check-out di front desk.\n\n";
                $message .= "Terima kasih telah menginap di UNS Inn Hotel!\n";
                $message .= "Semoga Anda puas dengan layanan kami. 😊";

                $this->send_message($transaction->phone, $message);
                
                Log::info('Check-out reminder sent', [
                    'invoice' => $transaction->invoice,
                    'check_out_date' => $checkOutDate
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send check-out reminder: ' . $e->getMessage());
        }
    }

    /**
     * Send thank you message after check-out
     */
    public function sendThankYouMessage($transaction)
    {
        try {
            $message = "🙏 *TERIMA KASIH - UNS Inn Hotel* 🙏\n\n";
            $message .= "Halo {$transaction->name}!\n";
            $message .= "Terima kasih telah menginap di UNS Inn Hotel.\n\n";
            $message .= "🏨 *Ringkasan Menginap:*\n";
            $message .= "• Invoice: *{$transaction->invoice}*\n";
            $message .= "• Nomor Kamar: *{$transaction->room_number}*\n";
            $message .= "• Tipe Kamar: *{$transaction->room->name}*\n\n";
            $message .= "⭐ Kami berharap Anda puas dengan layanan kami.\n\n";
            $message .= "📝 Jika ada feedback atau saran, jangan ragu untuk menghubungi kami.\n\n";
            $message .= "🔄 Kami menantikan kunjungan Anda kembali di masa depan!\n\n";
            $message .= "Salam hangat dari tim UNS Inn Hotel! 😊🏨";

            $this->send_message($transaction->phone, $message);
            
            Log::info('Thank you message sent', [
                'invoice' => $transaction->invoice
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send thank you message: ' . $e->getMessage());
        }
    }

    // ===============================
    // EXISTING HELPER METHODS
    // ===============================

    private function isRoomAvailableForDates($roomId, $checkIn, $checkOut)
    {
        // Ambil data room untuk mendapatkan total_rooms
        $room = Room::find($roomId);
        if (!$room) {
            return false;
        }
        
        // Hitung berapa kamar yang sudah dibooking untuk periode yang overlap
        $overlappingBookings = Transaction::where('room_id', $roomId)
            ->where('payment_status', '!=', 'CANCELLED') // Exclude cancelled bookings
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->where(function ($q) use ($checkIn, $checkOut) {
                    // Case 1: Booking existing dimulai di antara tanggal yang diminta
                    $q->where('check_in', '>=', $checkIn)
                    ->where('check_in', '<', $checkOut);
                })->orWhere(function ($q) use ($checkIn, $checkOut) {
                    // Case 2: Booking existing berakhir di antara tanggal yang diminta  
                    $q->where('check_out', '>', $checkIn)
                    ->where('check_out', '<=', $checkOut);
                })->orWhere(function ($q) use ($checkIn, $checkOut) {
                    // Case 3: Booking existing mencakup seluruh periode yang diminta
                    $q->where('check_in', '<=', $checkIn)
                    ->where('check_out', '>=', $checkOut);
                })->orWhere(function ($q) use ($checkIn, $checkOut) {
                    // Case 4: Periode yang diminta mencakup seluruh booking existing
                    $q->where('check_in', '>=', $checkIn)
                    ->where('check_out', '<=', $checkOut);
                });
            })
            ->count();

        // Bandingkan dengan total kamar yang tersedia
        // Jika booking yang overlap kurang dari total kamar, berarti masih tersedia
        return $overlappingBookings < $room->total_rooms;
    }

    private function getAvailableRoomsCount($roomId, $checkIn, $checkOut)
    {
        $room = Room::find($roomId);
        if (!$room) {
            return 0;
        }
        
        $overlappingBookings = Transaction::where('room_id', $roomId)
            ->where('payment_status', '!=', 'CANCELLED')
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->where(function ($q) use ($checkIn, $checkOut) {
                    $q->where('check_in', '>=', $checkIn)
                    ->where('check_in', '<', $checkOut);
                })->orWhere(function ($q) use ($checkIn, $checkOut) {
                    $q->where('check_out', '>', $checkIn)
                    ->where('check_out', '<=', $checkOut);
                })->orWhere(function ($q) use ($checkIn, $checkOut) {
                    $q->where('check_in', '<=', $checkIn)
                    ->where('check_out', '>=', $checkOut);
                })->orWhere(function ($q) use ($checkIn, $checkOut) {
                    $q->where('check_in', '>=', $checkIn)
                    ->where('check_out', '<=', $checkOut);
                });
            })
            ->count();

        return $room->total_rooms - $overlappingBookings;
    }

    private function generateUniqueInvoice()
    {
        do {
            $timestamp = date('ymd'); // Format: 241207 untuk 2024-12-07
            $random = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $invoice = 'MH-' . $timestamp . '-' . $random;
            
            // Check if invoice already exists
            $exists = Transaction::where('invoice', $invoice)->exists();
        } while ($exists);
        
        return $invoice;
    }

    private function assignAvailableRoomNumber($roomId, $checkIn, $checkOut)
    {
        $room = Room::find($roomId);
        if (!$room) {
            return null;
        }

        // Get all occupied room numbers for the overlapping date range
        $occupiedRoomNumbers = Transaction::where('room_id', $roomId)
            ->where('payment_status', '!=', 'CANCELLED')
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->where(function ($q) use ($checkIn, $checkOut) {
                    $q->where('check_in', '>=', $checkIn)
                    ->where('check_in', '<', $checkOut);
                })->orWhere(function ($q) use ($checkIn, $checkOut) {
                    $q->where('check_out', '>', $checkIn)
                    ->where('check_out', '<=', $checkOut);
                })->orWhere(function ($q) use ($checkIn, $checkOut) {
                    $q->where('check_in', '<=', $checkIn)
                    ->where('check_out', '>=', $checkOut);
                })->orWhere(function ($q) use ($checkIn, $checkOut) {
                    $q->where('check_in', '>=', $checkIn)
                    ->where('check_out', '<=', $checkOut);
                });
            })
            ->whereNotNull('room_number')
            ->pluck('room_number')
            ->toArray();

        // Find first available room number
        for ($roomNumber = 1; $roomNumber <= $room->total_rooms; $roomNumber++) {
            if (!in_array($roomNumber, $occupiedRoomNumbers)) {
                return $roomNumber;
            }
        }

        // If no room number available, return null
        return null;
    }

    private function getTimezoneAwareCarbon($datetime = null)
    {
        $userTimezone = config('app.timezone', 'Asia/Jakarta');
        
        if ($datetime) {
            return Carbon::parse($datetime, $userTimezone);
        }
        
        return Carbon::now($userTimezone);
    }

    private function formatDateTimeForUser($datetime, $format = 'Y-m-d H:i:s')
    {
        if (!$datetime) {
            return null;
        }

        $userTimezone = config('app.timezone', 'Asia/Jakarta');
        
        if (is_string($datetime)) {
            $datetime = Carbon::parse($datetime);
        }
        
        return $datetime->setTimezone($userTimezone)->format($format);
    }
}
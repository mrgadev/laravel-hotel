<?php

use App\Models\HotelFacilities;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PointUserController;
use App\Http\Controllers\User\BookingController;
use App\Http\Controllers\PaymentController;

Route::middleware('auth')->prefix('/payment')->name('payment.')->group(function(){
    Route::get('/pay/{transaction:invoice}', [PaymentController::class, 'bill'])->name('bill');
    Route::post('/store/cash', [PaymentController::class, 'cashPayment'])->name('cash');
    Route::post('/store/online', [PaymentController::class, 'onlinePayment'])->name('online');
    Route::post('/store/creditPayment', [PaymentController::class, 'creditPayment'])->name('creditPayment');
    Route::post('/store/addCash', [PaymentController::class, 'addCash'])->name('addCash');
    Route::post('/store/addXendit', [PaymentController::class, 'addXendit'])->name('addXendit');

    Route::get('/success/{transaction:invoice}', [PaymentController::class, 'success'])->name('success');
    Route::get('/failed/{id}', [PaymentController::class, 'failed'])->name('failed');
    Route::get('/timeout/{transaction:invoice}', [PaymentController::class, 'timeout'])->name('timeout');
});

// Webhook Xendit - TANPA middleware auth dan CSRF
Route::prefix('/payment/xendit')->name('payment.xendit.')->group(function() {
    Route::post('/webhook', [PaymentController::class, 'xenditWebhook'])->name('webhook');
    Route::post('/callback', [PaymentController::class, 'xenditWebhook'])->name('callback'); // alternative endpoint
    Route::any('/notify', [PaymentController::class, 'xenditWebhook'])->name('notify'); // support GET/POST
});

// Route tambahan untuk testing webhook (development only)
if (app()->environment(['local', 'development'])) {
    Route::get('/payment/xendit/test-webhook', function() {
        return response()->json(['message' => 'Webhook endpoint is accessible']);
    });
}

// use App\Models\HotelFacilities;
// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\PointUserController;
// use App\Http\Controllers\User\BookingController;
// use App\Http\Controllers\PaymentController;

// Route::middleware('auth')->prefix('/payment')->name('payment.')->group(function(){
//     Route::get('/pay/{transaction:invoice}', [PaymentController::class, 'bill'])->name('bill');
//     Route::post('/store/cash', [PaymentController::class, 'cashPayment'])->name('cash');
//     Route::post('/store/online', [PaymentController::class, 'onlinePayment'])->name('online');
//     Route::post('/store/creditPayment', [PaymentController::class, 'creditPayment'])->name('creditPayment');
//     Route::post('/store/addCash', [PaymentController::class, 'addCash'])->name('addCash');
//     Route::post('/store/addIPaymu', [PaymentController::class, 'addIPaymu'])->name('addIPaymu'); // Changed from addFlip to addIPaymu

//     Route::get('/success/{transaction:invoice}', [PaymentController::class, 'success'])->name('success');
//     Route::get('/failed/{id}', [PaymentController::class, 'failed'])->name('failed');
//     Route::get('/timeout/{transaction:invoice}', [PaymentController::class, 'timeout'])->name('timeout');
// });

// // Routes untuk iPaymu callbacks dan webhooks (tidak perlu middleware auth)
// Route::prefix('/payment/ipaymu')->name('payment.ipaymu.')->group(function() {
//     // Return URL - dipanggil setelah user melakukan pembayaran
//     Route::get('/return', [PaymentController::class, 'iPaymuReturn'])->name('return');
    
//     // Callback/Notify URL - untuk notifikasi status pembayaran dari iPaymu
//     Route::post('/notify', [PaymentController::class, 'iPaymuNotify'])->name('notify');
    
//     // Cancel URL - jika user membatalkan pembayaran
//     Route::get('/cancel', [PaymentController::class, 'iPaymuCancel'])->name('cancel');
// });
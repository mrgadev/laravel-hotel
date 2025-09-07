<?php

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
//     Route::post('/store/addXendit', [PaymentController::class, 'addXendit'])->name('addXendit');

//     Route::get('/success/{transaction:invoice}', [PaymentController::class, 'success'])->name('success');
//     Route::get('/failed/{id}', [PaymentController::class, 'failed'])->name('failed');
//     Route::get('/timeout/{transaction:invoice}', [PaymentController::class, 'timeout'])->name('timeout');
// });

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
    Route::post('/store/addFlip', [PaymentController::class, 'addFlip'])->name('addFlip'); // Changed from addXendit to addFlip

    Route::get('/success/{transaction:invoice}', [PaymentController::class, 'success'])->name('success');
    Route::get('/failed/{id}', [PaymentController::class, 'failed'])->name('failed');
    Route::get('/timeout/{transaction:invoice}', [PaymentController::class, 'timeout'])->name('timeout');
});

// Routes untuk Flip callbacks dan webhooks (tidak perlu middleware auth)
Route::prefix('/payment/flip')->name('payment.flip.')->group(function() {
    // Callback URL - dipanggil setelah user melakukan pembayaran
    Route::post('/callback', [PaymentController::class, 'flipCallback'])->name('callback');
    
    // Webhook URL - untuk notifikasi status pembayaran dari Flip
    Route::post('/webhook', [PaymentController::class, 'flipWebhook'])->name('webhook');
});
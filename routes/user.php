<?php

use App\Models\HotelFacilities;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PointUserController;
use App\Http\Controllers\Admin\RoomReviewController;
use App\Http\Controllers\User\BookingController;
use App\Http\Controllers\AccomodationPlanController;
use App\Http\Controllers\Admin\WithdrawController;
use App\Http\Controllers\SaldoController;

Route::middleware('auth')->prefix('/dashboard')->name('dashboard.')->group(function(){
    
    Route::get('/my-bookings', [BookingController::class, 'index'])->name('user.bookings')->middleware('role:user');
    Route::post('/my-bookings/check-in/{transaction:invoice}', [BookingController::class, 'checkIn'])->name('user.bookings.checkin')->middleware('role:user');
    Route::post('/my-bookings/check-out/{transaction:invoice}', [BookingController::class, 'checkOut'])->name('user.bookings.checkout')->middleware('role:user');
    Route::get('/my-bookings/detail/{transaction:invoice}', [BookingController::class, 'detail'])->name('user.bookings.detail');
    Route::get('/my-bookings/export/{transaction:invoice}', [BookingController::class, 'export'])->name('user.bookings.export');
    // Route::get('/my-point', [PointUserController::class, 'index'])->name('user.point')->middleware('role:user');
    // Route::get('/my-point/detail', [PointUserController::class, 'detail'])->name('user.point.detail')->middleware('role:user');
    Route::get('/my-wallet', [SaldoController::class, 'index'])->name('user.saldo')->middleware('role:user');
    Route::resource('/room-review', RoomReviewController::class)->names('room-review');
    Route::get('/withdraw/create', [WithdrawController::class, 'create'])->name('withdraw.create');
    Route::post('/withdraw', [WithdrawController::class, 'store'])->name('withdraw.store');
    Route::get('/withdraw/success/{id}', [WithdrawController::class, 'success'])->name('withdraw.success');
});
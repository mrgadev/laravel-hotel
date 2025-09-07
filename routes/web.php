<?php

use App\Models\Message;
use App\Models\HotelFacilities;
use App\Http\Controllers\BulkAction;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\PromoController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ServiceController;
use Symfony\Component\Mime\MessageConverter;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\FrontpageController;
use App\Http\Controllers\Admin\RoomReviewController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\SiteSettingsController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\Admin\NearbyLocationController;
use App\Http\Controllers\Admin\RoomFacilitiesController;
use App\Http\Controllers\Admin\AccomdationPlanController;
use App\Http\Controllers\Admin\FeedbackController;
use App\Http\Controllers\Admin\BankController;
use App\Http\Controllers\Admin\HotelAwardController;
use App\Http\Controllers\Admin\HotelFacilitiesController;
use App\Http\Controllers\Admin\HotelServiceController;
use App\Http\Controllers\Admin\WithdrawController;
use App\Http\Controllers\Admin\RoomRuleController;
use App\Http\Controllers\Admin\SaldoController;
use App\Http\Controllers\Admin\ServiceCategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SiteSettingPartnerController;

require __DIR__ . '/auth.php';
// require __DIR__ . '/frontpage.php';
require __DIR__ . '/user.php';
require __DIR__ . '/payment.php';
require __DIR__ . '/admin.php';
// require __DIR__ . '/web.php';
Route::name('frontpage.')->group(function() {
    Route::get('/', [FrontpageController::class, 'index'])->name('index');

    Route::get('/checkout/{id}', [FrontpageController::class, 'checkout'])->name('checkout');

    Route::get('/promo', [FrontpageController::class, 'promo'])->name('promo');

    Route::get('/kamar', [FrontpageController::class, 'rooms'])->name('rooms');

    Route::get('/kamar/detail/{room:slug}', [FrontpageController::class, 'room_detail'])->name('rooms.detail');

    Route::get('/layanan-lainnya', [FrontpageController::class, 'services'])->name('services');

    Route::get('/layanan-lainnya/{id}', [FrontpageController::class, 'services_detail'])->name('services.detail');

    Route::get('/kontak', [FrontpageController::class, 'contact'])->name('contact');

    Route::get('/tentang-kami', [FrontpageController::class, 'about'])->name('about');

    Route::post('/search-rooms', [FrontpageController::class, 'search'])->name('search');
    
    Route::post('/pesan', [FeedbackController::class, 'store'])->name('message.store');
});

Route::prefix('ajax')->name('ajax.')->group(function () {
    Route::post('/login', [App\Http\Controllers\Auth\AjaxAuthController::class, 'login'])->name('login');
    Route::post('/register', [App\Http\Controllers\Auth\AjaxAuthController::class, 'register'])->name('register');
    Route::post('/verify-otp', [App\Http\Controllers\Auth\AjaxAuthController::class, 'verifyOtp'])->name('verify.otp');
    Route::post('/resend-otp', [App\Http\Controllers\Auth\AjaxAuthController::class, 'resendOtp'])->name('resend.otp');
});

Route::get('/auth/google/redirect', [SocialiteController::class, 'redirectToGoogle'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [SocialiteController::class, 'handleGoogleCallback'])->name('auth.google.callback');
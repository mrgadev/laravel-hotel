<?php

use App\Http\Controllers\Admin\AccomodationPlanController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BankController;
use App\Http\Controllers\Admin\BulkActionController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\HotelAwardController;
use App\Http\Controllers\Admin\HotelFacilityController;
use App\Http\Controllers\Admin\HotelServiceController;
use App\Http\Controllers\Admin\FeedbackController;
use App\Http\Controllers\Admin\NearbyLocationController;
// use App\Http\Controllers\Admin\Withdraw;
use App\Http\Controllers\Admin\PromoController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\RoomFacilityController;
use App\Http\Controllers\Admin\RoomRuleController;
// use App\Http\Controllers\SaldoController;
use App\Http\Controllers\Admin\ServiceCategoryController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\SiteSettingPartnerController;
use App\Http\Controllers\Admin\SiteSettingsController;
use App\Http\Controllers\Admin\TransactionController;
// use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\SaldoController;
use App\Http\Controllers\Admin\WithdrawController;

use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('/dashboard')->name('dashboard.')->group(function(){
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('/monthly-revenue', [TransactionController::class, 'getMonthlyRevenue'])->name('monthly-revenue');

    Route::get('/profile', [DashboardController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [DashboardController::class, 'updatePassword'])->name('profile.updatePassword');
    Route::delete('/profile', [DashboardController::class, 'destroy'])->name('profile.destroy');

    Route::resource('/fasilitas-hotel', HotelFacilityController::class)->names('hotel-facilities')->middleware('role:admin|staff');
    Route::post('/fasilitas-hotel/bulkDelete', [BulkActionController::class, 'hotelFacilitiesBulkDelete'])->name('hotel-facilities.bulkDelete')->middleware('role:admin|staff');

    Route::resource('/layanan-hotel', HotelServiceController::class)->names('hotel-services')->middleware('role:admin|staff');
    Route::post('/layanan-hotel/bulkDelete', [BulkActionController::class, 'hotelServicesBulkDelete'])->name('hotel-services.bulkDelete')->middleware('role:admin|staff');

    Route::resource('/hotel-awards', HotelAwardController::class)->names('hotel-awards')->middleware('role:admin|staff');
    Route::post('/penghargaan-hotel/bulkDelete', [BulkActionController::class, 'hotelAwardsBulkDelete'])->name('hotel-awards.bulkDelete')->middleware('role:admin|staff');

    Route::resource('/users-management', UserController::class)->middleware('role:admin');
    Route::put('/users-management/{users-management}/password', [UserController::class, 'updatePassword'])->name('users-management.updatePassword')->middleware('role:admin');
    Route::post('/users-management/bulkUpdateRole', [BulkActionController::class, 'updateRole'])->name('users-management.updateRole')->middleware('role:admin');

    Route::resource('/accomodation-plan', AccomodationPlanController::class)->middleware('role:admin|staff');
    Route::post('/accomodation-plan/bulkDelete', [BulkActionController::class, 'accomodationPlanBulkDelete'])->name('accomodation-plan.bulkDelete')->middleware('role:admin|staff');

    Route::resource('/room-facilities', RoomFacilityController::class)->middleware('role:admin|staff');
    Route::post('/room-facilities/bulkDelete', [BulkActionController::class, 'roomFacilitiesBulkDelete'])->name('room-facilities.bulkDelete')->middleware('role:admin|staff');

    Route::post('/room-review/bulkChangeVisibility', [BulkActionController::class, 'changeReviewVisibility'])->name('room-review.changeVisibility')->middleware('role:admin|staff');

    Route::resource('/nearby-location', NearbyLocationController::class)->middleware('role:admin|staff');
    Route::post('/nearby-location/bulkDelete', [BulkActionController::class, 'nearbyLocationBulkDelete'])->name('nearby-location.bulkDelete')->middleware('role:admin|staff');

    Route::resource('/faq', FaqController::class)->middleware('role:admin|staff');
    Route::post('/faq/bulkDelete', [BulkActionController::class, 'faqBulkDelete'])->name('faq.bulkDelete')->middleware('role:admin|staff');

    Route::resource('/room', RoomController::class)->middleware('role:admin|staff');
    Route::post('/room/bulkDelete', [BulkActionController::class, 'roomBulkDelete'])->name('room.bulkDelete')->middleware('role:admin|staff');

    Route::resource('/promo', PromoController::class)->middleware('role:admin|staff');
    Route::post('/promo/bulkDelete', [BulkActionController::class, 'promoBulkDelete'])->name('promo.bulkDelete')->middleware('role:admin|staff');

    Route::resource('/service', ServiceController::class)->middleware('role:admin|staff');
    Route::post('/service/bulkDelete', [BulkActionController::class,'serviceBulkDelete'])->name('service.bulkDelete')->middleware('role:admin|staff');

    Route::resource('/service-category', ServiceCategoryController::class)->middleware('role:admin|staff');
    Route::post('/service-category/bulkDelete', [BulkActionController::class,'serviceCategoryBulkDelete'])->name('service-category.bulkDelete')->middleware('role:admin|staff');

    Route::get('/transactions', [TransactionController::class, 'index'])->name('transaction.index')->middleware('role:admin|staff');
    Route::post('/transactions/bulkUpdateStatus', [BulkActionController::class, 'updateStatus'])->name('transactions.updateStatus')->middleware('role:admin');
    Route::post('/transactions/bulkUpdateStatusCheckin', [BulkActionController::class, 'updateStatusCheckin'])->name('transactions.updateStatusCheckin')->middleware('role:admin');
    Route::get('/transaction/{transaction:invoice}', [TransactionController::class, 'show'])->name('transaction.show')->middleware('role:admin|staff');
    Route::put('/transaction/{transaction}/checkin-status', [TransactionController::class, 'changeCheckInStatus'])->name('transaction.changeCheckInStatus')->middleware('role:admin|staff');
    Route::put('/transaction/{transaction}/payment-status', [TransactionController::class, 'changePaymentStatus'])->name('transaction.changePaymentStatus')->middleware('role:admin|staff');
    Route::put('/transaction/bulk-update-check-in-status', [TransactionController::class, 'updateCheckInStatus'])->name('transaction.bulkActionController')->middleware('role:admin|staff');

    Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback')->middleware('role:admin|staff');
    Route::post('/feedback/bulkDelete', [BulkActionController::class,'pesanBulkDelete'])->name('pesan.bulkDelete')->middleware('role:admin|staff');

    Route::get('/feedback/{feedback:slug}', [FeedbackController::class, 'show'])->name('feedback.show')->middleware('role:admin|staff');

    Route::delete('/pesan/{feedback}', [FeedbackController::class, 'destroy'])->name('feedback.delete')->middleware('role:admin|staff');

    Route::get('/site-settings', [SiteSettingController::class, 'edit'])->name('site.settings.edit')->middleware('role:admin|staff');
    Route::put('/site-settings/{site_setting}', [SiteSettingController::class, 'update'])->name('site.settings.update')->middleware('role:admin|staff');
    Route::get('/site-settings/frontpage', [SiteSettingController::class, 'frontpageEdit'])->name('site.settings.frontpage.edit')->middleware('role:admin|staff');
    Route::put('/site-settings/frontpage/{frontpage_site_setting}', [SiteSettingController::class, 'frontpageUpdate'])->name('site.settings.frontpage.update')->middleware('role:admin|staff');

    Route::resource('/partners', SiteSettingPartnerController::class)->middleware('role:admin');

    Route::get('/report', [ReportController::class, 'index'])->name('report.index')->middleware('role:admin|staff');
    Route::post('/export-transactions', [ReportController::class, 'exportTransactions'])->name('export-transactions');

    Route::resource('/bank', BankController::class);

    Route::resource('/saldo', SaldoController::class);
    Route::put('/saldo/{id}/cancel', [SaldoController::class, 'cancelTransaction'])->name('saldo.cancelTransaction');

    Route::get('/withdraw/index', [WithdrawController::class, 'index'])->name('withdraw.index');
    Route::get('/withdraw/{penarikanSaldo}/show', [WithdrawController::class, 'show'])->name('withdraw.show');
    Route::put('/withdraw/{penarikanSaldo}/update', [WithdrawController::class, 'update'])->name('withdraw.update');

    Route::resource('/room-rules', RoomRuleController::class)->middleware('role:admin|staff');
    Route::post('/room-rules/bulkDelete', [BulkActionController::class,'roomRuleBulkDelete'])->name('room-rules.bulkDelete')->middleware('role:admin|staff');
});
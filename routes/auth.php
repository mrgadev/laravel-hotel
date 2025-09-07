<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::get('login/email', [AuthenticatedSessionController::class, 'createEmail'])
        ->name('login.email');

    Route::get('login/email', [AuthenticatedSessionController::class, 'emailLogin'])
        ->name('login.email');

    
    Route::post('login/email/create', [AuthenticatedSessionController::class, 'emailLoginStore'])->name('login.email.store');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::post('login/email', [AuthenticatedSessionController::class, 'storeEmail'])->name('login.email.process');

    // Route::get('login/google/redirect', [SocialiteController::class, 'redirectToGoogle'])->name('login.google.redirect');
    // Route::get('login/google/callback', [SocialiteController::class, 'handleGoogleCallback'])->name('login.google.callback');

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::get('/verify/{phone}/{random_url}',[RegisteredUserController::class,'verify'])->name('verify');

    Route::post('/verify/process',[RegisteredUserController::class,'verify_process'])->name('verify.process');

    Route::post('/verify/resend',[RegisteredUserController::class,'resend'])->name('resend');

    // Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
    //     ->name('password.email');

    // Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
    //     ->name('password.reset');

    // Route::post('reset-password', [NewPasswordController::class, 'store'])
    //     ->name('password.store');
    Route::get('/forgot-password/phone', [ForgotPasswordController::class, 'forgotPasswordFormPhone'])->name('forgot.password.phone');
    Route::post('/forgot-password/process', [ForgotPasswordController::class, 'forgotPasswordProcess'])->name('forgot.password.process');
    Route::get('/forgot-password/verify/{phone}/{random_url}', [ForgotPasswordController::class, 'forgotPasswordVerify'])->name('forgot.password.verify');
    Route::post('/forgot-password/verify/process', [ForgotPasswordController::class, 'forgotPasswordVerifyProcess'])->name('forgot.password.verify.process');
    Route::get('/forgot-password/reset/{phone}/', [ForgotPasswordController::class, 'forgotPasswordReset'])->name('forgot.password.reset');
    Route::post('/forgot-password/reset/process', [ForgotPasswordController::class, 'forgotPasswordResetProcess'])->name('forgot.password.reset.process');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

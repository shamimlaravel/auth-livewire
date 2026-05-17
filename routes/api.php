<?php

use App\Http\Controllers\API\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->name('api.auth.')->group(function () {
    Route::middleware(['throttle:auth'])->group(function () {
        Route::post('/login', [AuthController::class, 'login'])->name('login');
        Route::post('/register', [AuthController::class, 'register'])->name('register');
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');
        Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('reset-password');
        Route::post('/resend-verification', [AuthController::class, 'resendVerification'])->name('resend-verification');
    });

    Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::post('/verify-email', [AuthController::class, 'verifyEmail'])->name('verify-email');
        Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify-otp');
        Route::post('/two-factor/enable', [AuthController::class, 'enableTwoFactor'])->name('two-factor.enable');
        Route::post('/two-factor/confirm', [AuthController::class, 'confirmTwoFactor'])->name('two-factor.confirm');
        Route::post('/two-factor/disable', [AuthController::class, 'disableTwoFactor'])->name('two-factor.disable');
        Route::get('/two-factor/recovery-codes', [AuthController::class, 'recoveryCodes'])->name('two-factor.recovery-codes');
    });
});

Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::get('/user', [AuthController::class, 'user'])->name('api.user');
});

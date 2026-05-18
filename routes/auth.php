<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\MagicLinkController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\SocialLoginController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\MagicLinkRequest;
use App\Livewire\Auth\OtpLogin;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ResetPassword;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'forgotPassword'])->name('password.email');
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.store');
    Route::get('/login/magic', MagicLinkRequest::class)->name('login.magic');
    Route::get('/login/otp', OtpLogin::class)->name('login.otp');
    Route::get('/login/magic/{token}', [MagicLinkController::class, 'verify'])->name('login.magic.verify');

    Route::prefix('auth')->name('auth.social.')->group(function () {
        Route::get('/{provider}/redirect', [SocialLoginController::class, 'redirect'])->name('redirect');
        Route::get('/{provider}/callback', [SocialLoginController::class, 'callback'])->name('callback');
    });
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware('signed')->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
        ->middleware('throttle:6,1')->name('verification.send');

    Route::get('/two-factor-challenge', [TwoFactorController::class, 'create'])->name('two-factor.login');
    Route::post('/two-factor-challenge', [TwoFactorController::class, 'store']);

    Route::post('/user/two-factor/enable', [TwoFactorController::class, 'enable'])->name('two-factor.enable');
    Route::post('/user/two-factor/confirm', [TwoFactorController::class, 'confirm'])->name('two-factor.confirm');
    Route::post('/user/two-factor/disable', [TwoFactorController::class, 'disable'])->name('two-factor.disable');
    Route::post('/user/two-factor/recovery-codes', [TwoFactorController::class, 'recoveryCodes'])->name('two-factor.recovery-codes');
});

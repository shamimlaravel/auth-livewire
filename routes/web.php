<?php

use App\Livewire\Admin\AdminAuthConfig;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\RoleManager;
use App\Livewire\Admin\UserManager;
use App\Livewire\Seller\Dashboard as SellerDashboard;
use App\Livewire\User\AccountSettings;
use App\Livewire\User\Dashboard;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/settings', AccountSettings::class)->name('settings');

    Route::middleware(['role:seller|reseller'])->prefix('seller')->name('seller.')->group(function () {
        Route::get('/', SellerDashboard::class)->name('dashboard');
    });

    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', AdminDashboard::class)->name('dashboard');
        Route::get('/users', UserManager::class)->name('users');
        Route::get('/roles', RoleManager::class)->name('roles');
        Route::get('/auth-config', AdminAuthConfig::class)->name('auth.config');
    });
});

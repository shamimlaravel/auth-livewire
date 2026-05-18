<?php

use App\Livewire\Auth\OtpLogin;
use App\Models\OtpToken;
use App\Models\User;
use App\Services\Auth\OtpService;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

test('otp login page is rendered', function () {
    $response = $this->get(route('login.otp'));

    $response->assertStatus(200);
});

test('otp can be requested for an email', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    Livewire::test(OtpLogin::class)
        ->set('email', $user->email)
        ->call('sendOtp')
        ->assertSet('showOtpInput', true)
        ->assertSet('status', __('auth.otp_sent'));
});

test('invalid otp returns error', function () {
    $user = User::factory()->create(['email' => 'user@example.com']);
    $user->assignRole('user');

    Livewire::test(OtpLogin::class)
        ->set('email', 'user@example.com')
        ->call('sendOtp')
        ->set('code', '000000')
        ->call('verifyOtp')
        ->assertHasErrors(['code']);
});

test('otp service verifies valid code and returns user', function () {
    $validCode = '123456';

    $user = User::factory()->create(['email' => 'user@example.com']);
    $user->assignRole('user');

    OtpToken::create([
        'email' => 'user@example.com',
        'token' => hash('sha256', $validCode),
        'expires_at' => now()->addMinutes(10),
    ]);

    $result = app(OtpService::class)->verify('user@example.com', $validCode);

    expect($result)->not->toBeNull();
    expect($result->email)->toBe('user@example.com');
});

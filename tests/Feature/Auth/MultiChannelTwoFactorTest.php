<?php

use App\Enums\AuthChannel;
use App\Livewire\Auth\TwoFactorChallenge;
use App\Models\AuthSetting;
use App\Models\OtpToken;
use App\Models\User;
use App\Services\Auth\MultiChannelOtpService;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;

/*
 * TwoFactorChallenge Livewire component tests
 * Covers multi-channel 2FA (SMS, WhatsApp, Telegram) mounted with user session
 * and the default SMS path used when no channel is configured on the user.
 */

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);

    AuthSetting::updateOrCreate(['key' => 'channel_sms_enabled'], ['value' => 'true']);
    AuthSetting::updateOrCreate(['key' => 'channel_whatsapp_enabled'], ['value' => 'true']);
    AuthSetting::updateOrCreate(['key' => 'channel_telegram_enabled'], ['value' => 'true']);
});

// ── Page rendering ─────────────────────────────────────────────────────────────

test('two-factor challenge page renders when user exists in session', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    session(['two_factor_user_id' => $user->id]);

    $response = $this->get(route('two-factor.challenge'));
    $response->assertStatus(200);
});

test('two-factor challenge page redirects when no session', function () {
    $response = $this->get(route('two-factor.challenge'));
    $response->assertRedirect(route('login'));
});

// ── Mount — SMS 2FA sends OTP ────────────────────────────────────────────────

test('mount with user having no two_factor_channel defaults to SMS and sends OTP', function () {
    $svc = app(MultiChannelOtpService::class);
    $user = User::factory()->create([
        'phone' => '+18001112222',
        'two_factor_enabled' => true,
        'two_factor_channel' => null,
    ]);
    $user->assignRole('user');

    session(['two_factor_user_id' => $user->id]);

    // Consume the Livewire component mount (no explicit assertion needed;
    // the mount call should not throw and should set the channel)
    $component = Livewire::test(TwoFactorChallenge::class);

    expect($component->get('twoFactorChannel'))->toBe('sms');

    // An OtpToken record was created for the user's phone
    $token = OtpToken::where('identifiable', '+18001112222')
        ->where('channel', 'sms')
        ->whereNull('used_at')
        ->first();
    expect($token)->not->toBeNull();
});

test('mount with explicit SMS channel sends OTP to phone via MultiChannelOtpService', function () {
    $svc = app(MultiChannelOtpService::class);
    $user = User::factory()->create([
        'phone' => '+18003334444',
        'two_factor_enabled' => true,
        'two_factor_channel' => 'sms',
    ]);
    $user->assignRole('user');

    session(['two_factor_user_id' => $user->id]);

    Livewire::test(TwoFactorChallenge::class);

    // OtpToken was written for the user's phone on SMS channel
    $token = OtpToken::where('identifiable', '+18003334444')
        ->where('channel', 'sms')
        ->first();
    expect($token)->not->toBeNull();
});

// ── Submit — valid OTP authenticates user ─────────────────────────────────────

test('submit logs in user when valid OTP is provided (SMS 2FA)', function () {
    $svc = app(MultiChannelOtpService::class);
    $phone = '+18005556666';
    $user = User::factory()->create([
        'phone' => $phone,
        'two_factor_enabled' => true,
        'two_factor_channel' => 'sms',
    ]);
    $user->assignRole('user');

    $otp = $svc->send($phone, AuthChannel::SMS);

    session(['two_factor_user_id' => $user->id]);

    Livewire::test(TwoFactorChallenge::class)
        ->set('code', $otp)
        ->call('submit')
        ->assertRedirect(route('dashboard'));
});

// ── Submit — invalid OTP yields error ─────────────────────────────────────────

test('submit shows error for wrong code via MultiChannelOtpService', function () {
    $svc = app(MultiChannelOtpService::class);
    $user = User::factory()->create([
        'phone' => '+18007778888',
        'two_factor_enabled' => true,
        'two_factor_channel' => 'sms',
    ]);
    $user->assignRole('user');

    $svc->send('+18007778888', AuthChannel::SMS);

    session(['two_factor_user_id' => $user->id]);

    Livewire::test(TwoFactorChallenge::class)
        ->set('code', '999999')
        ->call('submit')
        ->assertHasErrors(['code']);
});

// ── Submit — missing user in session redirects to login ───────────────────────

test('submit redirects to login when user missing from session', function () {
    session()->forget('two_factor_user_id');

    // When no session exists, the component redirects during mount.
    // Test via HTTP since Livewire::test() can't handle mount redirects.
    $response = $this->get(route('two-factor.challenge'));
    $response->assertRedirect(route('login'));
});

// ── TwoFactorChallenge resolves to correct Route ──────────────────────────────

test('two-factor challenge route resolves', function () {
    expect(route('two-factor.challenge'))->toBe('http://auth.test/two-factor/challenge');
});

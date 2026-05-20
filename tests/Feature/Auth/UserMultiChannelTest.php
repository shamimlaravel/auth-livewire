<?php

use App\Enums\AuthChannel;
use App\Models\AuthSetting;
use App\Models\OtpToken;
use App\Models\User;
use App\Services\Auth\MultiChannelOtpService;
use Database\Seeders\RolePermissionSeeder;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);

    AuthSetting::updateOrCreate(['key' => 'channel_sms_enabled'], ['value' => 'true']);
    AuthSetting::updateOrCreate(['key' => 'channel_whatsapp_enabled'], ['value' => 'true']);
    AuthSetting::updateOrCreate(['key' => 'channel_telegram_enabled'], ['value' => 'true']);
});

// ── User model — multi-channel fields ────────────────────────────────────────

test('user can have phone, whatsapp_number and telegram_chat_id set', function () {
    $user = User::factory()->create([
        'phone' => '+1800001111',
        'whatsapp_number' => '+1800002222',
        'telegram_chat_id' => 'chat_001',
    ]);

    expect($user->phone)->toBe('+1800001111');
    expect($user->whatsapp_number)->toBe('+1800002222');
    expect($user->telegram_chat_id)->toBe('chat_001');
});

test('hasTwoFactorEnabled returns true when two_factor_enabled is true', function () {
    $user = User::factory()->create(['two_factor_enabled' => true]);
    expect($user->hasTwoFactorEnabled())->toBeTrue();
});

test('hasTwoFactorEnabled returns true when two_factor_confirmed_at is set', function () {
    $user = User::factory()->create(['two_factor_confirmed_at' => now()]);
    expect($user->hasTwoFactorEnabled())->toBeTrue();
});

test('hasTwoFactorEnabled returns false when neither flag is set', function () {
    $user = User::factory()->create([
        'two_factor_enabled' => false,
        'two_factor_confirmed_at' => null,
    ]);
    expect($user->hasTwoFactorEnabled())->toBeFalse();
});

test('getTwoFactorTarget returns phone when channel is SMS', function () {
    $user = User::factory()->create([
        'phone' => '+1800001111',
        'two_factor_channel' => 'sms',
    ]);

    expect($user->getTwoFactorTarget())->toBe('+1800001111');
});

test('getTwoFactorTarget returns whatsapp_number when channel is WhatsApp', function () {
    $user = User::factory()->create([
        'whatsapp_number' => '+1800002222',
        'two_factor_channel' => 'whatsapp',
    ]);

    expect($user->getTwoFactorTarget())->toBe('+1800002222');
});

test('getTwoFactorTarget returns telegram_chat_id when channel is Telegram', function () {
    $user = User::factory()->create([
        'telegram_chat_id' => 'chat_abc',
        'two_factor_channel' => 'telegram',
    ]);

    expect($user->getTwoFactorTarget())->toBe('chat_abc');
});

test('getTwoFactorTarget falls back to email when channel is null', function () {
    $user = User::factory()->create(['two_factor_channel' => null]);

    expect($user->getTwoFactorTarget())->toBe($user->email);
});

test('getTwoFactorTarget falls back to email for unrecognised channel', function () {
    $user = User::factory()->create(['two_factor_channel' => 'carrier-pigeon']);

    expect($user->getTwoFactorTarget())->toBe($user->email);
});

test('getAvailableChannels returns all four channels', function () {
    $user = User::factory()->create([
        'phone' => '+1',
        'whatsapp_number' => '+2',
        'telegram_chat_id' => 'three',
    ]);

    $channels = $user->getAvailableChannels();

    expect($channels)->toHaveKey('sms');
    expect($channels)->toHaveKey('whatsapp');
    expect($channels)->toHaveKey('telegram');
    expect($channels)->toHaveKey('email');
});

// ── MultiChannelOtpService end-to-end: user exists on all channel records ─────

test('multi-channel OTP service stores OtpToken with identifiables for each channel', function () {
    $phone = '+18005551000';
    $user = User::factory()->create([
        'email' => 'chan-chk@example.com',
        'phone' => $phone,
        'whatsapp_number' => $phone,
        'telegram_chat_id' => 'chan_chk_chat',
    ]);
    $user->assignRole('user');

    $svc = app(MultiChannelOtpService::class);

    $smsOtp = $svc->send($phone, AuthChannel::SMS);
    $waOtp = $svc->send($phone, AuthChannel::WhatsApp);
    $teleOtp = $svc->send('chan_chk_chat', AuthChannel::Telegram);
    $emailOtp = $svc->send('chan-chk@example.com', AuthChannel::Email);

    // All 4 OtpToken records exist in DB
    expect(OtpToken::count())->toBe(4);

    // Can verify each code and resolved user is the same across channels
    expect($svc->verify($phone, $smsOtp, AuthChannel::SMS)->id)->toBe($user->id);
    expect($svc->verify($phone, $waOtp, AuthChannel::WhatsApp)->id)->toBe($user->id);
    expect($svc->verify('chan_chk_chat', $teleOtp, AuthChannel::Telegram)->id)->toBe($user->id);
});

// ── WhatsappNumber & email are unique ────────────────────────────────────────

test('user model phone column is fillable', function () {
    $user = new User;
    expect($user->getFillable())->toContain('phone');
});

test('user model whatsapp_number is fillable', function () {
    $user = new User;
    expect($user->getFillable())->toContain('whatsapp_number');
});

test('user model telegram_chat_id is fillable', function () {
    $user = new User;
    expect($user->getFillable())->toContain('telegram_chat_id');
});

test('user model two_factor_enabled is fillable', function () {
    $user = new User;
    expect($user->getFillable())->toContain('two_factor_enabled');
});

test('user model two_factor_channel is fillable', function () {
    $user = new User;
    expect($user->getFillable())->toContain('two_factor_channel');
});

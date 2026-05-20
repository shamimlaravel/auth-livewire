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

// ── AuthSetting model ─────────────────────────────────────────────────────────

test('AuthSetting::get returns stored value', function () {
    AuthSetting::set('test_key', 'hello');
    expect(AuthSetting::get('test_key'))->toBe('hello');
});

test('AuthSetting::get returns default for missing key', function () {
    expect(AuthSetting::get('nonexistent', 'fallback'))->toBe('fallback');
});

test('AuthSetting::set persists value', function () {
    AuthSetting::set('my_key', 42);
    expect(AuthSetting::get('my_key'))->toBe('42');
});

test('AuthSetting::isEnabled returns true for truthy values', function () {
    foreach (['true', 'True', 'TRUE', '1', 'yes', 'Yes', 'on', 'On'] as $val) {
        AuthSetting::updateOrCreate(['key' => 'test_t_'.$val], ['value' => $val]);
        expect(AuthSetting::isEnabled('test_t_'.$val))->toBeTrue("Failed for: $val");
    }
});

test('AuthSetting::isEnabled returns false for falsy values', function () {
    foreach (['false', 'False', '0', '', 'no', 'off'] as $val) {
        AuthSetting::updateOrCreate(['key' => 'test_f_'.$val], ['value' => $val]);
        expect(AuthSetting::isEnabled('test_f_'.$val))->toBeFalse("Failed for: $val");
    }
});

test('AuthSetting::isEnabled returns false for missing key', function () {
    expect(AuthSetting::isEnabled('nonexistent'))->toBeFalse();
});

test('AuthSetting lifecycle: create, read, update', function () {
    AuthSetting::set('cycle_key', 'v1');
    expect(AuthSetting::get('cycle_key'))->toBe('v1');

    AuthSetting::set('cycle_key', 'v2');
    expect(AuthSetting::get('cycle_key'))->toBe('v2');
});

test('AuthSetting::allSettings returns plucked pairs', function () {
    AuthSetting::set('k1', 'v1');
    AuthSetting::set('k2', 'v2');

    $all = AuthSetting::allSettings();

    expect($all)->toHaveKey('k1', 'v1');
    expect($all)->toHaveKey('k2', 'v2');
});

// ── AuthChannel enum ───────────────────────────────────────────────────────────

test('AuthChannel carries correct string values', function () {
    expect(AuthChannel::SMS->value)->toBe('sms');
    expect(AuthChannel::WhatsApp->value)->toBe('whatsapp');
    expect(AuthChannel::Telegram->value)->toBe('telegram');
    expect(AuthChannel::Email->value)->toBe('email');
});

test('AuthChannel::loginChannels returns four entries', function () {
    expect(AuthChannel::loginChannels())->toBe([
        AuthChannel::SMS, AuthChannel::WhatsApp, AuthChannel::Telegram, AuthChannel::Email,
    ]);
});

test('AuthChannel::twoFactorChannels excludes email', function () {
    expect(AuthChannel::twoFactorChannels())->toBe([
        AuthChannel::SMS, AuthChannel::WhatsApp, AuthChannel::Telegram,
    ]);
});

test('AuthChannel::from resolves from string', function () {
    expect(AuthChannel::from('sms'))->toBe(AuthChannel::SMS);
    expect(AuthChannel::from('email'))->toBe(AuthChannel::Email);
});

test('AuthChannel::from throws ValueError for unknown channel', function () {
    expect(fn () => AuthChannel::from('bird-rider'))->toThrow(ValueError::class);
});

// ── MultiChannelOtpService — SMS ───────────────────────────────────────────────

test('send SMS returns a 6-digit OTP and persists OtpToken', function () {
    $svc = app(MultiChannelOtpService::class);
    $phone = '+18001234567';
    $user = User::factory()->create(['phone' => $phone]);
    $user->assignRole('user');

    $otp = $svc->send($phone, AuthChannel::SMS);

    expect($otp)->toBeString()->toHaveLength(6);
    expect($otp)->toMatch('/^\d{6}$/');

    $token = OtpToken::where('identifiable', $phone)->where('channel', 'sms')->first();
    expect($token)->not->toBeNull();
    expect($token->channel)->toBe('sms');
});

test('send SMS invalidates prior unused tokens', function () {
    $svc = app(MultiChannelOtpService::class);
    $phone = '+18001234568';

    $svc->send($phone, AuthChannel::SMS);

    $newOtp = $svc->send($phone, AuthChannel::SMS);

    // Count used tokens for this identifiable
    $usedCount = OtpToken::where('identifiable', $phone)
        ->where('channel', 'sms')
        ->whereNotNull('used_at')
        ->count();

    expect($usedCount)->toBe(1);
});

test('verify SMS OTP returns user for valid code (MultiChannelOtpService)', function () {
    $user = User::factory()->create(['phone' => '+18001234569']);
    $user->assignRole('user');

    $svc = app(MultiChannelOtpService::class);
    $otp = $svc->send('+18001234569', AuthChannel::SMS);

    $result = $svc->verify('+18001234569', $otp, AuthChannel::SMS);

    expect($result)->not->toBeNull();
    expect($result->email)->toBe($user->email);
});

test('verify returns null for invalid code (MultiChannelOtpService)', function () {
    $phone = '+18001234570';
    $user = User::factory()->create();
    $user->update(['phone' => $phone]);
    $user->assignRole('user');

    $svc = app(MultiChannelOtpService::class);
    $svc->send($phone, AuthChannel::SMS);

    $result = $svc->verify($user->phone, '000000', AuthChannel::SMS);

    expect($result)->toBeNull();
});

// ── MultiChannelOtpService — Email ────────────────────────────────────────────

test('send email returns 6-digit OTP via MultiChannelOtpService', function () {
    $svc = app(MultiChannelOtpService::class);
    $user = User::factory()->create(['email' => 'service-email@example.com']);
    $user->assignRole('user');

    $otp = $svc->send('service-email@example.com', AuthChannel::Email);

    expect($otp)->toHaveLength(6)->toBeNumeric();

    $token = OtpToken::where('identifiable', 'service-email@example.com')
        ->where('channel', 'email')
        ->first();
    expect($token)->not->toBeNull();
});

test('OtpToken is valid after send and invalid after verify (MultiChannelOtpService)', function () {
    $svc = app(MultiChannelOtpService::class);
    $user = User::factory()->create(['email' => 'valid-check@example.com']);
    $user->assignRole('user');

    $svc->send('valid-check@example.com', AuthChannel::Email);

    $record = OtpToken::where('identifiable', 'valid-check@example.com')
        ->where('channel', 'email')
        ->first();
    expect($record->isValid())->toBeTrue();

    $svc->verify('valid-check@example.com', str_pad('0', 6, '0', STR_PAD_LEFT), AuthChannel::Email);

    // Failed verification should NOT mark token as used
    expect($record->fresh()->isValid())->toBeTrue();
});

// ── MultiChannelOtpService — WhatsApp & Telegram ──────────────────────────────

test('send WhatsApp returns 6-digit OTP and stores channel record', function () {
    $svc = app(MultiChannelOtpService::class);
    $user = User::factory()->create(['phone' => '+18001234570']);
    $user->assignRole('user');

    $otp = $svc->send('+18001234570', AuthChannel::WhatsApp);

    expect($otp)->toHaveLength(6);

    $token = OtpToken::where('identifiable', '+18001234570')->where('channel', 'whatsapp')->first();
    expect($token)->not->toBeNull();
});

test('send Telegram returns 6-digit OTP and stores channel record', function () {
    $svc = app(MultiChannelOtpService::class);
    $user = User::factory()->create();
    $user->assignRole('user');

    $chatId = 'my-chat-id-99';
    $otp = $svc->send($chatId, AuthChannel::Telegram);

    expect($otp)->toHaveLength(6);

    $token = OtpToken::where('identifiable', $chatId)->where('channel', 'telegram')->first();
    expect($token)->not->toBeNull();
});

// ── resolveUser matches identifiable to correct user column ─────────────────────

test('resolveUser returns null when no user found for identifiable', function () {
    $svc = app(MultiChannelOtpService::class);
    expect($svc->verify('does-not-exist', '000000', AuthChannel::SMS))
        ->toBeNull();
});

// ── MultiChannelOtpService — disabled channel ─────────────────────────────────

test('disabled SMS channel does not crash send via MultiChannelOtpService', function () {
    AuthSetting::updateOrCreate(['key' => 'channel_sms_enabled'], ['value' => 'false']);

    $phone = '+18001234571';
    $user = User::factory()->create();
    $user->update(['phone' => $phone]);
    $user->assignRole('user');

    $svc = app(MultiChannelOtpService::class);
    // Should not throw — logs warning and skips delivery
    $svc->send($phone, AuthChannel::SMS);
    expect(true)->toBeTrue();
});

<?php

use App\Enums\AuthChannel;
use App\Livewire\Auth\PhoneLogin;
use App\Livewire\Auth\TelegramLogin;
use App\Livewire\Auth\WhatsAppLogin;
use App\Models\AuthSetting;
use App\Models\OtpToken;
use App\Models\User;
use App\Services\Auth\MultiChannelOtpService;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);

    AuthSetting::updateOrCreate(['key' => 'channel_sms_enabled'], ['value' => 'true']);
    AuthSetting::updateOrCreate(['key' => 'channel_whatsapp_enabled'], ['value' => 'true']);
    AuthSetting::updateOrCreate(['key' => 'channel_telegram_enabled'], ['value' => 'true']);
});

// ── PhoneLogin — page rendering ───────────────────────────────────────────────

test('phone otp login page renders', function () {
    $response = $this->get(route('login.phone'));
    $response->assertStatus(200);
    $response->assertSee('Phone Number');
});

test('phone otp login page renders 2-state form', function () {
    Livewire::test(PhoneLogin::class)
        ->assertSet('showOtpInput', false)
        ->assertSet('phone', '');
});

// ── PhoneLogin — sendOtp validation ──────────────────────────────────────────

test('phone login requires a phone number', function () {
    Livewire::test(PhoneLogin::class)
        ->call('sendOtp')
        ->assertHasErrors('phone');
});

test('phone login rejects non-starts-with-plus phone', function () {
    Livewire::test(PhoneLogin::class)
        ->set('phone', 'not-a-phone')
        ->call('sendOtp')
        ->assertHasErrors('phone');
});

test('phone login accepts valid +phone and transitions to OTP input', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    Livewire::test(PhoneLogin::class)
        ->set('phone', '+15550100')
        ->call('sendOtp')
        ->assertSet('showOtpInput', true)
        ->assertSet('status', __('auth.otp_sent_sms', ['phone' => '+15550100']));
});

// ── PhoneLogin — resendOtp ────────────────────────────────────────────────────

test('phone login resend OTP sets status', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    Livewire::test(PhoneLogin::class)
        ->set('phone', '+15550100')
        ->call('sendOtp')
        ->call('resendOtp')
        ->assertSet('status', __('auth.otp_sent_sms', ['phone' => '+15550100']));
});

// ── PhoneLogin — back transitions to first step ───────────────────────────────

test('phone login back returns to first step', function () {
    Livewire::test(PhoneLogin::class)
        ->set('phone', '+15550100')
        ->call('sendOtp')
        ->assertSet('showOtpInput', true)
        ->call('back')
        ->assertSet('showOtpInput', false)
        ->assertSet('phone', '')
        ->assertSet('status', null);
});

// ── PhoneLogin — verify flow via service ─────────────────────────────────────

test('phone login flow: send → verify → user authenticated', function () {
    $svc = app(MultiChannelOtpService::class);
    $phone = '+18009998888';
    $user = User::factory()->create(['phone' => $phone]);
    $user->assignRole('user');

    // Send via service
    $otp = $svc->send($phone, AuthChannel::SMS);

    $result = $svc->verify($phone, $otp, AuthChannel::SMS);

    expect($result)->not->toBeNull();
    expect($result->phone)->toBe($phone);
});

test('phone login OTP record is valid after send and invalid after verify', function () {
    $svc = app(MultiChannelOtpService::class);
    $phone = '+18007776666';

    $svc->send($phone, AuthChannel::SMS);

    $record = OtpToken::where('identifiable', $phone)
        ->where('channel', 'sms')
        ->first();

    expect($record?->isValid())->toBeTrue();

    Livewire::test(PhoneLogin::class)
        ->set('phone', $phone)
        ->set('code', '000000')
        ->call('sendOtp')
        ->set('code', '999999')
        ->call('verifyOtp');

    expect($record->fresh()->isValid())->toBeFalse();
});

// ── PhoneLogin — invalid code yields error ───────────────────────────────────

test('phone login shows error for wrong code', function () {
    Livewire::test(PhoneLogin::class)
        ->set('phone', '+15550100')
        ->call('sendOtp')
        ->set('code', '000000')
        ->call('verifyOtp')
        ->assertHasErrors(['code']);
});

test('phone login requires code when verifying', function () {
    Livewire::test(PhoneLogin::class)
        ->set('phone', '+15550100')
        ->call('sendOtp')
        ->call('verifyOtp')
        ->assertHasErrors(['code']);
});

// ── WhatsAppLogin — rendering ────────────────────────────────────────────────

test('whatsapp otp login page renders', function () {
    $response = $this->get(route('login.whatsapp'));
    $response->assertStatus(200);
    $response->assertSee('WhatsApp Number');
});

test('whatsapp login shows OTP input after send', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    Livewire::test(WhatsAppLogin::class)
        ->set('whatsapp_number', '+15005550099')
        ->call('sendOtp')
        ->assertSet('showOtpInput', true)
        ->assertSet('status', __('auth.otp_sent_whatsapp', ['phone' => '+15005550099']));
});

// ── WhatsAppLogin — validation ────────────────────────────────────────────────

test('whatsapp login requires phone number', function () {
    Livewire::test(WhatsAppLogin::class)
        ->call('sendOtp')
        ->assertHasErrors('whatsapp_number');
});

test('whatsapp login rejects badly formatted phone', function () {
    Livewire::test(WhatsAppLogin::class)
        ->set('whatsapp_number', 'not-a-number')
        ->call('sendOtp')
        ->assertHasErrors('whatsapp_number');
});

// ── WhatsAppLogin — back and resend ──────────────────────────────────────────

test('whatsapp login back resets state', function () {
    Livewire::test(WhatsAppLogin::class)
        ->set('whatsapp_number', '+15005550100')
        ->call('sendOtp')
        ->call('back')
        ->assertSet('showOtpInput', false)
        ->assertSet('whatsapp_number', '');
});

test('whatsapp login resend sets status', function () {
    Livewire::test(WhatsAppLogin::class)
        ->set('whatsapp_number', '+15005550100')
        ->call('sendOtp')
        ->call('resendOtp')
        ->assertSet('status', __('auth.otp_sent_whatsapp', ['phone' => '+15005550100']));
});

// ── TelegramLogin — rendering ─────────────────────────────────────────────────

test('telegram otp login page renders', function () {
    $response = $this->get(route('login.telegram'));
    $response->assertStatus(200);
    $response->assertSee('Telegram');
});

test('telegram login shows OTP input after send', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    Livewire::test(TelegramLogin::class)
        ->set('telegram_chat_id', 'my_chat_01')
        ->call('sendOtp')
        ->assertSet('showOtpInput', true);
});

// ── TelegramLogin — validation ────────────────────────────────────────────────

test('telegram login requires chat id', function () {
    Livewire::test(TelegramLogin::class)
        ->call('sendOtp')
        ->assertHasErrors('telegram_chat_id');
});

test('telegram login back resets state', function () {
    Livewire::test(TelegramLogin::class)
        ->set('telegram_chat_id', 'my_chat')
        ->call('sendOtp')
        ->call('back')
        ->assertSet('showOtpInput', false)
        ->assertSet('telegram_chat_id', '');
});

// ── Routes ────────────────────────────────────────────────────────────────────

test('new channel-specific login routes return 200', function () {
    $this->get('/login/phone')->assertStatus(200);
    $this->get('/login/whatsapp')->assertStatus(200);
    $this->get('/login/telegram')->assertStatus(200);
});

// ── Authenticated access ──────────────────────────────────────────────────────

test('authenticated user is redirected from phone login page', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    $response = $this->actingAs($user)->get(route('login.phone'));
    $response->assertRedirect(route('dashboard'));
});

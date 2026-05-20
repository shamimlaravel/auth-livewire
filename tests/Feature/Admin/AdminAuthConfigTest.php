<?php

use App\Livewire\Admin\AdminAuthConfig;
use App\Models\AuthSetting;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;

/*
 * AdminAuthConfig Livewire component tests
 * Covers page rendering, channel toggles, reset, 2FA, OTP login and channel persistence.
 */

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);

    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');

    // Clear auth_settings before each test so it starts clean
    AuthSetting::query()->delete();
});

// ── Page rendering ────────────────────────────────────────────────────────────

test('admin can view the auth config page', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.auth.config'));
    $response->assertStatus(200);
});

test('non-admin is forbidden from view the auth config page', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    $response = $this->actingAs($user)->get(route('admin.auth.config'));
    $response->assertStatus(403);
});

// ── Toggle channel providers ──────────────────────────────────────────────────

test('enabling sms channel persists to auth_settings table', function () {
    Livewire::actingAs($this->admin)
        ->test(AdminAuthConfig::class)
        ->set('channelToggles.sms', true)
        ->call('save');

    expect(AuthSetting::get('channel_sms_enabled'))->toBe('true');
});

test('disabling sms channel persists to auth_settings table', function () {
    AuthSetting::set('channel_sms_enabled', 'true');

    Livewire::actingAs($this->admin)
        ->test(AdminAuthConfig::class)
        ->set('channelToggles.sms', false)
        ->call('save');

    expect(AuthSetting::get('channel_sms_enabled'))->toBe('false');
});

test('enabling whatsapp and telegram channels persists', function () {
    Livewire::actingAs($this->admin)
        ->test(AdminAuthConfig::class)
        ->set('channelToggles.whatsapp', true)
        ->set('channelToggles.telegram', true)
        ->call('save');

    expect(AuthSetting::isEnabled('channel_whatsapp_enabled'))->toBeTrue();
    expect(AuthSetting::isEnabled('channel_telegram_enabled'))->toBeTrue();
});

// ── 2FA settings ─────────────────────────────────────────────────────────────

test('admin can set default 2FA channel', function () {
    Livewire::actingAs($this->admin)
        ->test(AdminAuthConfig::class)
        ->set('twoFactorDefaultChannel', 'telegram')
        ->call('save');

    expect(AuthSetting::get('two_factor_default_channel'))->toBe('telegram');
});

test('admin can toggle force-2FA-for-all-users', function () {
    Livewire::actingAs($this->admin)
        ->test(AdminAuthConfig::class)
        ->set('twoFactorForceAllUsers', true)
        ->call('save');

    expect(AuthSetting::get('two_factor_force_all_users'))->toBe('true');
});

// ── OTP login channel toggles ─────────────────────────────────────────────────

test('admin can enable phone OTP login', function () {
    Livewire::actingAs($this->admin)
        ->test(AdminAuthConfig::class)
        ->set('otpLoginPhoneEnabled', true)
        ->call('save');

    expect(AuthSetting::get('otp_login_phone_enabled'))->toBe('true');
});

test('admin can disable phone OTP login', function () {
    Livewire::actingAs($this->admin)
        ->test(AdminAuthConfig::class)
        ->set('otpLoginPhoneEnabled', false)
        ->call('save');

    expect(AuthSetting::get('otp_login_phone_enabled'))->toBe('false');
});

test('admin can enable WhatsApp OTP login', function () {
    Livewire::actingAs($this->admin)
        ->test(AdminAuthConfig::class)
        ->set('otpLoginWhatsAppEnabled', true)
        ->call('save');

    expect(AuthSetting::isEnabled('otp_login_whatsapp_enabled'))->toBeTrue();
});

test('admin can enable Telegram OTP login', function () {
    Livewire::actingAs($this->admin)
        ->test(AdminAuthConfig::class)
        ->set('otpLoginTelegramEnabled', true)
        ->call('save');

    expect(AuthSetting::isEnabled('otp_login_telegram_enabled'))->toBeTrue();
});

// ── Rate-limit settings ───────────────────────────────────────────────────────

test('admin can configure rate-limit settings', function () {
    Livewire::actingAs($this->admin)
        ->test(AdminAuthConfig::class)
        ->set('otpRateLimitPerIp', 20)
        ->set('otpRateLimitWindow', 120)
        ->set('otpCooldownSeconds', 90)
        ->call('save');

    expect(AuthSetting::get('otp_rate_limit_per_ip'))->toBe('20');
    expect(AuthSetting::get('otp_rate_limit_window_minutes'))->toBe('120');
    expect(AuthSetting::get('otp_cooldown_seconds'))->toBe('90');
});

// ── SMS gateway credentials ───────────────────────────────────────────────────

test('admin can store SMS gateway credentials', function () {
    Livewire::actingAs($this->admin)
        ->test(AdminAuthConfig::class)
        ->set('smsGateway', 'twilio')
        ->set('smsUsername', 'my_user')
        ->set('smsPassword', 'my_secret')
        ->set('smsSid', 'AC12345')
        ->call('save');

    expect(AuthSetting::get('sms_gateway'))->toBe('twilio');
    expect(AuthSetting::get('sms_username'))->toBe('my_user');
    expect(AuthSetting::get('sms_sid'))->toBe('AC12345');
});

// ── WhatsApp credentials ──────────────────────────────────────────────────────

test('admin can store WhatsApp Cloud API credentials', function () {
    Livewire::actingAs($this->admin)
        ->test(AdminAuthConfig::class)
        ->set('whatsappApiUrl', 'https://graph.facebook.com/v18.0')
        ->set('whatsappPhoneId', '987654321')
        ->set('whatsappAccessToken', 'EAAB_secret_token')
        ->call('save');

    expect(AuthSetting::get('whatsapp_api_url'))->toBe('https://graph.facebook.com/v18.0');
    expect(AuthSetting::get('whatsapp_phone_id'))->toBe('987654321');
});

// ── Telegram credentials ──────────────────────────────────────────────────────

test('admin can store Telegram bot credentials', function () {
    Livewire::actingAs($this->admin)
        ->test(AdminAuthConfig::class)
        ->set('telegramBotToken', '123456789:ABCdefGHIjklMNOpqrSTUvwxyz-1234567890')
        ->set('telegramBotName', '@TestBot')
        ->call('save');

    expect(AuthSetting::get('telegram_bot_token'))->toBe('123456789:ABCdefGHIjklMNOpqrSTUvwxyz-1234567890');
    expect(AuthSetting::get('telegram_bot_name'))->toBe('@TestBot');
});

// ── Reset-to-defaults ─────────────────────────────────────────────────────────

test('reset button restores default values', function () {
    Livewire::actingAs($this->admin)
        ->test(AdminAuthConfig::class)
        ->set('channelToggles.sms', true)
        ->set('channelToggles.whatsapp', true)
        ->set('channelToggles.telegram', true)
        ->set('twoFactorDefaultChannel', 'telegram')
        ->set('otpLoginPhoneEnabled', false)
        ->set('otpRateLimitPerIp', 99)
        ->set('magicLoginEnabled', true)
        ->set('magicLinkExpiration', 60)
        ->set('magicLinkDeviceRestriction', 'mobile')
        ->set('sessionTimeout', 60)
        ->set('loginThrottleAttempts', 10)
        ->call('save');

    Livewire::actingAs($this->admin)
        ->test(AdminAuthConfig::class)
        ->call('resetToDefaults')
        ->assertSet('twoFactorDefaultChannel', 'sms')
        ->assertSet('otpLoginPhoneEnabled', true)
        ->assertSet('otpLoginWhatsAppEnabled', false)
        ->assertSet('otpLoginTelegramEnabled', false)
        ->assertSet('otpRateLimitPerIp', 10)
        ->assertSet('otpRateLimitWindow', 60)
        ->assertSet('smsGateway', 'sslwireless')
        ->assertSet('magicLoginEnabled', false)
        ->assertSet('magicLinkExpiration', 30)
        ->assertSet('magicLinkDeviceRestriction', 'all')
        ->assertSet('sessionTimeout', 120)
        ->assertSet('loginThrottleAttempts', 5);
});

test('mount hydrates properties from auth_settings table', function () {
    AuthSetting::set('two_factor_default_channel', 'whatsapp');
    AuthSetting::set('sms_gateway', 'twilio');
    AuthSetting::set('whatsapp_phone_id', 'phone_id_value');
    AuthSetting::set('magic_link_device_restriction', 'desktop');
    AuthSetting::set('session_timeout_minutes', '90');

    Livewire::actingAs($this->admin)
        ->test(AdminAuthConfig::class)
        ->assertSet('twoFactorDefaultChannel', 'whatsapp')
        ->assertSet('smsGateway', 'twilio')
        ->assertSet('whatsappPhoneId', 'phone_id_value')
        ->assertSet('magicLinkDeviceRestriction', 'desktop')
        ->assertSet('sessionTimeout', 90);
});

// ── Tab switching ─────────────────────────────────────────────────────────────

test('default tab is general', function () {
    Livewire::actingAs($this->admin)
        ->test(AdminAuthConfig::class)
        ->assertSet('activeTab', 'general');
});

test('admin can switch tabs', function () {
    Livewire::actingAs($this->admin)
        ->test(AdminAuthConfig::class)
        ->call('switchTab', 'login-channels')
        ->assertSet('activeTab', 'login-channels')
        ->call('switchTab', '2fa')
        ->assertSet('activeTab', '2fa')
        ->call('switchTab', 'magic-login')
        ->assertSet('activeTab', 'magic-login')
        ->call('switchTab', 'rate-limits')
        ->assertSet('activeTab', 'rate-limits')
        ->call('switchTab', 'social-login')
        ->assertSet('activeTab', 'social-login')
        ->call('switchTab', 'general')
        ->assertSet('activeTab', 'general');
});

// ── Email OTP login toggle ────────────────────────────────────────────────────

test('admin can toggle email OTP login', function () {
    Livewire::actingAs($this->admin)
        ->test(AdminAuthConfig::class)
        ->set('otpLoginEmailEnabled', false)
        ->call('save');

    expect(AuthSetting::get('otp_login_email_enabled'))->toBe('false');
});

// ── Magic link OTP login toggle ───────────────────────────────────────────────

test('admin can toggle magic link OTP login', function () {
    Livewire::actingAs($this->admin)
        ->test(AdminAuthConfig::class)
        ->set('otpLoginMagicLinkEnabled', true)
        ->call('save');

    expect(AuthSetting::get('otp_login_magic_link_enabled'))->toBe('true');
});

// ── Magic login settings ──────────────────────────────────────────────────────

test('admin can configure magic login settings', function () {
    Livewire::actingAs($this->admin)
        ->test(AdminAuthConfig::class)
        ->set('magicLoginEnabled', true)
        ->set('magicLinkExpiration', 60)
        ->set('magicLinkDeviceRestriction', 'mobile')
        ->call('save');

    expect(AuthSetting::isEnabled('magic_login_enabled'))->toBeTrue();
    expect(AuthSetting::get('magic_link_expiration_minutes'))->toBe('60');
    expect(AuthSetting::get('magic_link_device_restriction'))->toBe('mobile');
});

// ── Email OTP expiration ──────────────────────────────────────────────────────

test('admin can configure email OTP expiration', function () {
    Livewire::actingAs($this->admin)
        ->test(AdminAuthConfig::class)
        ->set('emailOtpExpiration', 15)
        ->call('save');

    expect(AuthSetting::get('email_otp_expiration_minutes'))->toBe('15');
});

// ── Session & throttle settings ───────────────────────────────────────────────

test('admin can configure session and throttle settings', function () {
    Livewire::actingAs($this->admin)
        ->test(AdminAuthConfig::class)
        ->set('sessionTimeout', 60)
        ->set('loginThrottleAttempts', 10)
        ->set('loginThrottleDecay', 120)
        ->call('save');

    expect(AuthSetting::get('session_timeout_minutes'))->toBe('60');
    expect(AuthSetting::get('login_throttle_attempts'))->toBe('10');
    expect(AuthSetting::get('login_throttle_decay_seconds'))->toBe('120');
});

// ── SMTP credentials ──────────────────────────────────────────────────────────

test('admin can store SMTP credentials', function () {
    Livewire::actingAs($this->admin)
        ->test(AdminAuthConfig::class)
        ->set('smtpHost', 'smtp.example.com')
        ->set('smtpPort', '587')
        ->set('smtpUsername', 'user@example.com')
        ->set('smtpPassword', 'secret')
        ->set('smtpEncryption', 'tls')
        ->set('smtpFromEmail', 'noreply@example.com')
        ->set('smtpFromName', 'My App')
        ->call('save');

    expect(AuthSetting::get('smtp_host'))->toBe('smtp.example.com');
    expect(AuthSetting::get('smtp_port'))->toBe('587');
    expect(AuthSetting::get('smtp_username'))->toBe('user@example.com');
    expect(AuthSetting::get('smtp_encryption'))->toBe('tls');
    expect(AuthSetting::get('smtp_from_email'))->toBe('noreply@example.com');
    expect(AuthSetting::get('smtp_from_name'))->toBe('My App');
});

// ── Admin page route ──────────────────────────────────────────────────────────

test('admin auth config livewire renders as route', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.auth.config'));
    $response->assertStatus(200);
    $response->assertSee('Authentication Configuration');
});

// ── Social login settings ──────────────────────────────────────────────────────

test('admin can toggle social login master switch', function () {
    Livewire::actingAs($this->admin)
        ->test(AdminAuthConfig::class)
        ->set('socialLoginEnabled', false)
        ->call('save');

    expect(AuthSetting::get('social_login_enabled'))->toBe('false');
});

test('admin can enable a social provider', function () {
    Livewire::actingAs($this->admin)
        ->test(AdminAuthConfig::class)
        ->set('socialProviders.google.enabled', true)
        ->set('socialProviders.google.client_id', 'test-client-id')
        ->set('socialProviders.google.client_secret', 'test-client-secret')
        ->set('socialProviders.google.scopes', 'openid,profile,email')
        ->call('save');

    expect(AuthSetting::isEnabled('social_provider_google_enabled'))->toBeTrue();
    expect(AuthSetting::get('social_provider_google_client_id'))->toBe('test-client-id');
    expect(AuthSetting::get('social_provider_google_scopes'))->toBe('openid,profile,email');
});

test('admin can toggle social login auto-link email', function () {
    Livewire::actingAs($this->admin)
        ->test(AdminAuthConfig::class)
        ->set('socialLoginAutoLinkEmail', false)
        ->call('save');

    expect(AuthSetting::get('social_login_auto_link_email'))->toBe('false');
});

test('admin can toggle require verified email', function () {
    Livewire::actingAs($this->admin)
        ->test(AdminAuthConfig::class)
        ->set('socialLoginRequireVerifiedEmail', true)
        ->call('save');

    expect(AuthSetting::get('social_login_require_verified_email'))->toBe('true');
});

test('reset defaults restores social provider defaults', function () {
    Livewire::actingAs($this->admin)
        ->test(AdminAuthConfig::class)
        ->set('socialLoginEnabled', false)
        ->set('socialLoginAutoLinkEmail', false)
        ->set('socialProviders.google.enabled', true)
        ->call('resetToDefaults')
        ->assertSet('socialLoginEnabled', true)
        ->assertSet('socialLoginAutoLinkEmail', true)
        ->assertSet('socialProviders.google.enabled', false);
});

test('mount hydrates social provider settings from db', function () {
    AuthSetting::set('social_login_enabled', 'false');
    AuthSetting::set('social_provider_github_enabled', 'true');
    AuthSetting::set('social_provider_github_client_id', 'github-id');

    Livewire::actingAs($this->admin)
        ->test(AdminAuthConfig::class)
        ->assertSet('socialLoginEnabled', false)
        ->assertSet('socialProviders.github.enabled', true)
        ->assertSet('socialProviders.github.client_id', 'github-id');
});

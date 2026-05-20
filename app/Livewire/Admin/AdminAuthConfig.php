<?php

namespace App\Livewire\Admin;

use App\Models\AuthSetting;
use Livewire\Component;

class AdminAuthConfig extends Component
{
    // ── Active tab ─────────────────────────────────────────────────────────
    public string $activeTab = 'general';

    // ── Channel toggle keys ─────────────────────────────────────────────────
    public array $channelToggles = [];

    // ── 2FA settings ─────────────────────────────────────────────────────────
    public string $twoFactorDefaultChannel = 'sms';

    public bool $twoFactorForceAllUsers = false;

    // ── OTP login toggles ─────────────────────────────────────────────────────
    public bool $otpLoginPhoneEnabled = true;

    public bool $otpLoginWhatsAppEnabled = false;

    public bool $otpLoginTelegramEnabled = false;

    public bool $otpLoginEmailEnabled = true;

    public bool $otpLoginMagicLinkEnabled = false;

    // ── Magic link settings ───────────────────────────────────────────────────
    public bool $magicLoginEnabled = false;

    public int $magicLinkExpiration = 30;

    public string $magicLinkDeviceRestriction = 'all';

    // ── Email OTP settings ────────────────────────────────────────────────────
    public int $emailOtpExpiration = 10;

    // ── Rate-limit settings ───────────────────────────────────────────────────
    public int $otpRateLimitPerIp = 10;

    public int $otpRateLimitWindow = 60;

    public int $otpCooldownSeconds = 60;

    public int $sessionTimeout = 120;

    public int $loginThrottleAttempts = 5;

    public int $loginThrottleDecay = 60;

    // ── SMS credentials ───────────────────────────────────────────────────────
    public ?string $smsGateway = 'sslwireless';

    public ?string $smsUsername = '';

    public ?string $smsPassword = '';

    public ?string $smsSid = '';

    // ── WhatsApp credentials ───────────────────────────────────────────────────
    public ?string $whatsappApiUrl = '';

    public ?string $whatsappPhoneId = '';

    public ?string $whatsappAccessToken = '';

    // ── Telegram credentials ───────────────────────────────────────────────────
    public ?string $telegramBotToken = '';

    public ?string $telegramBotName = '';

    // ── SMTP credentials ──────────────────────────────────────────────────────
    public ?string $smtpHost = '';

    public ?string $smtpPort = '587';

    public ?string $smtpUsername = '';

    public ?string $smtpPassword = '';

    public ?string $smtpEncryption = 'tls';

    public ?string $smtpFromEmail = '';

    public ?string $smtpFromName = '';

    // ── Social login settings ───────────────────────────────────────────────────
    public bool $socialLoginEnabled = true;

    public bool $socialLoginAutoLinkEmail = true;

    public bool $socialLoginRequireVerifiedEmail = false;

    public array $socialProviders = [
        'google' => ['enabled' => false, 'client_id' => '', 'client_secret' => '', 'scopes' => 'openid,profile,email'],
        'github' => ['enabled' => false, 'client_id' => '', 'client_secret' => '', 'scopes' => 'user:email,read:user'],
        'facebook' => ['enabled' => false, 'client_id' => '', 'client_secret' => '', 'scopes' => 'email,public_profile'],
        'twitter' => ['enabled' => false, 'client_id' => '', 'client_secret' => '', 'scopes' => 'tweet.read,users.read'],
        'linkedin' => ['enabled' => false, 'client_id' => '', 'client_secret' => '', 'scopes' => 'openid,profile,email'],
        'gitlab' => ['enabled' => false, 'client_id' => '', 'client_secret' => '', 'scopes' => 'read_user'],
        'microsoft' => ['enabled' => false, 'client_id' => '', 'client_secret' => '', 'scopes' => 'openid,profile,email,User.Read'],
    ];

    public bool $saving = false;

    public function mount(): void
    {
        $this->loadFromSettings();
    }

    public function switchTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    protected function loadFromSettings(): void
    {
        $all = AuthSetting::allSettings();

        $this->channelToggles = [
            'sms' => AuthSetting::isEnabled('channel_sms_enabled'),
            'whatsapp' => AuthSetting::isEnabled('channel_whatsapp_enabled'),
            'telegram' => AuthSetting::isEnabled('channel_telegram_enabled'),
        ];

        $this->twoFactorDefaultChannel = (string) ($all['two_factor_default_channel'] ?? 'sms');
        $this->twoFactorForceAllUsers = (bool) ($all['two_factor_force_all_users'] ?? false);

        $this->otpLoginPhoneEnabled = (bool) ($all['otp_login_phone_enabled'] ?? true);
        $this->otpLoginWhatsAppEnabled = (bool) ($all['otp_login_whatsapp_enabled'] ?? false);
        $this->otpLoginTelegramEnabled = (bool) ($all['otp_login_telegram_enabled'] ?? false);
        $this->otpLoginEmailEnabled = (bool) ($all['otp_login_email_enabled'] ?? true);
        $this->otpLoginMagicLinkEnabled = (bool) ($all['otp_login_magic_link_enabled'] ?? false);

        $this->magicLoginEnabled = (bool) ($all['magic_login_enabled'] ?? false);
        $this->magicLinkExpiration = (int) ($all['magic_link_expiration_minutes'] ?? 30);
        $this->magicLinkDeviceRestriction = (string) ($all['magic_link_device_restriction'] ?? 'all');

        $this->emailOtpExpiration = (int) ($all['email_otp_expiration_minutes'] ?? 10);

        $this->otpRateLimitPerIp = (int) ($all['otp_rate_limit_per_ip'] ?? 10);
        $this->otpRateLimitWindow = (int) ($all['otp_rate_limit_window_minutes'] ?? 60);
        $this->otpCooldownSeconds = (int) ($all['otp_cooldown_seconds'] ?? 60);
        $this->sessionTimeout = (int) ($all['session_timeout_minutes'] ?? 120);
        $this->loginThrottleAttempts = (int) ($all['login_throttle_attempts'] ?? 5);
        $this->loginThrottleDecay = (int) ($all['login_throttle_decay_seconds'] ?? 60);

        $this->smsGateway = $all['sms_gateway'] ?? config('auth_channels.channels.sms.gateway', 'sslwireless');
        $this->smsUsername = $all['sms_username'] ?? '';
        $this->smsPassword = $all['sms_password'] ?? '';
        $this->smsSid = $all['sms_sid'] ?? '';

        $this->whatsappApiUrl = $all['whatsapp_api_url'] ?? config('auth_channels.channels.whatsapp.api_url', '');
        $this->whatsappPhoneId = $all['whatsapp_phone_id'] ?? config('auth_channels.channels.whatsapp.phone_id', '');
        $this->whatsappAccessToken = $all['whatsapp_access_token'] ?? '';

        $this->telegramBotToken = $all['telegram_bot_token'] ?? '';
        $this->telegramBotName = $all['telegram_bot_name'] ?? config('auth_channels.channels.telegram.bot_name', '');

        $this->smtpHost = $all['smtp_host'] ?? '';
        $this->smtpPort = $all['smtp_port'] ?? '587';
        $this->smtpUsername = $all['smtp_username'] ?? '';
        $this->smtpPassword = $all['smtp_password'] ?? '';
        $this->smtpEncryption = $all['smtp_encryption'] ?? 'tls';
        $this->smtpFromEmail = $all['smtp_from_email'] ?? '';
        $this->smtpFromName = $all['smtp_from_name'] ?? '';

        $this->socialLoginEnabled = AuthSetting::isEnabled('social_login_enabled') || ! isset($all['social_login_enabled']);
        $this->socialLoginAutoLinkEmail = AuthSetting::isEnabled('social_login_auto_link_email') || ! isset($all['social_login_auto_link_email']);
        $this->socialLoginRequireVerifiedEmail = AuthSetting::isEnabled('social_login_require_verified_email');

        foreach ($this->socialProviders as $name => $defaults) {
            $this->socialProviders[$name] = [
                'enabled' => AuthSetting::isEnabled("social_provider_{$name}_enabled"),
                'client_id' => $all["social_provider_{$name}_client_id"] ?? $defaults['client_id'],
                'client_secret' => $all["social_provider_{$name}_client_secret"] ?? $defaults['client_secret'],
                'scopes' => $all["social_provider_{$name}_scopes"] ?? $defaults['scopes'],
            ];
        }
    }

    public function save(): void
    {
        $this->saving = true;

        AuthSetting::set('channel_sms_enabled', $this->channelToggles['sms'] ?? false);
        AuthSetting::set('channel_whatsapp_enabled', $this->channelToggles['whatsapp'] ?? false);
        AuthSetting::set('channel_telegram_enabled', $this->channelToggles['telegram'] ?? false);

        AuthSetting::set('two_factor_default_channel', $this->twoFactorDefaultChannel);
        AuthSetting::set('two_factor_force_all_users', $this->twoFactorForceAllUsers);

        AuthSetting::set('otp_login_phone_enabled', $this->otpLoginPhoneEnabled);
        AuthSetting::set('otp_login_whatsapp_enabled', $this->otpLoginWhatsAppEnabled);
        AuthSetting::set('otp_login_telegram_enabled', $this->otpLoginTelegramEnabled);
        AuthSetting::set('otp_login_email_enabled', $this->otpLoginEmailEnabled);
        AuthSetting::set('otp_login_magic_link_enabled', $this->otpLoginMagicLinkEnabled);

        AuthSetting::set('magic_login_enabled', $this->magicLoginEnabled);
        AuthSetting::set('magic_link_expiration_minutes', $this->magicLinkExpiration);
        AuthSetting::set('magic_link_device_restriction', $this->magicLinkDeviceRestriction);

        AuthSetting::set('email_otp_expiration_minutes', $this->emailOtpExpiration);

        AuthSetting::set('otp_rate_limit_per_ip', $this->otpRateLimitPerIp);
        AuthSetting::set('otp_rate_limit_window_minutes', $this->otpRateLimitWindow);
        AuthSetting::set('otp_cooldown_seconds', $this->otpCooldownSeconds);
        AuthSetting::set('session_timeout_minutes', $this->sessionTimeout);
        AuthSetting::set('login_throttle_attempts', $this->loginThrottleAttempts);
        AuthSetting::set('login_throttle_decay_seconds', $this->loginThrottleDecay);

        AuthSetting::set('sms_gateway', $this->smsGateway);
        AuthSetting::set('sms_username', $this->smsUsername);
        AuthSetting::set('sms_password', $this->smsPassword);
        AuthSetting::set('sms_sid', $this->smsSid);

        AuthSetting::set('whatsapp_api_url', $this->whatsappApiUrl);
        AuthSetting::set('whatsapp_phone_id', $this->whatsappPhoneId);
        AuthSetting::set('whatsapp_access_token', $this->whatsappAccessToken);

        AuthSetting::set('telegram_bot_token', $this->telegramBotToken);
        AuthSetting::set('telegram_bot_name', $this->telegramBotName);

        AuthSetting::set('smtp_host', $this->smtpHost);
        AuthSetting::set('smtp_port', $this->smtpPort);
        AuthSetting::set('smtp_username', $this->smtpUsername);
        AuthSetting::set('smtp_password', $this->smtpPassword);
        AuthSetting::set('smtp_encryption', $this->smtpEncryption);
        AuthSetting::set('smtp_from_email', $this->smtpFromEmail);
        AuthSetting::set('smtp_from_name', $this->smtpFromName);

        AuthSetting::set('social_login_enabled', $this->socialLoginEnabled);
        AuthSetting::set('social_login_auto_link_email', $this->socialLoginAutoLinkEmail);
        AuthSetting::set('social_login_require_verified_email', $this->socialLoginRequireVerifiedEmail);

        foreach ($this->socialProviders as $name => $config) {
            AuthSetting::set("social_provider_{$name}_enabled", $config['enabled']);
            AuthSetting::set("social_provider_{$name}_client_id", $config['client_id']);
            AuthSetting::set("social_provider_{$name}_client_secret", $config['client_secret']);
            AuthSetting::set("social_provider_{$name}_scopes", $config['scopes']);
        }

        $this->saving = false;
        $this->dispatch('toast', type: 'success', message: __('Settings saved successfully'));
    }

    public function resetToDefaults(): void
    {
        $this->channelToggles = ['sms' => true, 'whatsapp' => false, 'telegram' => false];
        $this->twoFactorDefaultChannel = 'sms';
        $this->twoFactorForceAllUsers = false;
        $this->otpLoginPhoneEnabled = true;
        $this->otpLoginWhatsAppEnabled = false;
        $this->otpLoginTelegramEnabled = false;
        $this->otpLoginEmailEnabled = true;
        $this->otpLoginMagicLinkEnabled = false;
        $this->magicLoginEnabled = false;
        $this->magicLinkExpiration = 30;
        $this->magicLinkDeviceRestriction = 'all';
        $this->emailOtpExpiration = 10;
        $this->otpRateLimitPerIp = 10;
        $this->otpRateLimitWindow = 60;
        $this->otpCooldownSeconds = 60;
        $this->sessionTimeout = 120;
        $this->loginThrottleAttempts = 5;
        $this->loginThrottleDecay = 60;
        $this->smsGateway = 'sslwireless';
        $this->smsUsername = '';
        $this->smsPassword = '';
        $this->smsSid = '';
        $this->whatsappApiUrl = '';
        $this->whatsappPhoneId = '';
        $this->whatsappAccessToken = '';
        $this->telegramBotToken = '';
        $this->telegramBotName = '';
        $this->smtpHost = '';
        $this->smtpPort = '587';
        $this->smtpUsername = '';
        $this->smtpPassword = '';
        $this->smtpEncryption = 'tls';
        $this->smtpFromEmail = '';
        $this->smtpFromName = '';

        $this->socialLoginEnabled = true;
        $this->socialLoginAutoLinkEmail = true;
        $this->socialLoginRequireVerifiedEmail = false;
        $this->socialProviders = [
            'google' => ['enabled' => false, 'client_id' => '', 'client_secret' => '', 'scopes' => 'openid,profile,email'],
            'github' => ['enabled' => false, 'client_id' => '', 'client_secret' => '', 'scopes' => 'user:email,read:user'],
            'facebook' => ['enabled' => false, 'client_id' => '', 'client_secret' => '', 'scopes' => 'email,public_profile'],
            'twitter' => ['enabled' => false, 'client_id' => '', 'client_secret' => '', 'scopes' => 'tweet.read,users.read'],
            'linkedin' => ['enabled' => false, 'client_id' => '', 'client_secret' => '', 'scopes' => 'openid,profile,email'],
            'gitlab' => ['enabled' => false, 'client_id' => '', 'client_secret' => '', 'scopes' => 'read_user'],
            'microsoft' => ['enabled' => false, 'client_id' => '', 'client_secret' => '', 'scopes' => 'openid,profile,email,User.Read'],
        ];
    }

    public function render()
    {
        $enabledCount = collect($this->channelToggles)->filter()->count();
        $socialEnabledCount = collect($this->socialProviders)->filter(fn ($p) => $p['enabled'])->count();

        return view('livewire.admin.admin-auth-config', [
            'enabledCount' => $enabledCount,
            'totalProviders' => count($this->channelToggles),
            'socialEnabledCount' => $socialEnabledCount,
        ])->layout('components.admin.app', ['header' => 'Authentication Configuration']);
    }
}

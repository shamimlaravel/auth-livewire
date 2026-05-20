<?php

namespace Database\Seeders;

use App\Models\AuthSetting;
use Illuminate\Database\Seeder;

class AuthSettingsSeeder extends Seeder
{
    /**
     * Seed the auth_settings table with sensible defaults.
     */
    public function run(): void
    {
        $defaults = [
            // ── Channel toggles ───────────────────────────────────────────────
            'channel_sms_enabled' => 'true',
            'channel_whatsapp_enabled' => 'false',
            'channel_telegram_enabled' => 'false',

            // ── 2FA settings ─────────────────────────────────────────────────
            'two_factor_default_channel' => 'sms',
            'two_factor_force_all_users' => 'false',

            // ── OTP login toggles ────────────────────────────────────────────
            'otp_login_phone_enabled' => 'true',
            'otp_login_whatsapp_enabled' => 'false',
            'otp_login_telegram_enabled' => 'false',

            // ── Rate limits ──────────────────────────────────────────────────
            'otp_rate_limit_per_ip' => '10',
            'otp_rate_limit_window_minutes' => '60',
            'otp_cooldown_seconds' => '60',
        ];

        foreach ($defaults as $key => $value) {
            AuthSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}

<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Auth Channels Configuration
    |--------------------------------------------------------------------------
    |
    | Controls which OTP / 2FA delivery channels are enabled and their
    | per-channel runtime settings.  Toggle `enabled` from the admin panel
    | at runtime via the `auth_settings` table.
    |
    */

    'default_channel' => env('TWO_FACTOR_CHANNEL', 'sms'),

    'channels' => [

        /**
         * SMS — delivered through the Larament Barta SMS gateway.
         */
        'sms' => [
            'enabled' => true,
            'gateway' => env('BARTA_DEFAULT_GATEWAY', 'sslwireless'),
            'ttl_minutes' => 10,
            'code_length' => 6,
        ],

        /**
         * WhatsApp — delivered through the Facebook Cloud API.
         */
        'whatsapp' => [
            'enabled' => false,
            'api_url' => env('WHATSAPP_API_URL'),
            'phone_id' => env('WHATSAPP_PHONE_NUMBER_ID'),
            'access_token' => env('WHATSAPP_ACCESS_TOKEN'),
            'ttl_minutes' => 10,
            'code_length' => 6,
        ],

        /**
         * Telegram — delivered through the DefStudio Telegraph package.
         */
        'telegram' => [
            'enabled' => false,
            'bot_token' => env('TELEGRAM_BOT_TOKEN'),
            'bot_name' => env('TELEGRAM_BOT_NAME'),
            'ttl_minutes' => 10,
            'code_length' => 6,
        ],

        /**
         * Email — the existing OTP login path (always available).
         */
        'email' => [
            'enabled' => true,
            'ttl_minutes' => 10,
            'code_length' => 6,
        ],

    ],

];

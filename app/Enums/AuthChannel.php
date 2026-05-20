<?php

namespace App\Enums;

enum AuthChannel: string
{
    case SMS = 'sms';
    case WhatsApp = 'whatsapp';
    case Telegram = 'telegram';
    case Email = 'email';

    /**
     * Return a human-readable label for the channel.
     */
    public function label(): string
    {
        return match ($this) {
            self::SMS => __('SMS'),
            self::WhatsApp => __('WhatsApp'),
            self::Telegram => __('Telegram'),
            self::Email => __('Email'),
        };
    }

    /**
     * Return an icon / SVG symbol identifier for UI use.
     */
    public function icon(): string
    {
        return match ($this) {
            self::SMS => 'phone',
            self::WhatsApp => 'chat',
            self::Telegram => 'send',
            self::Email => 'envelope',
        };
    }

    /**
     * All channels available for OTP login.
     */
    public static function loginChannels(): array
    {
        return [self::SMS, self::WhatsApp, self::Telegram, self::Email];
    }

    /**
     * All channels available for 2FA.
     */
    public static function twoFactorChannels(): array
    {
        return [self::SMS, self::WhatsApp, self::Telegram];
    }
}

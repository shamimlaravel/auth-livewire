<?php

namespace App\Services\Auth;

use App\Enums\AuditEvent;
use App\Enums\AuthChannel;
use App\Models\AuthSetting;
use App\Models\OtpToken;
use App\Models\User;
use App\Notifications\OtpLoginNotification;
use App\Services\Security\AuditService;
use DefStudio\Telegraph\Telegraph;
use Devsfort\Whatsapp\Facades\Whatsapp;
use Larament\Barta\Facades\Barta;

class MultiChannelOtpService
{
    public function __construct(private readonly AuditService $auditService) {}

    /**
     * Send an OTP to the given identifiable value through the specified channel.
     */
    public function send(
        string $identifiable,
        AuthChannel|string $channel,
        ?int $ttlMinutes = 10,
    ): string {
        $channel = $channel instanceof AuthChannel ? $channel : AuthChannel::from($channel);

        // Invalidate any unused tokens for this identifiable on this channel
        $this->invalidateExisting($identifiable, $channel);

        // Generate OTP (6-digit numeric code)
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Persist hashed token record (multi-channel aware)
        OtpToken::create([
            'email' => $channel === AuthChannel::Email ? $identifiable : null,
            'identifiable' => $identifiable,
            'channel' => $channel->value,
            'token' => hash('sha256', $otp),
            'expires_at' => now()->addMinutes($ttlMinutes),
        ]);

        // Dispatch channel-specific delivery
        $this->deliver($identifiable, $channel, $otp);

        return $otp;
    }

    /**
     * Verify an OTP code for the given identifiable value and channel.
     */
    public function verify(
        string $identifiable,
        string $code,
        AuthChannel|string $channel,
    ): ?User {
        $channel = $channel instanceof AuthChannel ? $channel : AuthChannel::from($channel);

        $hashed = hash('sha256', $code);

        $record = OtpToken::query()
            ->where(function ($q) use ($identifiable) {
                $q->where('identifiable', $identifiable)
                    ->orWhere('email', $identifiable);
            })
            ->where('channel', $channel->value)
            ->where('token', $hashed)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->first();

        if (! $record) {
            $this->auditService->log(
                event: AuditEvent::OtpLoginFailed,
                ipAddress: request()->ip(),
                userAgent: request()->userAgent(),
                payload: ['identifiable' => $identifiable, 'channel' => $channel->value, 'reason' => 'Invalid or expired code'],
            );

            return null;
        }

        $record->update(['used_at' => now()]);

        // Resolve the user (identifiable may be email, phone, or other channel ID)
        $user = $this->resolveUser($identifiable, $channel);

        if ($user) {
            $this->auditService->log(
                event: AuditEvent::OtpLoginVerified,
                user: $user,
                ipAddress: request()->ip(),
                userAgent: request()->userAgent(),
                payload: ['identifiable' => $identifiable, 'channel' => $channel->value],
            );
        }

        return $user;
    }

    /**
     * Invalidate any outstanding unused tokens for this identifiable+channel pair.
     */
    protected function invalidateExisting(string $identifiable, AuthChannel $channel): void
    {
        OtpToken::where('identifiable', $identifiable)
            ->where('channel', $channel->value)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->update(['used_at' => now()]);
    }

    /**
     * Deliver the OTP to the target through the appropriate channel.
     *
     * For SMS, WhatsApp, and Telegram you should register concrete sender
     * classes in your service container; this method gracefully logs when
     * a channel sender is not registered so production never crashes.
     */
    protected function deliver(string $identifiable, AuthChannel $channel, string $otp): void
    {
        match ($channel) {
            AuthChannel::Email => $this->sendViaEmail($identifiable, $otp),
            AuthChannel::SMS => $this->sendViaSms($identifiable, $otp),
            AuthChannel::WhatsApp => $this->sendViaWhatsApp($identifiable, $otp),
            AuthChannel::Telegram => $this->sendViaTelegram($identifiable, $otp),
        };
    }

    protected function sendViaEmail(string $email, string $otp): void
    {
        $user = User::where('email', $email)->first();
        if ($user) {
            $user->notify(new OtpLoginNotification($otp));
        }
    }

    protected function sendViaSms(string $phone, string $otp): void
    {
        if (AuthSetting::isEnabled('channel_sms_enabled')) {
            try {
                Barta::to($phone)
                    ->message(trans('auth.otp_sms_body', ['otp' => $otp]))
                    ->send();
            } catch (\Throwable $e) {
                \Log::warning('SMS delivery failed: '.$e->getMessage());
            }
        } else {
            \Log::warning('SMS gateway disabled; OTP for '.$phone.' not sent.');
        }
    }

    protected function sendViaWhatsApp(string $phone, string $otp): void
    {
        if (AuthSetting::isEnabled('channel_whatsapp_enabled')) {
            try {
                Whatsapp::sendText(
                    $phone,
                    trans('auth.otp_whatsapp_body', ['otp' => $otp]),
                );
            } catch (\Throwable $e) {
                \Log::warning('WhatsApp delivery failed: '.$e->getMessage());
            }
        } else {
            \Log::warning('WhatsApp provider disabled; OTP for '.$phone.' not sent.');
        }
    }

    protected function sendViaTelegram(string $chatId, string $otp): void
    {
        if (AuthSetting::isEnabled('channel_telegram_enabled')) {
            try {
                Telegraph::chat($chatId)
                    ->message(trans('auth.otp_telegram_body', ['otp' => $otp]))
                    ->send();
            } catch (\Throwable $e) {
                \Log::warning('Telegram delivery failed: '.$e->getMessage());
            }
        } else {
            \Log::warning('Telegram provider disabled; OTP for '.$chatId.' not sent.');
        }
    }

    /**
     * Find a user by their identifiable value for the given channel.
     */
    protected function resolveUser(string $identifiable, AuthChannel $channel): ?User
    {
        return match ($channel) {
            AuthChannel::Email => User::where('email', $identifiable)->first(),
            AuthChannel::SMS => User::where('phone', $identifiable)->first(),
            AuthChannel::WhatsApp => User::where('whatsapp_number', $identifiable)->first(),
            AuthChannel::Telegram => User::where('telegram_chat_id', $identifiable)->first(),
        };
    }
}

<?php

namespace App\Services\Auth;

use App\Enums\AuditEvent;
use App\Models\OtpToken;
use App\Models\User;
use App\Notifications\OtpLoginNotification;
use App\Services\Security\AuditService;

class OtpService
{
    public function __construct(private readonly AuditService $auditService) {}

    public function send(string $email, ?string $ipAddress = null, ?string $userAgent = null): void
    {
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        OtpToken::where('email', $email)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->update(['used_at' => now()]);

        OtpToken::create([
            'email' => $email,
            'token' => hash('sha256', $otp),
            'expires_at' => now()->addMinutes(10),
        ]);

        $user = User::where('email', $email)->first();

        if ($user) {
            $user->notify(new OtpLoginNotification($otp));
        }

        $this->auditService->log(
            event: AuditEvent::OtpLoginRequested,
            ipAddress: $ipAddress,
            userAgent: $userAgent,
            payload: ['email' => $email],
        );
    }

    public function verify(string $email, string $code): ?User
    {
        $hashed = hash('sha256', $code);
        $record = OtpToken::where('email', $email)
            ->where('token', $hashed)
            ->valid()
            ->first();

        if (! $record) {
            $this->auditService->log(
                event: AuditEvent::OtpLoginFailed,
                ipAddress: request()->ip(),
                userAgent: request()->userAgent(),
                payload: ['email' => $email, 'reason' => 'Invalid or expired code'],
            );

            return null;
        }

        $record->update(['used_at' => now()]);

        $user = User::where('email', $email)->first();

        if ($user) {
            $this->auditService->log(
                event: AuditEvent::OtpLoginVerified,
                user: $user,
                ipAddress: request()->ip(),
                userAgent: request()->userAgent(),
                payload: ['email' => $email],
            );
        }

        return $user;
    }
}

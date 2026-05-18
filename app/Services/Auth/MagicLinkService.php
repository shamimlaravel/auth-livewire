<?php

namespace App\Services\Auth;

use App\Enums\AuditEvent;
use App\Models\MagicLinkToken;
use App\Models\User;
use App\Notifications\MagicLinkNotification;
use App\Services\Security\AuditService;
use Illuminate\Support\Str;

class MagicLinkService
{
    public function __construct(private readonly AuditService $auditService) {}

    public function send(string $email, ?string $ipAddress = null, ?string $userAgent = null): void
    {
        $token = Str::random(64);

        MagicLinkToken::where('email', $email)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->update(['used_at' => now()]);

        MagicLinkToken::create([
            'email' => $email,
            'token' => hash('sha256', $token),
            'expires_at' => now()->addMinutes(15),
        ]);

        $user = User::where('email', $email)->first();

        if ($user) {
            $user->notify(new MagicLinkNotification($token));
        }

        $this->auditService->log(
            event: AuditEvent::MagicLinkRequested,
            ipAddress: $ipAddress,
            userAgent: $userAgent,
            payload: ['email' => $email],
        );
    }

    public function verify(string $token): ?User
    {
        $hashed = hash('sha256', $token);
        $record = MagicLinkToken::where('token', $hashed)->valid()->first();

        if (! $record) {
            return null;
        }

        $record->update(['used_at' => now()]);

        $user = User::where('email', $record->email)->first();

        if ($user) {
            $this->auditService->log(
                event: AuditEvent::MagicLinkUsed,
                user: $user,
                payload: ['email' => $record->email],
            );
        }

        return $user;
    }
}

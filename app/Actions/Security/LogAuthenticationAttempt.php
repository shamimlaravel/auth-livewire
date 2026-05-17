<?php

namespace App\Actions\Security;

use App\Enums\LoginStatus;
use App\Models\LoginAttempt;
use App\Models\User;

class LogAuthenticationAttempt
{
    public function execute(
        ?User $user,
        string $ipAddress,
        LoginStatus $status,
        ?string $failureReason = null,
        ?string $userAgent = null,
        ?string $deviceFingerprint = null,
    ): LoginAttempt {
        return LoginAttempt::create([
            'user_id' => $user?->id,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'device_fingerprint' => $deviceFingerprint,
            'status' => $status->value,
            'failure_reason' => $failureReason,
            'attempted_at' => now(),
        ]);
    }
}

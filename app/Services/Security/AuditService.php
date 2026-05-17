<?php

namespace App\Services\Security;

use App\Enums\AuditEvent;
use App\Models\AuditLog;
use App\Models\User;

class AuditService
{
    public function log(
        AuditEvent $event,
        ?User $user = null,
        ?string $ipAddress = null,
        ?string $userAgent = null,
        ?array $payload = null,
    ): AuditLog {
        return AuditLog::create([
            'user_id' => $user?->id,
            'event_type' => $event->value,
            'ip_address' => $ipAddress ?? request()->ip(),
            'user_agent' => $userAgent ?? request()->userAgent(),
            'payload' => $payload,
        ]);
    }
}

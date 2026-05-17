<?php

namespace App\Observers;

use App\Enums\AuditEvent;
use App\Models\User;
use App\Notifications\SuspiciousLoginNotification;
use App\Services\Security\AuditService;

class UserObserver
{
    public function __construct(private readonly AuditService $auditService) {}

    public function updating(User $user): void
    {
        if ($user->isDirty('last_login_ip') && $user->getOriginal('last_login_ip')) {
            $previousIp = $user->getOriginal('last_login_ip');
            $newIp = $user->last_login_ip;

            $this->auditService->log(
                event: AuditEvent::IpChanged,
                user: $user,
                ipAddress: $newIp,
                payload: ['previous_ip' => $previousIp, 'new_ip' => $newIp],
            );

            $this->auditService->log(
                event: AuditEvent::SuspiciousLogin,
                user: $user,
                ipAddress: $newIp,
                payload: ['reason' => 'IP address changed', 'previous_ip' => $previousIp],
            );

            $user->notify(new SuspiciousLoginNotification(
                ip: $newIp,
                previousIp: $previousIp,
                time: now(),
            ));
        }
    }
}

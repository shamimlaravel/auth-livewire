<?php

namespace App\Services\Security;

use App\Enums\AuditEvent;
use App\Models\User;
use App\Models\WhitelistedIp;
use App\Notifications\SuspiciousLoginNotification;

class IpValidationService
{
    public function __construct(private readonly AuditService $auditService) {}

    public function isWhitelisted(?string $ip): bool
    {
        if (! $ip) {
            return false;
        }

        return WhitelistedIp::where('ip_address', $ip)->exists();
    }

    public function validateIpChange(User $user, ?string $currentIp): void
    {
        if (! $currentIp || ! $user->last_login_ip) {
            return;
        }

        if ($user->last_login_ip !== $currentIp) {
            $this->auditService->log(
                event: AuditEvent::IpChanged,
                user: $user,
                ipAddress: $currentIp,
                payload: [
                    'previous_ip' => $user->last_login_ip,
                    'new_ip' => $currentIp,
                ],
            );

            $this->auditService->log(
                event: AuditEvent::SuspiciousLogin,
                user: $user,
                ipAddress: $currentIp,
                payload: [
                    'reason' => 'IP address changed',
                    'previous_ip' => $user->last_login_ip,
                ],
            );

            $user->notify(new SuspiciousLoginNotification(
                ip: $currentIp,
                previousIp: $user->last_login_ip,
                time: now(),
            ));
        }
    }
}

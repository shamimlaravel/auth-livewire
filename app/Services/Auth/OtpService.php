<?php

namespace App\Services\Auth;

use App\Enums\AuditEvent;
use App\Enums\AuthChannel;
use App\Models\User;
use App\Services\Security\AuditService;

/**
 * Backward-compatible wrapper around MultiChannelOtpService.
 *
 * Keeps the original email-only API intact so existing controllers,
 * notifications, and Livewire components keep working without changes.
 */
class OtpService
{
    public function __construct(
        private readonly MultiChannelOtpService $multiService,
        private readonly AuditService $auditService,
    ) {}

    /**
     * Send OTP via email — original API preserved.
     */
    public function send(string $email, ?string $ipAddress = null, ?string $userAgent = null): void
    {
        $this->multiService->send($email, AuthChannel::Email);

        $this->auditService->log(
            event: AuditEvent::OtpLoginRequested,
            ipAddress: $ipAddress,
            userAgent: $userAgent,
            payload: ['email' => $email, 'channel' => 'email'],
        );
    }

    /**
     * Verify OTP via email — original API preserved.
     */
    public function verify(string $email, string $code): ?User
    {
        return $this->multiService->verify($email, $code, AuthChannel::Email);
    }
}

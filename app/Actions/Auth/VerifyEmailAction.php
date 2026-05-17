<?php

namespace App\Actions\Auth;

use App\Enums\AuditEvent;
use App\Models\User;
use App\Services\Security\AuditService;

readonly class VerifyEmailAction
{
    public function __construct(private AuditService $auditService) {}

    public function execute(User $user): void
    {
        if ($user->hasVerifiedEmail()) {
            return;
        }

        $user->markEmailAsVerified();

        $this->auditService->log(
            event: AuditEvent::EmailVerified,
            user: $user,
        );
    }
}

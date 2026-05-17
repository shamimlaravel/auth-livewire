<?php

namespace App\Services\Auth;

use App\Enums\AuditEvent;
use App\Models\User;
use App\Services\Security\AuditService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class PasswordResetService
{
    public function __construct(private readonly AuditService $auditService) {}

    public function sendResetLink(string $email): string
    {
        $status = Password::sendResetLink(['email' => $email]);

        if ($status !== Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        $this->auditService->log(
            event: AuditEvent::PasswordResetRequested,
            payload: ['email' => $email],
        );

        return __($status);
    }

    public function reset(array $credentials): string
    {
        $status = Password::reset($credentials, function (User $user, string $password) {
            $user->update(['password' => Hash::make($password)]);

            $this->auditService->log(
                event: AuditEvent::PasswordReset,
                user: $user,
            );
        });

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        return __($status);
    }
}

<?php

namespace App\Services\Auth;

use App\Enums\AuditEvent;
use App\Models\User;
use App\Services\Security\AuditService;
use Illuminate\Support\Str;
use PragmaRX\Google2FALaravel\Google2FA;

class TwoFactorService
{
    public function __construct(
        private readonly Google2FA $google2fa,
        private readonly AuditService $auditService,
    ) {}

    public function generateSecretKey(): string
    {
        return $this->google2fa->generateSecretKey();
    }

    public function getQrCodeUrl(string $companyName, string $email, string $secret): string
    {
        return $this->google2fa->getQRCodeUrl($companyName, $email, $secret);
    }

    public function verify(string $secret, string $code): bool
    {
        return $this->google2fa->verifyKey($secret, $code);
    }

    public function enable(User $user, string $code): bool
    {
        if (! $this->verify($user->two_factor_secret, $code)) {
            return false;
        }

        $user->update([
            'two_factor_confirmed_at' => now(),
        ]);

        $this->auditService->log(
            event: AuditEvent::TwoFactorEnabled,
            user: $user,
        );

        return true;
    }

    public function disable(User $user): void
    {
        $user->update([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ]);

        $this->auditService->log(
            event: AuditEvent::TwoFactorDisabled,
            user: $user,
        );
    }

    public function generateRecoveryCodes(): array
    {
        $codes = [];

        for ($i = 0; $i < 8; $i++) {
            $codes[] = Str::random(10).'-'.Str::random(10);
        }

        return $codes;
    }
}

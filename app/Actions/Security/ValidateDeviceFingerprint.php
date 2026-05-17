<?php

namespace App\Actions\Security;

use App\Models\User;
use App\Services\Security\DeviceFingerprintService;

class ValidateDeviceFingerprint
{
    public function __construct(private readonly DeviceFingerprintService $service) {}

    public function execute(User $user, string $fingerprint): bool
    {
        if (! $this->service->isKnown($user, $fingerprint)) {
            $this->service->record($user, $fingerprint);

            return false;
        }

        return true;
    }
}

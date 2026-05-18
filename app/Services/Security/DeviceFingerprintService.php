<?php

namespace App\Services\Security;

use App\Models\DeviceFingerprint;
use App\Models\User;

class DeviceFingerprintService
{
    public function generate(?string $userAgent, ?string $ip, ?string $additional = null): ?string
    {
        if (! $userAgent && ! $ip) {
            return null;
        }

        $raw = implode('|', array_filter([$userAgent, $ip, $additional]));

        return hash('sha256', $raw);
    }

    public function record(User $user, string $fingerprint, ?string $platform = null, ?string $browser = null): DeviceFingerprint
    {
        return $user->deviceFingerprints()->updateOrCreate(
            ['fingerprint_hash' => $fingerprint, 'user_id' => $user->id],
            [
                'device_name' => $browser,
                'platform' => $platform,
                'browser' => $browser,
                'last_used_at' => now(),
            ],
        );
    }

    public function isKnown(User $user, string $fingerprint): bool
    {
        return $user->deviceFingerprints()
            ->where('fingerprint_hash', $fingerprint)
            ->exists();
    }

    public function isTrusted(User $user, string $fingerprint): bool
    {
        return $user->deviceFingerprints()
            ->where('fingerprint_hash', $fingerprint)
            ->where('is_trusted', true)
            ->exists();
    }
}

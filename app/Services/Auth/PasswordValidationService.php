<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class PasswordValidationService
{
    public function checkStrength(string $password): array
    {
        $criteria = [
            'length' => strlen($password) >= 8,
            'uppercase' => preg_match('/[A-Z]/', $password) === 1,
            'lowercase' => preg_match('/[a-z]/', $password) === 1,
            'digit' => preg_match('/[0-9]/', $password) === 1,
            'special' => preg_match('/[^A-Za-z0-9]/', $password) === 1,
        ];

        $score = count(array_filter($criteria));

        $label = match (true) {
            $score <= 1 => 'Weak',
            $score <= 3 => 'Fair',
            $score === 4 => 'Good',
            $score === 5 => 'Strong',
        };

        return [
            'score' => $score,
            'label' => $label,
            'criteria' => $criteria,
        ];
    }

    public function checkBreach(string $password): int
    {
        $hash = strtoupper(sha1($password));
        $prefix = substr($hash, 0, 5);
        $suffix = substr($hash, 5);

        $cacheKey = "hibp_prefix_{$prefix}";

        $response = Cache::remember($cacheKey, 86400, function () use ($prefix) {
            $response = Http::timeout(5)
                ->withHeaders(['User-Agent' => 'Laravel-Auth-System'])
                ->get("https://api.pwnedpasswords.com/range/{$prefix}");

            if (! $response->successful()) {
                return '';
            }

            return $response->body();
        });

        if (empty($response)) {
            return 0;
        }

        foreach (explode("\r\n", $response) as $line) {
            $parts = explode(':', $line);

            if (strtoupper(trim($parts[0] ?? '')) === $suffix) {
                return (int) ($parts[1] ?? 0);
            }
        }

        return 0;
    }
}

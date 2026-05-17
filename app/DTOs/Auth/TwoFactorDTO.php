<?php

namespace App\DTOs\Auth;

readonly class TwoFactorDTO
{
    public function __construct(
        public string $code,
        public ?string $ipAddress = null,
        public ?string $userAgent = null,
    ) {}

    public static function fromRequest(array $data, ?string $ip = null, ?string $ua = null): self
    {
        return new self(
            code: $data['code'],
            ipAddress: $ip,
            userAgent: $ua,
        );
    }
}

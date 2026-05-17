<?php

namespace App\DTOs\Auth;

readonly class MagicLinkDTO
{
    public function __construct(
        public string $email,
        public ?string $ipAddress = null,
        public ?string $userAgent = null,
    ) {}

    public static function fromRequest(array $data, ?string $ip = null, ?string $ua = null): self
    {
        return new self(
            email: $data['email'],
            ipAddress: $ip,
            userAgent: $ua,
        );
    }
}

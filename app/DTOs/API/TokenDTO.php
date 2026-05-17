<?php

namespace App\DTOs\API;

use Laravel\Sanctum\NewAccessToken;

readonly class TokenDTO
{
    public function __construct(
        public string $accessToken,
        public string $tokenType = 'Bearer',
        public ?int $expiresIn = null,
    ) {}

    public static function fromSanctum(NewAccessToken $token, ?int $expiresIn = null): self
    {
        return new self(
            accessToken: $token->plainTextToken,
            tokenType: 'Bearer',
            expiresIn: $expiresIn,
        );
    }

    public function toArray(): array
    {
        return [
            'access_token' => $this->accessToken,
            'token_type' => $this->tokenType,
            'expires_in' => $this->expiresIn,
        ];
    }
}

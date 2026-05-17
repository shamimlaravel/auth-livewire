<?php

namespace App\DTOs\Auth;

use App\Http\Requests\API\Auth\ApiLoginRequest;
use App\Http\Requests\Auth\LoginRequest;

readonly class LoginDTO
{
    public function __construct(
        public string $login,
        public string $password,
        public bool $remember = false,
        public ?string $ipAddress = null,
        public ?string $userAgent = null,
        public ?string $deviceFingerprint = null,
    ) {}

    public static function fromRequest(LoginRequest $request): self
    {
        return new self(
            login: $request->validated('email'),
            password: $request->validated('password'),
            remember: (bool) $request->validated('remember', false),
            ipAddress: $request->ip(),
            userAgent: $request->userAgent(),
            deviceFingerprint: $request->header('X-Device-Fingerprint'),
        );
    }

    public static function fromApiRequest(ApiLoginRequest $request): self
    {
        return new self(
            login: $request->validated('email'),
            password: $request->validated('password'),
            ipAddress: $request->ip(),
            userAgent: $request->userAgent(),
            deviceFingerprint: $request->header('X-Device-Fingerprint'),
        );
    }
}

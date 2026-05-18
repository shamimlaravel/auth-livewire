<?php

namespace App\DTOs\Auth;

use App\Http\Requests\API\Auth\ApiRegisterRequest;
use App\Http\Requests\Auth\RegisterRequest;

readonly class RegisterDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public ?string $username = null,
        public ?string $phone = null,
        public ?string $company = null,
        public ?string $timezone = null,
        public ?string $address_line_1 = null,
        public ?string $city = null,
        public ?string $state = null,
        public ?string $postal_code = null,
        public ?string $country = null,
        public ?string $ipAddress = null,
        public ?string $userAgent = null,
        public ?string $deviceFingerprint = null,
    ) {}

    public static function fromRequest(RegisterRequest $request): self
    {
        return new self(
            name: $request->validated('name'),
            email: $request->validated('email'),
            password: $request->validated('password'),
            ipAddress: $request->ip(),
            userAgent: $request->userAgent(),
            deviceFingerprint: $request->header('X-Device-Fingerprint'),
        );
    }

    public static function fromApiRequest(ApiRegisterRequest $request): self
    {
        return new self(
            name: $request->validated('name'),
            email: $request->validated('email'),
            password: $request->validated('password'),
            username: $request->validated('username'),
            phone: $request->validated('phone'),
            company: $request->validated('company'),
            timezone: $request->validated('timezone'),
            address_line_1: $request->validated('address_line_1'),
            city: $request->validated('city'),
            state: $request->validated('state'),
            postal_code: $request->validated('postal_code'),
            country: $request->validated('country'),
            ipAddress: $request->ip(),
            userAgent: $request->userAgent(),
            deviceFingerprint: $request->header('X-Device-Fingerprint'),
        );
    }
}

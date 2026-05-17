<?php

namespace App\DTOs\User;

use App\Models\User;

readonly class UserDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public ?string $avatar,
        public ?string $phone,
        public string $status,
        public bool $emailVerified,
        public bool $twoFactorEnabled,
        public string $authProvider,
        public ?string $lastLoginIp,
        public ?string $lastLoginAt,
        public array $roles,
        public array $permissions,
    ) {}

    public static function fromModel(User $user): self
    {
        return new self(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            avatar: $user->avatar,
            phone: $user->phone,
            status: $user->status ?? 'active',
            emailVerified: ! is_null($user->email_verified_at),
            twoFactorEnabled: ! is_null($user->two_factor_confirmed_at),
            authProvider: $user->auth_provider ?? 'email',
            lastLoginIp: $user->last_login_ip,
            lastLoginAt: $user->last_login_at?->toIso8601String(),
            roles: $user->roles->pluck('name')->toArray(),
            permissions: $user->getAllPermissions()->pluck('name')->toArray(),
        );
    }
}

<?php

namespace App\Services\Auth;

use App\DTOs\Auth\SocialLoginDTO;
use App\Enums\AuditEvent;
use App\Models\SocialAccount;
use App\Models\User;
use App\Services\Security\AuditService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class SocialLoginService
{
    public function __construct(private readonly AuditService $auditService) {}

    public function findOrCreate(SocialLoginDTO $dto): User
    {
        $account = SocialAccount::where('provider', $dto->provider)
            ->where('provider_id', $dto->providerId)
            ->first();

        if ($account) {
            return $account->user;
        }

        $user = User::where('email', $dto->email)->first();

        if (! $user) {
            $user = User::create([
                'name' => $dto->name,
                'email' => $dto->email,
                'password' => Hash::make(Str::random(32)),
                'avatar' => $dto->avatar,
                'auth_provider' => $dto->provider,
                'auth_provider_id' => $dto->providerId,
            ]);

            $role = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
            $user->assignRole($role);
        }

        $user->socialAccounts()->create([
            'provider' => $dto->provider,
            'provider_id' => $dto->providerId,
            'avatar' => $dto->avatar,
        ]);

        $this->auditService->log(
            event: AuditEvent::SocialLoginLinked,
            user: $user,
            payload: ['provider' => $dto->provider],
        );

        return $user;
    }
}

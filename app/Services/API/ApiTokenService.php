<?php

namespace App\Services\API;

use App\DTOs\API\TokenDTO;
use App\DTOs\User\UserDTO;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\NewAccessToken;

class ApiTokenService
{
    public function createToken(User $user, string $deviceName = 'api', ?array $abilities = ['*']): NewAccessToken
    {
        return $user->createToken($deviceName, $abilities);
    }

    public function issueTokenResponse(User $user, string $deviceName = 'api', ?array $abilities = ['*']): array
    {
        $token = $this->createToken($user, $deviceName, $abilities);

        return [
            'token' => TokenDTO::fromSanctum($token)->toArray(),
            'user' => UserDTO::fromModel($user),
        ];
    }

    public function revokeCurrentToken(User $user): void
    {
        $user->currentAccessToken()->delete();
    }

    public function revokeAllTokens(User $user): void
    {
        $user->tokens()->delete();
    }

    public function authenticate(array $credentials): array
    {
        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => [__('auth.failed')],
            ]);
        }

        if (! $user->isActive()) {
            throw ValidationException::withMessages([
                'email' => [__('auth.inactive')],
            ]);
        }

        return $this->issueTokenResponse($user);
    }
}

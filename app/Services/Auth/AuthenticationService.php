<?php

namespace App\Services\Auth;

use App\DTOs\Auth\LoginDTO;
use App\DTOs\Auth\RegisterDTO;
use App\Enums\AuditEvent;
use App\Models\User;
use App\Services\Security\AuditService;
use App\Services\Security\DeviceFingerprintService;
use App\Services\Security\IpValidationService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class AuthenticationService
{
    public function __construct(
        private readonly AuditService $auditService,
        private readonly DeviceFingerprintService $deviceFingerprintService,
        private readonly IpValidationService $ipValidationService,
    ) {}

    public function register(RegisterDTO $dto): User
    {
        $user = User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'username' => $dto->username,
            'password' => Hash::make($dto->password),
            'auth_provider' => 'email',
        ]);

        $role = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $user->assignRole($role);

        $profile = $user->profile()->create([
            'phone' => $dto->phone,
            'company' => $dto->company,
            'timezone' => $dto->timezone ?? 'UTC',
        ]);

        if ($dto->address_line_1) {
            $profile->addresses()->create([
                'address_line_1' => $dto->address_line_1,
                'city' => $dto->city,
                'state' => $dto->state,
                'postal_code' => $dto->postal_code,
                'country' => $dto->country,
                'is_primary' => true,
            ]);
        }

        event(new Registered($user));

        $this->auditService->log(
            event: AuditEvent::Registered,
            user: $user,
            ipAddress: $dto->ipAddress,
            userAgent: $dto->userAgent,
        );

        return $user;
    }

    public function login(LoginDTO $dto): User
    {
        $user = User::where('email', $dto->login)
            ->orWhere('username', $dto->login)
            ->first();

        if (! $user || ! Hash::check($dto->password, $user->password)) {
            $this->auditService->log(
                event: AuditEvent::LoginFailed,
                ipAddress: $dto->ipAddress,
                userAgent: $dto->userAgent,
                payload: ['login' => $dto->login, 'reason' => 'Invalid credentials'],
            );

            throw ValidationException::withMessages([
                'login' => [__('auth.failed')],
            ]);
        }

        if (! $user->isActive()) {
            $this->auditService->log(
                event: AuditEvent::LoginFailed,
                ipAddress: $dto->ipAddress,
                userAgent: $dto->userAgent,
                payload: ['login' => $dto->login, 'reason' => 'Account inactive'],
            );

            throw ValidationException::withMessages([
                'login' => [__('auth.inactive')],
            ]);
        }

        $this->ipValidationService->validateIpChange($user, $dto->ipAddress);

        $this->updateLoginMetadata($user, $dto);

        return $user;
    }

    public function sendPasswordResetLink(string $email): string
    {
        $status = Password::sendResetLink(['email' => $email]);

        if ($status !== Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        return __($status);
    }

    public function resetPassword(array $credentials): string
    {
        $status = Password::reset($credentials, function (User $user, string $password) {
            $user->update(['password' => Hash::make($password)]);
        });

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        return __($status);
    }

    private function updateLoginMetadata(User $user, LoginDTO $dto): void
    {
        $user->update([
            'last_login_ip' => $dto->ipAddress,
            'last_login_at' => now(),
        ]);
    }
}

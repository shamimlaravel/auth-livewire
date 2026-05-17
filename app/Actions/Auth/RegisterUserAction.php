<?php

namespace App\Actions\Auth;

use App\DTOs\Auth\RegisterDTO;
use App\Models\User;
use App\Services\Auth\AuthenticationService;

readonly class RegisterUserAction
{
    public function __construct(private AuthenticationService $authService) {}

    public function execute(RegisterDTO $dto): User
    {
        return $this->authService->register($dto);
    }
}

<?php

namespace App\Actions\Auth;

use App\DTOs\Auth\LoginDTO;
use App\Models\User;
use App\Services\Auth\AuthenticationService;

readonly class LoginUserAction
{
    public function __construct(private AuthenticationService $authService) {}

    public function execute(LoginDTO $dto): User
    {
        return $this->authService->login($dto);
    }
}

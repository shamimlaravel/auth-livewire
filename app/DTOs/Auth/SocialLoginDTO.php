<?php

namespace App\DTOs\Auth;

use Laravel\Socialite\Contracts\User as SocialiteUser;

readonly class SocialLoginDTO
{
    public function __construct(
        public string $provider,
        public string $providerId,
        public string $name,
        public string $email,
        public ?string $avatar = null,
        public ?string $token = null,
        public ?string $refreshToken = null,
    ) {}

    public static function fromSocialite(string $provider, SocialiteUser $socialiteUser): self
    {
        return new self(
            provider: $provider,
            providerId: $socialiteUser->getId(),
            name: $socialiteUser->getName() ?? $socialiteUser->getNickname() ?? 'User',
            email: $socialiteUser->getEmail() ?? $socialiteUser->getId().'@'.$provider.'.placeholder',
            avatar: $socialiteUser->getAvatar(),
            token: $socialiteUser->token,
            refreshToken: $socialiteUser->refreshToken,
        );
    }
}

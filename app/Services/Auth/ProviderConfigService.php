<?php

namespace App\Services\Auth;

use App\Models\AuthSetting;

class ProviderConfigService
{
    protected array $providers = [
        'google' => [
            'label' => 'Google',
            'description' => 'Sign in with Google account.',
            'default_scopes' => 'openid,profile,email',
        ],
        'github' => [
            'label' => 'GitHub',
            'description' => 'Sign in with GitHub account.',
            'default_scopes' => 'user:email,read:user',
        ],
        'facebook' => [
            'label' => 'Facebook',
            'description' => 'Sign in with Facebook account.',
            'default_scopes' => 'email,public_profile',
        ],
        'twitter' => [
            'label' => 'Twitter / X',
            'description' => 'Sign in with X (Twitter) account.',
            'default_scopes' => 'tweet.read,users.read',
        ],
        'linkedin' => [
            'label' => 'LinkedIn',
            'description' => 'Sign in with LinkedIn account.',
            'default_scopes' => 'openid,profile,email',
        ],
        'gitlab' => [
            'label' => 'GitLab',
            'description' => 'Sign in with GitLab account.',
            'default_scopes' => 'read_user',
        ],
        'microsoft' => [
            'label' => 'Microsoft',
            'description' => 'Sign in with Microsoft account.',
            'default_scopes' => 'openid,profile,email,User.Read',
        ],
    ];

    public function allProviders(): array
    {
        return $this->providers;
    }

    public function getConfig(string $provider): array
    {
        $clientId = AuthSetting::get("social_provider_{$provider}_client_id")
            ?? config("services.{$provider}.client_id", '');

        $clientSecret = AuthSetting::get("social_provider_{$provider}_client_secret")
            ?? config("services.{$provider}.client_secret", '');

        $scopes = AuthSetting::get("social_provider_{$provider}_scopes")
            ?? ($this->providers[$provider]['default_scopes'] ?? '');

        $enabled = AuthSetting::isEnabled("social_provider_{$provider}_enabled")
            && ! empty($clientId) && ! empty($clientSecret);

        return [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'redirect' => route('auth.social.callback', $provider),
            'scopes' => array_filter(explode(',', $scopes)),
            'enabled' => $enabled,
        ];
    }

    public function getAvailableProviders(): array
    {
        $available = [];

        foreach (array_keys($this->providers) as $provider) {
            $config = $this->getConfig($provider);

            if ($config['enabled']) {
                $available[] = $provider;
            }
        }

        return $available;
    }

    public function isProviderEnabled(string $provider): bool
    {
        return $this->getConfig($provider)['enabled'];
    }
}

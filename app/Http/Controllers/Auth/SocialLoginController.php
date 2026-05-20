<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\RedirectAuthenticatedUser;
use App\DTOs\Auth\SocialLoginDTO;
use App\Http\Controllers\Controller;
use App\Services\Auth\ProviderConfigService;
use App\Services\Auth\SocialLoginService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    public function __construct(
        private readonly SocialLoginService $socialLoginService,
        private readonly ProviderConfigService $providerConfig,
        private readonly RedirectAuthenticatedUser $redirectUser,
    ) {}

    public function redirect(string $provider): mixed
    {
        $config = $this->providerConfig->getConfig($provider);

        if (! $config['enabled']) {
            abort(404);
        }

        $redirect = Socialite::driver($provider);

        if (! empty($config['scopes'])) {
            $redirect->scopes($config['scopes']);
        }

        return $redirect->redirect();
    }

    public function callback(string $provider): RedirectResponse
    {
        $socialiteUser = Socialite::driver($provider)->user();

        $dto = SocialLoginDTO::fromSocialite($provider, $socialiteUser);

        $user = $this->socialLoginService->findOrCreate($dto);

        Auth::login($user);

        return redirect()->intended($this->redirectUser->execute());
    }
}

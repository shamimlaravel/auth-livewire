<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\RedirectAuthenticatedUser;
use App\DTOs\Auth\SocialLoginDTO;
use App\Http\Controllers\Controller;
use App\Services\Auth\SocialLoginService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    public function __construct(
        private readonly SocialLoginService $socialLoginService,
        private readonly RedirectAuthenticatedUser $redirectUser,
    ) {}

    public function redirect(string $provider): mixed
    {
        return Socialite::driver($provider)->redirect();
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

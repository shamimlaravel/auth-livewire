<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\RedirectAuthenticatedUser;
use App\Http\Controllers\Controller;
use App\Services\Auth\MagicLinkService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MagicLinkController extends Controller
{
    public function __construct(
        private readonly MagicLinkService $magicLinkService,
        private readonly RedirectAuthenticatedUser $redirectUser,
    ) {}

    public function verify(Request $request, string $token): RedirectResponse
    {
        $user = $this->magicLinkService->verify($token);

        if (! $user) {
            return redirect()->route('login')->withErrors([
                'email' => __('auth.magic_link_invalid'),
            ]);
        }

        Auth::login($user);

        return redirect()->intended($this->redirectUser->execute());
    }
}

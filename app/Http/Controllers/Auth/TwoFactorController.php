<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\TwoFactorRequest;
use App\Services\Auth\TwoFactorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends Controller
{
    public function __construct(private readonly TwoFactorService $twoFactorService) {}

    public function create(): mixed
    {
        return view('livewire.auth.two-factor-challenge');
    }

    public function store(TwoFactorRequest $request): RedirectResponse
    {
        $user = Auth::user();

        if (! $this->twoFactorService->verify($user->two_factor_secret, $request->validated('code'))) {
            return back()->withErrors(['code' => __('auth.invalid_two_factor_code')]);
        }

        $request->session()->put('two_factor_authenticated', true);

        return redirect()->intended('/dashboard');
    }

    public function enable(Request $request): mixed
    {
        $user = $request->user();
        $secret = $this->twoFactorService->generateSecretKey();

        $user->update(['two_factor_secret' => $secret]);

        $qrCodeUrl = $this->twoFactorService->getQrCodeUrl(
            config('app.name'),
            $user->email,
            $secret,
        );

        $recoveryCodes = $this->twoFactorService->generateRecoveryCodes();
        $user->update(['two_factor_recovery_codes' => json_encode($recoveryCodes)]);

        return back()->with([
            'secret' => $secret,
            'qr_code_url' => $qrCodeUrl,
            'recovery_codes' => $recoveryCodes,
        ]);
    }

    public function confirm(TwoFactorRequest $request): RedirectResponse
    {
        $user = $request->user();

        if (! $this->twoFactorService->enable($user, $request->validated('code'))) {
            return back()->withErrors(['code' => __('auth.invalid_two_factor_code')]);
        }

        return back()->with('status', __('auth.two_factor_enabled'));
    }

    public function disable(Request $request): RedirectResponse
    {
        $this->twoFactorService->disable($request->user());

        return back()->with('status', __('auth.two_factor_disabled'));
    }

    public function recoveryCodes(Request $request): mixed
    {
        $codes = $this->twoFactorService->generateRecoveryCodes();
        $request->user()->update(['two_factor_recovery_codes' => json_encode($codes)]);

        return back()->with(['recovery_codes' => $codes]);
    }
}

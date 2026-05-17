<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\RedirectAuthenticatedUser;
use App\Actions\Auth\VerifyEmailAction;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function __construct(
        private readonly VerifyEmailAction $verifyEmailAction,
        private readonly RedirectAuthenticatedUser $redirectUser,
    ) {}

    public function notice(Request $request): mixed
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect()->intended($this->redirectUser->execute())
            : view('livewire.auth.verify-email');
    }

    public function verify(EmailVerificationRequest $request): RedirectResponse
    {
        $this->verifyEmailAction->execute($request->user());

        return redirect()->intended($this->redirectUser->execute())->with('status', __('auth.email_verified'));
    }

    public function resend(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended($this->redirectUser->execute());
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', __('auth.verification_sent'));
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Services\Auth\PasswordResetService;
use Illuminate\Http\RedirectResponse;

class PasswordResetController extends Controller
{
    public function __construct(private readonly PasswordResetService $passwordResetService) {}

    public function forgotPassword(ForgotPasswordRequest $request): RedirectResponse
    {
        $status = $this->passwordResetService->sendResetLink($request->validated('email'));

        return back()->with('status', $status);
    }

    public function reset(ResetPasswordRequest $request): RedirectResponse
    {
        $status = $this->passwordResetService->reset($request->validated());

        return redirect()->route('login')->with('status', $status);
    }
}

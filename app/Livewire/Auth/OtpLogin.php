<?php

namespace App\Livewire\Auth;

use App\Services\Auth\OtpService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.auth')]
class OtpLogin extends Component
{
    public string $email = '';

    public ?string $status = null;

    public string $code = '';

    public bool $showOtpInput = false;

    protected function rules(): array
    {
        return match (true) {
            $this->showOtpInput => [
                'code' => ['required', 'string', 'digits:6'],
            ],
            default => [
                'email' => ['required', 'string', 'email', 'max:255'],
            ],
        };
    }

    public function sendOtp(OtpService $service): void
    {
        $this->validate();

        $service->send(
            email: $this->email,
            ipAddress: request()->ip(),
            userAgent: request()->userAgent(),
        );

        $this->showOtpInput = true;
        $this->status = __('auth.otp_sent');
    }

    public function verifyOtp(OtpService $service): void
    {
        $this->validate();
        $this->showOtpInput = true;

        $user = $service->verify($this->email, $this->code);

        if (! $user) {
            $this->addError('code', __('auth.otp_invalid'));

            return;
        }

        if (! $user->isActive()) {
            $this->addError('code', __('auth.inactive'));

            return;
        }

        auth()->login($user);

        $this->redirectIntended(default: route('dashboard'));
    }

    public function back(): void
    {
        $this->showOtpInput = false;
        $this->status = null;
        $this->code = '';
        $this->resetValidation();
    }

    public function resendOtp(OtpService $service): void
    {
        $this->validateOnly('email');

        $service->send(
            email: $this->email,
            ipAddress: request()->ip(),
            userAgent: request()->userAgent(),
        );

        $this->status = __('auth.otp_sent');
    }

    public function render()
    {
        return view('livewire.auth.otp-login');
    }
}

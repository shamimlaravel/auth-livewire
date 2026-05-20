<?php

namespace App\Livewire\Auth;

use App\Enums\AuthChannel;
use App\Services\Auth\MultiChannelOtpService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.auth')]
class PhoneLogin extends Component
{
    public string $phone = '';

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
                'phone' => ['required', 'string', 'starts_with:+'],
            ],
        };
    }

    public function sendOtp(MultiChannelOtpService $otpService): void
    {
        $this->validate();

        $otpService->send($this->phone, AuthChannel::SMS);

        $this->showOtpInput = true;
        $this->status = __('auth.otp_sent_sms', ['phone' => $this->phone]);
    }

    public function verifyOtp(MultiChannelOtpService $otpService): void
    {
        $this->validate();
        $this->showOtpInput = true;

        $user = $otpService->verify($this->phone, $this->code, AuthChannel::SMS);

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
        $this->phone = '';
        $this->resetValidation();
    }

    public function resendOtp(MultiChannelOtpService $otpService): void
    {
        $this->validateOnly('phone');

        $otpService->send($this->phone, AuthChannel::SMS);

        $this->status = __('auth.otp_sent_sms', ['phone' => $this->phone]);
    }

    public function render()
    {
        return view('livewire.auth.phone-login');
    }
}

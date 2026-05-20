<?php

namespace App\Livewire\Auth;

use App\Enums\AuthChannel;
use App\Services\Auth\MultiChannelOtpService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.auth')]
class WhatsAppLogin extends Component
{
    public string $whatsapp_number = '';

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
                'whatsapp_number' => ['required', 'string', 'regex:/^\+?[\d\s-]{10,}$/'],
            ],
        };
    }

    public function sendOtp(MultiChannelOtpService $otpService): void
    {
        $this->validate();

        $otpService->send($this->whatsapp_number, AuthChannel::WhatsApp);

        $this->showOtpInput = true;
        $this->status = __('auth.otp_sent_whatsapp', ['phone' => $this->whatsapp_number]);
    }

    public function verifyOtp(MultiChannelOtpService $otpService): void
    {
        $this->validate();
        $this->showOtpInput = true;

        $user = $otpService->verify($this->whatsapp_number, $this->code, AuthChannel::WhatsApp);

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
        $this->whatsapp_number = '';
        $this->resetValidation();
    }

    public function resendOtp(MultiChannelOtpService $otpService): void
    {
        $this->validateOnly('whatsapp_number');

        $otpService->send($this->whatsapp_number, AuthChannel::WhatsApp);

        $this->status = __('auth.otp_sent_whatsapp', ['phone' => $this->whatsapp_number]);
    }

    public function render()
    {
        return view('livewire.auth.whatsapp-login');
    }
}

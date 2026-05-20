<?php

namespace App\Livewire\Auth;

use App\Enums\AuthChannel;
use App\Services\Auth\MultiChannelOtpService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.auth')]
class TelegramLogin extends Component
{
    public string $telegram_chat_id = '';

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
                'telegram_chat_id' => ['required', 'string'],
            ],
        };
    }

    public function sendOtp(MultiChannelOtpService $otpService): void
    {
        $this->validate();

        $otpService->send($this->telegram_chat_id, AuthChannel::Telegram);

        $this->showOtpInput = true;
        $this->status = __('auth.otp_sent_telegram', ['chat_id' => $this->telegram_chat_id]);
    }

    public function verifyOtp(MultiChannelOtpService $otpService): void
    {
        $this->validate();
        $this->showOtpInput = true;

        $user = $otpService->verify($this->telegram_chat_id, $this->code, AuthChannel::Telegram);

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
        $this->telegram_chat_id = '';
        $this->resetValidation();
    }

    public function resendOtp(MultiChannelOtpService $otpService): void
    {
        $this->validateOnly('telegram_chat_id');

        $otpService->send($this->telegram_chat_id, AuthChannel::Telegram);

        $this->status = __('auth.otp_sent_telegram', ['chat_id' => $this->telegram_chat_id]);
    }

    public function render()
    {
        return view('livewire.auth.telegram-login');
    }
}

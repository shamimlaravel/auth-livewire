<?php

namespace App\Livewire\Auth;

use App\Actions\Auth\RedirectAuthenticatedUser;
use App\Enums\AuthChannel;
use App\Models\OtpToken;
use App\Models\User;
use App\Services\Auth\MultiChannelOtpService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.auth')]
class TwoFactorChallenge extends Component
{
    public string $code = '';

    public ?string $twoFactorChannel = null;

    public function mount(): void
    {
        $userId = session('two_factor_user_id');

        if (! $userId) {
            $this->redirect(route('login'));

            return;
        }

        $user = User::find($userId);

        if (! $user) {
            $this->redirect(route('login'));

            return;
        }

        $channel = $user->two_factor_channel ?? AuthChannel::SMS->value;
        $this->twoFactorChannel = $channel;

        $target = match ($channel) {
            'sms' => $user->phone,
            'whatsapp' => $user->whatsapp_number,
            'telegram' => $user->telegram_chat_id,
            default => $user->email,
        };

        if ($target) {
            $service = app(MultiChannelOtpService::class);

            $existingToken = OtpToken::where('identifiable', $target)
                ->where('channel', $channel)
                ->whereNull('used_at')
                ->where('expires_at', '>', now())
                ->first();

            if (! $existingToken) {
                $service->send($target, $channel);
            }
        }
    }

    public function submit(MultiChannelOtpService $otpService): void
    {
        $this->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $userId = session('two_factor_user_id');

        if (! $userId) {
            $this->redirect(route('login'));

            return;
        }

        $user = User::find($userId);

        if (! $user) {
            $this->redirect(route('login'));

            return;
        }

        $channel = $this->twoFactorChannel ?? AuthChannel::SMS->value;

        $target = match ($channel) {
            'sms' => $user->phone,
            'whatsapp' => $user->whatsapp_number,
            'telegram' => $user->telegram_chat_id,
            default => $user->email,
        };

        if (! $target) {
            $this->addError('code', __('auth.invalid_two_factor_code'));

            return;
        }

        $verifiedUser = $otpService->verify($target, $this->code, $channel);

        if (! $verifiedUser) {
            $this->addError('code', __('auth.invalid_two_factor_code'));

            return;
        }

        auth()->login($user);
        session()->forget('two_factor_user_id');
        session()->put('two_factor_authenticated', true);

        $this->redirectToDashboard();
    }

    private function redirectToDashboard(): void
    {
        $this->redirect(
            session()->pull('url.intended', app(RedirectAuthenticatedUser::class)->execute()),
        );
    }

    public function render(): View
    {
        return view('livewire.auth.two-factor-challenge');
    }
}

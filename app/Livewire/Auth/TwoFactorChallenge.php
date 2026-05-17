<?php

namespace App\Livewire\Auth;

use App\Actions\Auth\RedirectAuthenticatedUser;
use App\Models\User;
use App\Services\Auth\TwoFactorService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.auth')]
class TwoFactorChallenge extends Component
{
    public string $code = '';

    public function submit(TwoFactorService $service): void
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

        if (! $user || ! $service->verify($user->two_factor_secret, $this->code)) {
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

    public function render()
    {
        return view('livewire.auth.two-factor-challenge');
    }
}

<?php

namespace App\Livewire\Auth;

use App\Actions\Auth\RedirectAuthenticatedUser;
use App\DTOs\Auth\LoginDTO;
use App\Models\User;
use App\Services\Auth\AuthenticationService;
use App\Services\Auth\TwoFactorService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.auth')]
class Login extends Component
{
    public string $login = '';

    public string $password = '';

    public bool $remember = false;

    public bool $showTwoFactor = false;

    public string $twoFactorCode = '';

    protected function rules(): array
    {
        return [
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    public function submit(AuthenticationService $authService): void
    {
        $this->validate();

        $user = $authService->login(new LoginDTO(
            login: $this->login,
            password: $this->password,
            remember: $this->remember,
            ipAddress: request()->ip(),
            userAgent: request()->userAgent(),
        ));

        if ($user->hasTwoFactorEnabled()) {
            $this->showTwoFactor = true;
            session(['two_factor_user_id' => $user->id]);

            return;
        }

        Auth::login($user, $this->remember);
        $this->redirectToDashboard();
    }

    public function verifyTwoFactor(): void
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

        $service = app(TwoFactorService::class);
        if (! $service->verify($user->two_factor_secret, $this->twoFactorCode)) {
            $this->addError('twoFactorCode', __('auth.invalid_two_factor_code'));

            return;
        }

        Auth::login($user, $this->remember);
        session()->forget('two_factor_user_id');
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
        return view('livewire.auth.login');
    }
}

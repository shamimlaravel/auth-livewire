<?php

namespace App\Livewire\Auth;

use App\Actions\Auth\RedirectAuthenticatedUser;
use App\DTOs\Auth\LoginDTO;
use App\Enums\AuthChannel;
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

    public ?string $otpChannel = 'email';

    public bool $showChannelSelector = false;

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

    /**
     * Trigger multi-channel OTP login from the main login page.
     * Stores the login credential in the session and redirects to
     * the appropriate OTP component via the `login.proceed` route.
     */
    public function initiateOtpLogin(string $channel): void
    {
        $this->startOtpLogin($channel, $this->login);
    }

    /**
     * Start OTP login from any Livewire component — store the identifiable
     * in the temp session key and navigate to the OTP login blade.
     */
    protected function startOtpLogin(string $channel, string $target): void
    {
        session(['auth.otp.channel' => $channel]);
        session(['auth.otp.target' => $target]);

        $this->redirect(route('login.otp'));
    }

    private function redirectToDashboard(): void
    {
        $this->redirect(
            session()->pull('url.intended', app(RedirectAuthenticatedUser::class)->execute()),
        );
    }

    public function render()
    {
        return view('livewire.auth.login', [
            'channels' => AuthChannel::loginChannels(),
        ]);
    }
}

<?php

namespace App\Livewire\User;

use App\Rules\CurrentPassword;
use App\Services\Auth\TwoFactorService;
use Livewire\Component;

class AccountSettings extends Component
{
    public string $name = '';

    public string $email = '';

    public ?string $phone = null;

    public string $currentPassword = '';

    public string $newPassword = '';

    public string $newPasswordConfirmation = '';

    public ?string $twoFactorSecret = null;

    public ?string $qrCodeUrl = null;

    public array $recoveryCodes = [];

    public string $twoFactorCode = '';

    public string $confirmCode = '';

    public function mount(): void
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
    }

    public function updateProfile(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,'.auth()->id()],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        auth()->user()->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ]);

        session()->flash('status', __('Profile updated.'));
    }

    public function updatePassword(): void
    {
        $this->validate([
            'currentPassword' => ['required', new CurrentPassword],
            'newPassword' => ['required', 'string', 'min:8', 'confirmed:newPasswordConfirmation'],
        ]);

        auth()->user()->update([
            'password' => bcrypt($this->newPassword),
        ]);

        $this->currentPassword = '';
        $this->newPassword = '';
        $this->newPasswordConfirmation = '';

        session()->flash('status', __('Password updated.'));
    }

    public function setupTwoFactor(TwoFactorService $service): void
    {
        $user = auth()->user();

        if ($user->hasTwoFactorEnabled()) {
            session()->flash('error', __('2FA is already enabled.'));

            return;
        }

        $secret = $service->generateSecretKey();
        $this->twoFactorSecret = $secret;
        $this->qrCodeUrl = $service->getQrCodeUrl(config('app.name'), $user->email, $secret);
        $this->recoveryCodes = $service->generateRecoveryCodes();

        $user->update([
            'two_factor_secret' => $secret,
            'two_factor_recovery_codes' => json_encode($this->recoveryCodes),
        ]);
    }

    public function confirmTwoFactor(TwoFactorService $service): void
    {
        $this->validate(['confirmCode' => ['required', 'string', 'size:6']]);

        if (! $service->enable(auth()->user(), $this->confirmCode)) {
            $this->addError('confirmCode', __('auth.invalid_two_factor_code'));

            return;
        }

        $this->twoFactorSecret = null;
        $this->qrCodeUrl = null;
        $this->confirmCode = '';
        session()->flash('status', __('auth.two_factor_enabled'));
    }

    public function disableTwoFactor(TwoFactorService $service): void
    {
        $service->disable(auth()->user());
        $this->twoFactorSecret = null;
        $this->qrCodeUrl = null;
        session()->flash('status', __('auth.two_factor_disabled'));
    }

    public function generateRecoveryCodes(TwoFactorService $service): void
    {
        $codes = $service->generateRecoveryCodes();
        auth()->user()->update(['two_factor_recovery_codes' => json_encode($codes)]);
        $this->recoveryCodes = $codes;
        session()->flash('status', __('auth.recovery_codes_generated'));
    }

    public function render()
    {
        $user = auth()->user();

        return view('livewire.user.account-settings', [
            'socialAccounts' => $user->socialAccounts,
            'hasTwoFactor' => $user->hasTwoFactorEnabled(),
            'storedRecoveryCodes' => $user->two_factor_recovery_codes ? json_decode($user->two_factor_recovery_codes, true) : [],
        ])->layout('components.user.app');
    }
}

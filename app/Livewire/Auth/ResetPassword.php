<?php

namespace App\Livewire\Auth;

use App\Services\Auth\PasswordResetService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.auth')]
class ResetPassword extends Component
{
    public string $token = '';

    public string $email = '';

    public string $password = '';

    public string $passwordConfirmation = '';

    public ?string $status = null;

    public function mount(string $token): void
    {
        $this->token = $token;
    }

    protected function rules(): array
    {
        return [
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8'],
            'passwordConfirmation' => ['required', 'same:password'],
        ];
    }

    public function submit(PasswordResetService $service): void
    {
        $this->validate();

        $this->status = $service->reset([
            'token' => $this->token,
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->passwordConfirmation,
        ]);

        if ($this->status) {
            $this->redirect(route('login'));
        }
    }

    public function render()
    {
        return view('livewire.auth.reset-password');
    }
}

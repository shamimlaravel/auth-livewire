<?php

namespace App\Livewire\Auth;

use App\Services\Auth\PasswordResetService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.auth')]
class ForgotPassword extends Component
{
    public string $email = '';

    public ?string $status = null;

    protected function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
        ];
    }

    public function submit(PasswordResetService $service): void
    {
        $this->validate();

        $this->status = $service->sendResetLink($this->email);
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}

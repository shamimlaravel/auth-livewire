<?php

namespace App\Livewire\Auth;

use App\Services\Auth\MagicLinkService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.auth')]
class MagicLinkRequest extends Component
{
    public string $email = '';

    public ?string $status = null;

    protected function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
        ];
    }

    public function submit(MagicLinkService $service): void
    {
        $this->validate();

        $service->send(
            email: $this->email,
            ipAddress: request()->ip(),
            userAgent: request()->userAgent(),
        );

        $this->status = __('auth.magic_link_sent');
    }

    public function render()
    {
        return view('livewire.auth.magic-link-request');
    }
}

<?php

namespace App\Livewire\Auth;

use App\Actions\Auth\SendMagicLinkAction;
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

    public function submit(SendMagicLinkAction $action): void
    {
        $this->validate();

        $action->execute(
            email: $this->email,
            ip: request()->ip(),
            ua: request()->userAgent(),
        );

        $this->status = __('auth.magic_link_sent');
    }

    public function render()
    {
        return view('livewire.auth.magic-link-request');
    }
}

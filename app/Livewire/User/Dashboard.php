<?php

namespace App\Livewire\User;

use App\Models\LoginAttempt;
use Livewire\Component;

class Dashboard extends Component
{
    public ?int $failedLoginCount = null;

    public bool $twoFactorEnabled = false;

    public function mount(): void
    {
        $user = auth()->user();

        $this->twoFactorEnabled = $user->hasTwoFactorEnabled();

        if ($user->hasRole('admin')) {
            $this->failedLoginCount = LoginAttempt::where('status', 'failed')
                ->where('attempted_at', '>=', now()->subDay())
                ->count();
        }
    }

    public function render()
    {
        return view('livewire.user.dashboard')
            ->layout('components.user.app');
    }
}

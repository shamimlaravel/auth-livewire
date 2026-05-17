<?php

namespace App\Livewire\Admin;

use App\Models\AuditLog;
use App\Models\LoginAttempt;
use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    public int $totalUsers;

    public int $totalFailedLogins;

    public int $totalAuditEvents;

    public array $recentUsers;

    public array $recentAuditLogs;

    public function mount(): void
    {
        $this->totalUsers = User::count();
        $this->totalFailedLogins = LoginAttempt::where('status', 'failed')
            ->where('attempted_at', '>=', now()->subDay())
            ->count();
        $this->totalAuditEvents = AuditLog::where('created_at', '>=', now()->subDay())->count();
        $this->recentUsers = User::latest()->take(5)->get()->toArray();
        $this->recentAuditLogs = AuditLog::with('user')
            ->latest('created_at')
            ->take(10)
            ->get()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.admin.dashboard')
            ->layout('components.admin.app');
    }
}

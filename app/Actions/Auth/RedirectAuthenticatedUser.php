<?php

namespace App\Actions\Auth;

use App\Models\User;

class RedirectAuthenticatedUser
{
    public function execute(?User $user = null): string
    {
        $user ??= auth()->user();

        return match (true) {
            $user->hasRole('admin') => route('admin.dashboard'),
            $user->hasRole('seller') || $user->hasRole('reseller') => route('seller.dashboard'),
            default => route('dashboard'),
        };
    }
}

<div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
    @auth
        @if (auth()->user()->hasRole('admin'))
            <div class="mb-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-lg border border-gray-200 bg-white p-6">
                    <p class="text-sm font-medium text-gray-600">{{ __('Failed Logins (24h)') }}</p>
                    <p class="mt-2 text-3xl font-bold text-red-600">{{ $failedLoginCount ?? 0 }}</p>
                </div>
                <a href="{{ route('admin.users') }}" wire:navigate
                   class="flex items-center justify-center rounded-lg border border-indigo-200 bg-indigo-50 p-6 text-indigo-700 hover:bg-indigo-100">
                    <span class="text-lg font-semibold">{{ __('Manage Users') }} &rarr;</span>
                </a>
                <a href="{{ route('admin.roles') }}" wire:navigate
                   class="flex items-center justify-center rounded-lg border border-gray-200 bg-white p-6 text-gray-700 hover:bg-gray-50">
                    <span class="text-lg font-semibold">{{ __('Manage Roles') }} &rarr;</span>
                </a>
            </div>
        @endif

        @if (auth()->user()->hasRole('seller'))
            <div class="mb-8 grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div class="rounded-lg border border-gray-200 bg-white p-6">
                    <p class="text-sm font-medium text-gray-600">{{ __('Products') }}</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">--</p>
                    <p class="mt-1 text-xs text-gray-500">{{ __('Product management coming soon.') }}</p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-white p-6">
                    <p class="text-sm font-medium text-gray-600">{{ __('Orders') }}</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">--</p>
                    <p class="mt-1 text-xs text-gray-500">{{ __('Order management coming soon.') }}</p>
                </div>
            </div>
        @endif

        @if (auth()->user()->hasRole('support_agent'))
            <div class="mb-8 grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div class="rounded-lg border border-gray-200 bg-white p-6">
                    <p class="text-sm font-medium text-gray-600">{{ __('Open Tickets') }}</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">--</p>
                    <p class="mt-1 text-xs text-gray-500">{{ __('Ticket management coming soon.') }}</p>
                </div>
            </div>
        @endif

        <div class="rounded-lg border border-gray-200 bg-white p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900">
                        {{ __('Welcome, :name!', ['name' => auth()->user()->name]) }}
                    </h1>
                    <p class="mt-1 text-gray-600">{{ __('You are logged in as :role.', ['role' => auth()->user()->roles->first()?->name ?? 'user']) }}</p>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div class="rounded-lg border border-gray-100 bg-gray-50 p-4">
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Email') }}</p>
                    <p class="mt-1 text-sm text-gray-900">{{ auth()->user()->email }}</p>
                </div>
                <div class="rounded-lg border border-gray-100 bg-gray-50 p-4">
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Email Verified') }}</p>
                    <p class="mt-1 text-sm text-gray-900">
                        @if (auth()->user()->hasVerifiedEmail())
                            <span class="text-green-600">{{ __('Yes') }}</span>
                        @else
                            <span class="text-yellow-600">{{ __('No') }}</span>
                        @endif
                    </p>
                </div>
                <div class="rounded-lg border border-gray-100 bg-gray-50 p-4">
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('2FA') }}</p>
                    <p class="mt-1 text-sm text-gray-900">
                        @if ($twoFactorEnabled)
                            <span class="text-green-600">{{ __('Enabled') }}</span>
                        @else
                            <span class="text-gray-600">{{ __('Disabled') }}</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @endauth
</div>

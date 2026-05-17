<div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ __('Admin Dashboard') }}</h1>
    <p class="mt-2 text-sm text-gray-600">{{ __('Overview of your application.') }}</p>

    <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <div class="rounded-lg border border-gray-200 bg-white p-6">
            <p class="text-sm font-medium text-gray-600">{{ __('Total Users') }}</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $totalUsers }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-6">
            <p class="text-sm font-medium text-gray-600">{{ __('Failed Logins (24h)') }}</p>
            <p class="mt-2 text-3xl font-bold text-red-600">{{ $totalFailedLogins }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-6">
            <p class="text-sm font-medium text-gray-600">{{ __('Audit Events (24h)') }}</p>
            <p class="mt-2 text-3xl font-bold text-indigo-600">{{ $totalAuditEvents }}</p>
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-2">
        <div class="rounded-lg border border-gray-200 bg-white">
            <div class="border-b border-gray-200 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-900">{{ __('Recent Users') }}</h2>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse ($recentUsers as $u)
                    <div class="flex items-center justify-between px-6 py-3">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $u['name'] }}</p>
                            <p class="text-sm text-gray-600">{{ $u['email'] }}</p>
                        </div>
                        <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($u['created_at'])->diffForHumans() }}</span>
                    </div>
                @empty
                    <p class="px-6 py-4 text-sm text-gray-600">{{ __('No users yet.') }}</p>
                @endforelse
            </div>
        </div>

        <div class="rounded-lg border border-gray-200 bg-white">
            <div class="border-b border-gray-200 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-900">{{ __('Recent Audit Events') }}</h2>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse ($recentAuditLogs as $log)
                    <div class="px-6 py-3">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900">{{ $log['event_type'] }}</p>
                            <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($log['created_at'])->diffForHumans() }}</span>
                        </div>
                        @if ($log['user'])
                            <p class="text-xs text-gray-600">{{ $log['user']['name'] ?? 'System' }}</p>
                        @endif
                    </div>
                @empty
                    <p class="px-6 py-4 text-sm text-gray-600">{{ __('No audit events yet.') }}</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="mt-8 flex gap-4">
        <a href="{{ route('admin.users') }}" wire:navigate
           class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
            {{ __('Manage Users') }}
        </a>
        <a href="{{ route('admin.roles') }}" wire:navigate
           class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            {{ __('Manage Roles') }}
        </a>
    </div>
</div>

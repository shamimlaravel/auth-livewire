<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-surface-900 dark:text-white">{{ __('Admin Dashboard') }}</h1>
        <p class="mt-1 text-sm text-surface-500 dark:text-surface-400">{{ __('Overview of your application.') }}</p>
    </div>

    {{-- Stats cards --}}
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <div class="relative rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 p-6 transition-all duration-200 hover:shadow-md">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400">
                    <svg class="size-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-surface-500 dark:text-surface-400">{{ __('Total Users') }}</p>
                    <p class="text-2xl font-bold text-surface-900 dark:text-white">{{ $totalUsers }}</p>
                </div>
            </div>
        </div>
        <div class="relative rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 p-6 transition-all duration-200 hover:shadow-md">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-error-50 dark:bg-error-500/10 text-error-600 dark:text-error-400">
                    <svg class="size-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-surface-500 dark:text-surface-400">{{ __('Failed Logins (24h)') }}</p>
                    <p class="text-2xl font-bold text-error-600 dark:text-error-400">{{ $totalFailedLogins }}</p>
                </div>
            </div>
        </div>
        <div class="relative rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 p-6 transition-all duration-200 hover:shadow-md">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-success-50 dark:bg-success-500/10 text-success-600 dark:text-success-400">
                    <svg class="size-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-surface-500 dark:text-surface-400">{{ __('Audit Events (24h)') }}</p>
                    <p class="text-2xl font-bold text-surface-900 dark:text-white">{{ $totalAuditEvents }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Activity sections --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 overflow-hidden">
            <div class="border-b border-surface-200 dark:border-surface-700 px-6 py-4">
                <h2 class="text-base font-semibold text-surface-900 dark:text-white">{{ __('Recent Users') }}</h2>
            </div>
            <div class="divide-y divide-surface-200 dark:divide-surface-700">
                @forelse ($recentUsers as $u)
                    <div class="flex items-center justify-between px-6 py-3.5 hover:bg-surface-50 dark:hover:bg-surface-700/30">
                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-brand-100 dark:bg-brand-500/20 text-xs font-bold text-brand-700 dark:text-brand-300">
                                {{ substr($u['name'], 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-surface-900 dark:text-white">{{ $u['name'] }}</p>
                                <p class="text-xs text-surface-500 dark:text-surface-400">{{ $u['email'] }}</p>
                            </div>
                        </div>
                        <span class="text-xs text-surface-400 dark:text-surface-500">{{ \Carbon\Carbon::parse($u['created_at'])->diffForHumans() }}</span>
                    </div>
                @empty
                    <p class="px-6 py-4 text-sm text-surface-500 dark:text-surface-400">{{ __('No users yet.') }}</p>
                @endforelse
            </div>
        </div>

        <div class="rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 overflow-hidden">
            <div class="border-b border-surface-200 dark:border-surface-700 px-6 py-4">
                <h2 class="text-base font-semibold text-surface-900 dark:text-white">{{ __('Recent Audit Events') }}</h2>
            </div>
            <div class="divide-y divide-surface-200 dark:divide-surface-700">
                @forelse ($recentAuditLogs as $log)
                    <div class="px-6 py-3.5 hover:bg-surface-50 dark:hover:bg-surface-700/30">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center rounded-full bg-brand-50 dark:bg-brand-500/10 px-2 py-0.5 text-xs font-medium text-brand-700 dark:text-brand-300">
                                    {{ $log['event_type'] }}
                                </span>
                            </div>
                            <span class="text-xs text-surface-400 dark:text-surface-500">{{ \Carbon\Carbon::parse($log['created_at'])->diffForHumans() }}</span>
                        </div>
                        @if ($log['user'])
                            <p class="mt-1 text-xs text-surface-500 dark:text-surface-400">{{ $log['user']['name'] ?? 'System' }}</p>
                        @endif
                    </div>
                @empty
                    <p class="px-6 py-4 text-sm text-surface-500 dark:text-surface-400">{{ __('No audit events yet.') }}</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Quick actions --}}
    <div class="flex flex-wrap gap-3">
        <a href="{{ route('admin.users') }}" wire:navigate
           class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-brand-500 to-brand-b-500 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-500/25 hover:brightness-110 transition-all duration-200">
            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
            </svg>
            {{ __('Manage Users') }}
        </a>
        <a href="{{ route('admin.roles') }}" wire:navigate
           class="inline-flex items-center gap-2 rounded-xl border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 px-5 py-2.5 text-sm font-medium text-surface-700 dark:text-surface-300 hover:bg-surface-50 dark:hover:bg-surface-700 transition-all duration-200">
            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
            </svg>
            {{ __('Manage Roles') }}
        </a>
    </div>
</div>

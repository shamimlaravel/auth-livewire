<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - {{ __('Admin') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface-50 antialiased">
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside class="fixed left-0 top-0 z-30 flex h-full w-64 flex-col bg-surface-900">
            <div class="flex h-16 items-center justify-center border-b border-white/10">
                <span class="text-lg font-bold text-white">{{ config('app.name') }}</span>
            </div>
            <nav class="flex-1 space-y-1 overflow-y-auto p-4">
                <a href="{{ route('admin.dashboard') }}" wire:navigate
                   class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-surface-300 hover:bg-white/10 hover:text-white {{ request()->routeIs('admin.dashboard') ? 'bg-brand-500/20 text-white' : '' }}">
                    {{ __('Dashboard') }}
                </a>
                <a href="{{ route('admin.users') }}" wire:navigate
                   class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-surface-300 hover:bg-white/10 hover:text-white {{ request()->routeIs('admin.users*') ? 'bg-brand-500/20 text-white' : '' }}">
                    {{ __('Users') }}
                </a>
                <a href="{{ route('admin.roles') }}" wire:navigate
                   class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-surface-300 hover:bg-white/10 hover:text-white {{ request()->routeIs('admin.roles*') ? 'bg-brand-500/20 text-white' : '' }}">
                    {{ __('Roles') }}
                </a>
            </nav>
            <div class="border-t border-white/10 p-4">
                <a href="{{ route('dashboard') }}" wire:navigate
                   class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-surface-400 hover:text-white">
                    &larr; {{ __('Back to Dashboard') }}
                </a>
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit"
                            class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-surface-400 hover:text-white">
                        {{ __('Logout') }}
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main content --}}
        <div class="flex flex-1 flex-col pl-64">
            <header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-surface-200 bg-white px-6">
                <h1 class="text-lg font-semibold text-surface-900">{{ $header ?? __('Admin Panel') }}</h1>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-surface-600">{{ auth()->user()?->name }}</span>
                </div>
            </header>
            <main class="flex-1 p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>

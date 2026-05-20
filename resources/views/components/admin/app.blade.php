<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ sidebarOpen: false, dark: $store.theme.dark }"
      :class="{ 'dark': dark }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - {{ __('Admin') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface-50 dark:bg-surface-900 antialiased">
    <div class="flex min-h-screen">
        {{-- Mobile overlay --}}
        <div x-show="sidebarOpen" x-cloak
             @@click="sidebarOpen = false"
             class="fixed inset-0 z-30 bg-black/50 lg:hidden"></div>

        {{-- Sidebar --}}
        <aside class="fixed left-0 top-0 z-40 flex h-full w-64 flex-col bg-gradient-to-b from-surface-900 to-surface-800 dark:from-surface-950 dark:to-surface-900 transition-transform duration-300 lg:translate-x-0"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

            {{-- Logo --}}
            <div class="flex h-16 shrink-0 items-center justify-between border-b border-white/10 px-6">
                <span class="text-lg font-bold tracking-tight text-white">{{ config('app.name') }}</span>
                <button @@click="sidebarOpen = false" class="text-white/60 hover:text-white lg:hidden">
                    <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 space-y-1 overflow-y-auto p-4">
                {{-- Main navigation --}}
                <p class="px-3 text-xs font-semibold uppercase tracking-wider text-white/40 mb-2">Main</p>

                <a href="{{ route('admin.dashboard') }}" wire:navigate
                   class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-brand-500 to-brand-b-500 text-white shadow-lg shadow-brand-500/25' : 'text-surface-300 hover:bg-white/10 hover:text-white' }}">
                    <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955a1.126 1.126 0 0 1 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    {{ __('Dashboard') }}
                </a>

                <a href="{{ route('admin.users') }}" wire:navigate
                   class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.users*') ? 'bg-gradient-to-r from-brand-500 to-brand-b-500 text-white shadow-lg shadow-brand-500/25' : 'text-surface-300 hover:bg-white/10 hover:text-white' }}">
                    <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                    {{ __('Users') }}
                </a>

                <a href="{{ route('admin.roles') }}" wire:navigate
                   class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.roles*') ? 'bg-gradient-to-r from-brand-500 to-brand-b-500 text-white shadow-lg shadow-brand-500/25' : 'text-surface-300 hover:bg-white/10 hover:text-white' }}">
                    <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                    </svg>
                    {{ __('Roles') }}
                </a>

                {{-- System --}}
                <p class="px-3 text-xs font-semibold uppercase tracking-wider text-white/40 mb-2 mt-6">System</p>

                <a href="{{ route('admin.auth.config') }}" wire:navigate
                   class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.auth.config*') ? 'bg-gradient-to-r from-brand-500 to-brand-b-500 text-white shadow-lg shadow-brand-500/25' : 'text-surface-300 hover:bg-white/10 hover:text-white' }}">
                    <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    {{ __('Auth Config') }}
                </a>

                <a href="#" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-surface-300 hover:bg-white/10 hover:text-white transition-all duration-200">
                    <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    {{ __('Activity Logs') }}
                </a>

                <a href="#" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-surface-300 hover:bg-white/10 hover:text-white transition-all duration-200">
                    <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    {{ __('Settings') }}
                </a>

                <a href="#" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-surface-300 hover:bg-white/10 hover:text-white transition-all duration-200">
                    <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                    </svg>
                    {{ __('Backups') }}
                </a>
            </nav>

            {{-- User profile card at bottom --}}
            <div class="border-t border-white/10 p-4">
                <div class="flex items-center gap-3 rounded-xl bg-white/5 px-3 py-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-brand-400 to-brand-b-500 text-xs font-bold text-white">
                        {{ substr(auth()->user()?->name ?? 'A', 0, 2) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ auth()->user()?->name }}</p>
                        <p class="text-xs text-white/60 truncate">{{ auth()->user()?->email }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-2 rounded-xl px-3 py-2 text-sm font-medium text-surface-400 hover:bg-white/5 hover:text-white transition-colors">
                        <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                        </svg>
                        {{ __('Logout') }}
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main content --}}
        <div class="flex flex-1 flex-col lg:pl-64 min-w-0">
            {{-- Header --}}
            <header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-surface-200 dark:border-surface-700 bg-white/80 dark:bg-surface-800/80 backdrop-blur-lg px-4 sm:px-6">
                <div class="flex items-center gap-4">
                    <button @@click="sidebarOpen = true" class="text-surface-500 hover:text-surface-700 dark:text-surface-400 dark:hover:text-white lg:hidden">
                        <svg class="size-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                    <div>
                        <h1 class="text-lg font-semibold text-surface-900 dark:text-white">{{ $header ?? __('Admin Panel') }}</h1>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    {{-- Theme toggle --}}
                    <button @@click="$store.theme.toggle()"
                            class="flex h-9 w-9 items-center justify-center rounded-xl text-surface-500 hover:bg-surface-100 dark:text-surface-400 dark:hover:bg-surface-700 transition-colors"
                            :title="dark ? 'Switch to light mode' : 'Switch to dark mode'">
                        <svg x-show="!dark" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.75A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                        </svg>
                        <svg x-show="dark" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                        </svg>
                    </button>

                    {{-- Notifications --}}
                    <button class="relative flex h-9 w-9 items-center justify-center rounded-xl text-surface-500 hover:bg-surface-100 dark:text-surface-400 dark:hover:bg-surface-700 transition-colors">
                        <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                        </svg>
                        <span class="absolute right-2 top-2 h-2 w-2 rounded-full bg-error-500"></span>
                    </button>

                    {{-- User dropdown --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @@click="open = !open" @@click.away="open = false" type="button" class="flex items-center gap-2 rounded-xl px-3 py-1.5 text-sm font-medium text-surface-700 dark:text-surface-300 hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors">
                            <div class="flex h-7 w-7 items-center justify-center rounded-full bg-gradient-to-br from-brand-400 to-brand-b-500 text-xs font-bold text-white">
                                {{ substr(auth()->user()?->name ?? 'A', 0, 1) }}
                            </div>
                            <span class="hidden sm:inline">{{ auth()->user()?->name }}</span>
                            <svg class="size-4 text-surface-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>

                        <div x-show="open" x-cloak
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 z-50 mt-2 w-56 origin-top-right rounded-xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 py-1 shadow-xl"
                             @@click="open = false">

                            <div class="border-b border-surface-200 dark:border-surface-700 px-4 py-3">
                                <p class="text-sm font-medium text-surface-900 dark:text-white">{{ auth()->user()?->name }}</p>
                                <p class="text-xs text-surface-500 dark:text-surface-400">{{ auth()->user()?->email }}</p>
                            </div>

                            <a href="{{ route('settings') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 text-sm text-surface-700 dark:text-surface-300 hover:bg-surface-50 dark:hover:bg-surface-700/50">
                                <svg class="size-4 text-surface-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                {{ __('Settings') }}
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex w-full items-center gap-3 px-4 py-2.5 text-sm text-surface-700 dark:text-surface-300 hover:bg-surface-50 dark:hover:bg-surface-700/50">
                                    <svg class="size-4 text-surface-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                                    </svg>
                                    {{ __('Logout') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Page content --}}
            <main class="flex-1">
                {{ $slot }}
            </main>
        </div>
    </div>

    {{-- Toast container --}}
    <div x-data="{ toast: { show: false, type: 'success', message: '' } }"
         @@toast.window="toast = { show: true, type: $event.detail.type, message: $event.detail.message }; setTimeout(() => toast.show = false, 4000)"
         class="fixed bottom-6 right-6 z-50">
        <template x-teleport="body">
            <div x-show="toast.show" x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-4"
                 class="flex items-center gap-3 rounded-xl px-5 py-4 shadow-2xl border"
                 :class="toast.type === 'success' ? 'bg-success-50 dark:bg-success-500/10 border-success-200 dark:border-success-500/20' : 'bg-error-50 dark:bg-error-500/10 border-error-200 dark:border-error-500/20'">
                <template x-if="toast.type === 'success'">
                    <svg class="size-5 text-success-500 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </template>
                <template x-if="toast.type === 'error'">
                    <svg class="size-5 text-error-500 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                </template>
                <p class="text-sm font-medium" x-text="toast.message"
                   :class="toast.type === 'success' ? 'text-success-800 dark:text-success-300' : 'text-error-800 dark:text-error-300'"></p>
                <button @@click="toast.show = false" class="shrink-0 p-1">
                    <svg class="size-4" :class="toast.type === 'success' ? 'text-success-600' : 'text-error-600'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </template>
    </div>
</body>
</html>

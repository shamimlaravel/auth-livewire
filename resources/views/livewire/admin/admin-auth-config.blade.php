<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 space-y-6"
     x-data="{ changed: false }"
     x-init="$wire.on('toast', () => changed = false)">

    {{-- ════════════════════════════════════════════════════════════════════════════
         Header
         ════════════════════════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-surface-900 dark:text-white">
                Authentication Configuration
            </h1>
            <p class="mt-1 text-sm text-surface-500 dark:text-surface-400">
                Manage OTP channels, two-factor settings, provider credentials, and security limits.
            </p>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <button type="button" wire:click="resetToDefaults"
                class="inline-flex items-center gap-2 rounded-xl border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 px-4 py-2.5 text-sm font-medium text-surface-700 dark:text-surface-300 hover:bg-surface-50 dark:hover:bg-surface-700 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-400 focus-visible:ring-offset-2 focus-visible:ring-offset-surface-50 dark:focus-visible:ring-offset-surface-900">
                <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182" />
                </svg>
                Reset to defaults
            </button>
            <button type="button" wire:click="save" wire:loading.attr="disabled"
                class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-brand-500 to-brand-b-500 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-500/25 hover:brightness-110 transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-400 focus-visible:ring-offset-2 focus-visible:ring-offset-surface-50 dark:focus-visible:ring-offset-surface-900 active:brightness-95 min-h-[44px] disabled:opacity-50">
                <span wire:loading.remove wire:target="save">
                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                </span>
                <svg wire:loading wire:target="save" class="size-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                <span wire:loading.remove wire:target="save">Save changes</span>
                <span wire:loading wire:target="save">Saving...</span>
            </button>
        </div>
    </div>

    {{-- ── Active channels summary ────────────────────────────────────────────────── --}}
    <div class="flex flex-wrap items-center gap-3 rounded-xl bg-white dark:bg-surface-800 border border-surface-200 dark:border-surface-700 px-5 py-3">
        <span class="text-sm font-medium text-surface-700 dark:text-surface-300">Active channels:</span>
        @foreach ($channelToggles as $channel => $enabled)
            <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-medium
                {{ $enabled ? 'bg-success-50 text-success-700 dark:bg-success-500/10 dark:text-success-400 ring-1 ring-success-200 dark:ring-success-500/20' : 'bg-surface-100 text-surface-500 dark:bg-surface-700 dark:text-surface-400' }}">
                @if ($enabled)
                    <svg class="size-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                @endif
                {{ ucfirst($channel) }}
            </span>
        @endforeach
        @if ($enabledCount === 0)
            <span class="text-xs text-surface-400 dark:text-surface-500 italic">No channels enabled</span>
        @endif
    </div>

    {{-- ════════════════════════════════════════════════════════════════════════════
         Tab Navigation
         ════════════════════════════════════════════════════════════════════════════ --}}
    <div class="border-b border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 rounded-t-xl">
        <nav class="-mb-px flex space-x-1 overflow-x-auto scrollbar-none px-4" role="tablist">
            @php
                $tabs = [
                    ['id' => 'general', 'label' => 'General', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />'],
                    ['id' => 'login-channels', 'label' => 'Login Channels', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />'],
                    ['id' => '2fa', 'label' => '2FA Settings', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />'],
                    ['id' => 'magic-login', 'label' => 'Magic Login', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />'],
                    ['id' => 'rate-limits', 'label' => 'Rate Limits', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />'],
                    ['id' => 'social-login', 'label' => 'Social Login', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />'],
                ];
            @endphp

            @foreach ($tabs as $tab)
                <button type="button" role="tab" aria-selected="{{ $activeTab === $tab['id'] ? 'true' : 'false' }}"
                    wire:click="switchTab('{{ $tab['id'] }}')"
                    class="group inline-flex items-center gap-2 whitespace-nowrap px-4 py-3.5 text-sm font-medium transition-all duration-200 border-b-2 -mb-px
                    {{ $activeTab === $tab['id']
                        ? 'border-brand-500 text-brand-600 dark:text-brand-400'
                        : 'border-transparent text-surface-500 dark:text-surface-400 hover:text-surface-700 dark:hover:text-surface-300 hover:border-surface-300 dark:hover:border-surface-600' }}">
                    <svg class="size-4 {{ $activeTab === $tab['id'] ? 'text-brand-500' : 'text-surface-400 dark:text-surface-500 group-hover:text-surface-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        {!! $tab['icon'] !!}
                    </svg>
                    {{ $tab['label'] }}
                </button>
            @endforeach
        </nav>
    </div>

    {{-- ════════════════════════════════════════════════════════════════════════════
         Tab Content
         ════════════════════════════════════════════════════════════════════════════ --}}
    <div class="min-h-[300px]">

        {{-- ── GENERAL TAB ─────────────────────────────────────────────────────── --}}
        @if ($activeTab === 'general')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- SMS --}}
                <div class="group relative rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 p-6 transition-all duration-200 hover:shadow-md hover:border-brand-200 dark:hover:border-brand-500/30 {{ ($channelToggles['sms'] ?? false) ? 'ring-1 ring-brand-100 dark:ring-brand-500/20' : '' }}">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400">
                                <svg class="size-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-surface-900 dark:text-white">SMS (Barta)</h3>
                                <p class="mt-1 text-sm text-surface-500 dark:text-surface-400">Send OTP codes via SMS using the Barta gateway.</p>
                            </div>
                        </div>
                        <div wire:click="$toggle('channelToggles.sms')" role="switch"
                             aria-checked="{{ $channelToggles['sms'] ?? false }}"
                             class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-400 focus-visible:ring-offset-2
                             {{ ($channelToggles['sms'] ?? false) ? 'bg-brand-500' : 'bg-surface-300 dark:bg-surface-600' }}">
                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out
                                {{ ($channelToggles['sms'] ?? false) ? 'translate-x-5' : 'translate-x-0' }}"></span>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center gap-2">
                        @if ($channelToggles['sms'] ?? false)
                            <span class="inline-flex items-center gap-1 rounded-full bg-success-50 dark:bg-success-500/10 px-2.5 py-0.5 text-xs font-medium text-success-700 dark:text-success-400 ring-1 ring-success-200 dark:ring-success-500/20">
                                <svg class="size-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                                Active
                            </span>
                            <span class="text-xs text-surface-400 dark:text-surface-500">Gateway: {{ $smsGateway ?: 'sslwireless' }}</span>
                        @else
                            <span class="inline-flex items-center gap-1 rounded-full bg-surface-100 dark:bg-surface-700 px-2.5 py-0.5 text-xs font-medium text-surface-500 dark:text-surface-400">Disabled</span>
                        @endif
                    </div>
                </div>

                {{-- WhatsApp --}}
                <div class="group relative rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 p-6 transition-all duration-200 hover:shadow-md hover:border-brand-200 dark:hover:border-brand-500/30 {{ ($channelToggles['whatsapp'] ?? false) ? 'ring-1 ring-brand-100 dark:ring-brand-500/20' : '' }}">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                                <svg class="size-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-surface-900 dark:text-white">WhatsApp Cloud API</h3>
                                <p class="mt-1 text-sm text-surface-500 dark:text-surface-400">Send OTP codes via WhatsApp Cloud API.</p>
                            </div>
                        </div>
                        <div wire:click="$toggle('channelToggles.whatsapp')" role="switch"
                             aria-checked="{{ $channelToggles['whatsapp'] ?? false }}"
                             class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-400 focus-visible:ring-offset-2
                             {{ ($channelToggles['whatsapp'] ?? false) ? 'bg-brand-500' : 'bg-surface-300 dark:bg-surface-600' }}">
                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out
                                {{ ($channelToggles['whatsapp'] ?? false) ? 'translate-x-5' : 'translate-x-0' }}"></span>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center gap-2">
                        @if ($channelToggles['whatsapp'] ?? false)
                            <span class="inline-flex items-center gap-1 rounded-full bg-success-50 dark:bg-success-500/10 px-2.5 py-0.5 text-xs font-medium text-success-700 dark:text-success-400 ring-1 ring-success-200 dark:ring-success-500/20">
                                <svg class="size-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 rounded-full bg-surface-100 dark:bg-surface-700 px-2.5 py-0.5 text-xs font-medium text-surface-500 dark:text-surface-400">Disabled</span>
                        @endif
                    </div>
                </div>

                {{-- Telegram --}}
                <div class="group relative rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 p-6 transition-all duration-200 hover:shadow-md hover:border-brand-200 dark:hover:border-brand-500/30 {{ ($channelToggles['telegram'] ?? false) ? 'ring-1 ring-brand-100 dark:ring-brand-500/20' : '' }}">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-sky-50 dark:bg-sky-500/10 text-sky-600 dark:text-sky-400">
                                <svg class="size-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-surface-900 dark:text-white">Telegram</h3>
                                <p class="mt-1 text-sm text-surface-500 dark:text-surface-400">Send OTP codes via Telegram Bot API.</p>
                            </div>
                        </div>
                        <div wire:click="$toggle('channelToggles.telegram')" role="switch"
                             aria-checked="{{ $channelToggles['telegram'] ?? false }}"
                             class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-400 focus-visible:ring-offset-2
                             {{ ($channelToggles['telegram'] ?? false) ? 'bg-brand-500' : 'bg-surface-300 dark:bg-surface-600' }}">
                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out
                                {{ ($channelToggles['telegram'] ?? false) ? 'translate-x-5' : 'translate-x-0' }}"></span>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center gap-2">
                        @if ($channelToggles['telegram'] ?? false)
                            <span class="inline-flex items-center gap-1 rounded-full bg-success-50 dark:bg-success-500/10 px-2.5 py-0.5 text-xs font-medium text-success-700 dark:text-success-400 ring-1 ring-success-200 dark:ring-success-500/20">
                                <svg class="size-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 rounded-full bg-surface-100 dark:bg-surface-700 px-2.5 py-0.5 text-xs font-medium text-surface-500 dark:text-surface-400">Disabled</span>
                        @endif
                    </div>
                </div>

                {{-- Email --}}
                <div class="group relative rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 p-6 transition-all duration-200 hover:shadow-md hover:border-brand-200 dark:hover:border-brand-500/30 ring-1 ring-brand-100 dark:ring-brand-500/20">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400">
                                <svg class="size-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-surface-900 dark:text-white">Email</h3>
                                <p class="mt-1 text-sm text-surface-500 dark:text-surface-400">Send OTP codes via email. Always available as a fallback.</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center rounded-full bg-surface-100 dark:bg-surface-700 px-2.5 py-1 text-xs font-medium text-surface-500 dark:text-surface-400">Always on</span>
                    </div>
                </div>
            </div>

        {{-- ── LOGIN CHANNELS TAB ───────────────────────────────────────────────── --}}
        @elseif ($activeTab === 'login-channels')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Phone / SMS OTP --}}
                <div class="rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 p-6 transition-all duration-200 hover:shadow-md {{ $otpLoginPhoneEnabled ? 'ring-1 ring-brand-100 dark:ring-brand-500/20' : '' }}">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl {{ $otpLoginPhoneEnabled ? 'bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400' : 'bg-surface-100 dark:bg-surface-700 text-surface-400 dark:text-surface-500' }}">
                                <svg class="size-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-surface-900 dark:text-white">Phone / SMS OTP</h3>
                                <p class="mt-1 text-sm text-surface-500 dark:text-surface-400">Login via SMS code sent to phone number.</p>
                            </div>
                        </div>
                        <div wire:click="$toggle('otpLoginPhoneEnabled')" role="switch"
                             aria-checked="{{ $otpLoginPhoneEnabled }}"
                             class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-400 focus-visible:ring-offset-2
                             {{ $otpLoginPhoneEnabled ? 'bg-brand-500' : 'bg-surface-300 dark:bg-surface-600' }}">
                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out
                                {{ $otpLoginPhoneEnabled ? 'translate-x-5' : 'translate-x-0' }}"></span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium {{ $otpLoginPhoneEnabled ? 'bg-success-50 dark:bg-success-500/10 text-success-700 dark:text-success-400 ring-1 ring-success-200 dark:ring-success-500/20' : 'bg-surface-100 dark:bg-surface-700 text-surface-500' }}">
                            {{ $otpLoginPhoneEnabled ? 'Enabled' : 'Disabled' }}
                        </span>
                    </div>
                </div>

                {{-- WhatsApp OTP --}}
                <div class="rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 p-6 transition-all duration-200 hover:shadow-md {{ $otpLoginWhatsAppEnabled ? 'ring-1 ring-emerald-100 dark:ring-emerald-500/20' : '' }}">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl {{ $otpLoginWhatsAppEnabled ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-surface-100 dark:bg-surface-700 text-surface-400 dark:text-surface-500' }}">
                                <svg class="size-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-surface-900 dark:text-white">WhatsApp OTP</h3>
                                <p class="mt-1 text-sm text-surface-500 dark:text-surface-400">Login via WhatsApp OTP code.</p>
                            </div>
                        </div>
                        <div wire:click="$toggle('otpLoginWhatsAppEnabled')" role="switch"
                             aria-checked="{{ $otpLoginWhatsAppEnabled }}"
                             class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-400 focus-visible:ring-offset-2
                             {{ $otpLoginWhatsAppEnabled ? 'bg-brand-500' : 'bg-surface-300 dark:bg-surface-600' }}">
                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out
                                {{ $otpLoginWhatsAppEnabled ? 'translate-x-5' : 'translate-x-0' }}"></span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium {{ $otpLoginWhatsAppEnabled ? 'bg-success-50 dark:bg-success-500/10 text-success-700 dark:text-success-400 ring-1 ring-success-200 dark:ring-success-500/20' : 'bg-surface-100 dark:bg-surface-700 text-surface-500' }}">
                            {{ $otpLoginWhatsAppEnabled ? 'Enabled' : 'Disabled' }}
                        </span>
                    </div>
                </div>

                {{-- Telegram OTP --}}
                <div class="rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 p-6 transition-all duration-200 hover:shadow-md {{ $otpLoginTelegramEnabled ? 'ring-1 ring-sky-100 dark:ring-sky-500/20' : '' }}">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl {{ $otpLoginTelegramEnabled ? 'bg-sky-50 dark:bg-sky-500/10 text-sky-600 dark:text-sky-400' : 'bg-surface-100 dark:bg-surface-700 text-surface-400 dark:text-surface-500' }}">
                                <svg class="size-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-surface-900 dark:text-white">Telegram OTP</h3>
                                <p class="mt-1 text-sm text-surface-500 dark:text-surface-400">Login via Telegram OTP code.</p>
                            </div>
                        </div>
                        <div wire:click="$toggle('otpLoginTelegramEnabled')" role="switch"
                             aria-checked="{{ $otpLoginTelegramEnabled }}"
                             class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-400 focus-visible:ring-offset-2
                             {{ $otpLoginTelegramEnabled ? 'bg-brand-500' : 'bg-surface-300 dark:bg-surface-600' }}">
                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out
                                {{ $otpLoginTelegramEnabled ? 'translate-x-5' : 'translate-x-0' }}"></span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium {{ $otpLoginTelegramEnabled ? 'bg-success-50 dark:bg-success-500/10 text-success-700 dark:text-success-400 ring-1 ring-success-200 dark:ring-success-500/20' : 'bg-surface-100 dark:bg-surface-700 text-surface-500' }}">
                            {{ $otpLoginTelegramEnabled ? 'Enabled' : 'Disabled' }}
                        </span>
                    </div>
                </div>

                {{-- Email OTP --}}
                <div class="rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 p-6 transition-all duration-200 hover:shadow-md {{ $otpLoginEmailEnabled ? 'ring-1 ring-purple-100 dark:ring-purple-500/20' : '' }}">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl {{ $otpLoginEmailEnabled ? 'bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400' : 'bg-surface-100 dark:bg-surface-700 text-surface-400 dark:text-surface-500' }}">
                                <svg class="size-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-surface-900 dark:text-white">Email OTP</h3>
                                <p class="mt-1 text-sm text-surface-500 dark:text-surface-400">Login via email OTP code.</p>
                            </div>
                        </div>
                        <div wire:click="$toggle('otpLoginEmailEnabled')" role="switch"
                             aria-checked="{{ $otpLoginEmailEnabled }}"
                             class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-400 focus-visible:ring-offset-2
                             {{ $otpLoginEmailEnabled ? 'bg-brand-500' : 'bg-surface-300 dark:bg-surface-600' }}">
                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out
                                {{ $otpLoginEmailEnabled ? 'translate-x-5' : 'translate-x-0' }}"></span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium {{ $otpLoginEmailEnabled ? 'bg-success-50 dark:bg-success-500/10 text-success-700 dark:text-success-400 ring-1 ring-success-200 dark:ring-success-500/20' : 'bg-surface-100 dark:bg-surface-700 text-surface-500' }}">
                            {{ $otpLoginEmailEnabled ? 'Enabled' : 'Disabled' }}
                        </span>
                    </div>
                </div>

                {{-- Magic Link --}}
                <div class="rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 p-6 transition-all duration-200 hover:shadow-md {{ $otpLoginMagicLinkEnabled ? 'ring-1 ring-amber-100 dark:ring-amber-500/20' : '' }}">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl {{ $otpLoginMagicLinkEnabled ? 'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400' : 'bg-surface-100 dark:bg-surface-700 text-surface-400 dark:text-surface-500' }}">
                                <svg class="size-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-surface-900 dark:text-white">Magic Link</h3>
                                <p class="mt-1 text-sm text-surface-500 dark:text-surface-400">Passwordless login via email magic link.</p>
                            </div>
                        </div>
                        <div wire:click="$toggle('otpLoginMagicLinkEnabled')" role="switch"
                             aria-checked="{{ $otpLoginMagicLinkEnabled }}"
                             class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-400 focus-visible:ring-offset-2
                             {{ $otpLoginMagicLinkEnabled ? 'bg-brand-500' : 'bg-surface-300 dark:bg-surface-600' }}">
                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out
                                {{ $otpLoginMagicLinkEnabled ? 'translate-x-5' : 'translate-x-0' }}"></span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium {{ $otpLoginMagicLinkEnabled ? 'bg-success-50 dark:bg-success-500/10 text-success-700 dark:text-success-400 ring-1 ring-success-200 dark:ring-success-500/20' : 'bg-surface-100 dark:bg-surface-700 text-surface-500' }}">
                            {{ $otpLoginMagicLinkEnabled ? 'Enabled' : 'Disabled' }}
                        </span>
                    </div>
                </div>
            </div>

        {{-- ── 2FA SETTINGS TAB ─────────────────────────────────────────────────── --}}
        @elseif ($activeTab === '2fa')
            <div class="space-y-6">
                <div class="rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 divide-y divide-surface-200 dark:divide-surface-700">
                    {{-- Default 2FA channel --}}
                    <div class="flex items-center justify-between gap-4 px-6 py-5">
                        <div class="flex items-start gap-3">
                            <svg class="size-5 mt-0.5 text-surface-400 dark:text-surface-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-surface-900 dark:text-white">Default 2FA channel</p>
                                <p class="text-xs text-surface-500 dark:text-surface-400 mt-0.5">Which OTP channel to use when sending two-factor challenge codes.</p>
                            </div>
                        </div>
                        <div class="relative">
                            <select wire:model="twoFactorDefaultChannel"
                                class="appearance-none rounded-xl border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 px-4 py-2.5 pr-10 text-sm text-surface-900 dark:text-white focus:border-brand-400 focus:ring-brand-500/20 focus:ring-[3px] cursor-pointer">
                                <option value="sms">SMS</option>
                                <option value="whatsapp">WhatsApp</option>
                                <option value="telegram">Telegram</option>
                            </select>
                            <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 size-4 text-surface-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </div>
                    </div>

                    {{-- Force 2FA --}}
                    <div class="flex items-center justify-between gap-4 px-6 py-5">
                        <div class="flex items-start gap-3">
                            <svg class="size-5 mt-0.5 text-surface-400 dark:text-surface-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-surface-900 dark:text-white">Force 2FA for all users</p>
                                <p class="text-xs text-surface-500 dark:text-surface-400 mt-0.5">When enabled, every authenticated user must complete a second OTP challenge regardless of their individual setting.</p>
                            </div>
                        </div>
                        <div class="flex rounded-xl border border-surface-300 dark:border-surface-600 overflow-hidden">
                            <button type="button" wire:click="$set('twoFactorForceAllUsers', false)"
                                class="px-4 py-2 text-sm font-medium transition-colors {{ !$twoFactorForceAllUsers ? 'bg-brand-500 text-white' : 'bg-white dark:bg-surface-700 text-surface-700 dark:text-surface-300 hover:bg-surface-50 dark:hover:bg-surface-600' }}">
                                Disabled
                            </button>
                            <button type="button" wire:click="$set('twoFactorForceAllUsers', true)"
                                class="px-4 py-2 text-sm font-medium transition-colors {{ $twoFactorForceAllUsers ? 'bg-brand-500 text-white' : 'bg-white dark:bg-surface-700 text-surface-700 dark:text-surface-300 hover:bg-surface-50 dark:hover:bg-surface-600' }}">
                                {{ $twoFactorForceAllUsers ? 'Enabled ✓' : 'Enabled' }}
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Security info alert --}}
                <div class="rounded-2xl border border-brand-200 dark:border-brand-500/20 bg-brand-50 dark:bg-brand-500/5 p-5">
                    <div class="flex gap-3">
                        <svg class="size-5 shrink-0 text-brand-600 dark:text-brand-400 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                        </svg>
                        <div>
                            <h4 class="text-sm font-semibold text-brand-700 dark:text-brand-300">Security Recommendation</h4>
                            <p class="mt-1 text-sm text-brand-600 dark:text-brand-400">Enabling 2FA for all users adds an essential layer of security to your application. We recommend using multiple OTP channels so users have a backup method if their primary channel is unavailable.</p>
                        </div>
                    </div>
                </div>
            </div>

        {{-- ── MAGIC LOGIN TAB ───────────────────────────────────────────────────── --}}
        @elseif ($activeTab === 'magic-login')
            <div class="space-y-6">
                <div class="rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start gap-4">
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 text-white shadow-lg">
                                <svg class="size-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-surface-900 dark:text-white">Magic Login (Passwordless)</h3>
                                <p class="mt-1 text-sm text-surface-500 dark:text-surface-400">Allow users to sign in without a password via a secure email link.</p>
                            </div>
                        </div>
                        <div wire:click="$toggle('magicLoginEnabled')" role="switch"
                             aria-checked="{{ $magicLoginEnabled }}"
                             class="relative inline-flex h-7 w-14 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-400 focus-visible:ring-offset-2
                             {{ $magicLoginEnabled ? 'bg-brand-500' : 'bg-surface-300 dark:bg-surface-600' }}">
                            <span class="pointer-events-none inline-block h-6 w-6 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out
                                {{ $magicLoginEnabled ? 'translate-x-7' : 'translate-x-0' }}"></span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Expiration --}}
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Magic link expiration (minutes)</label>
                        <div class="relative">
                            <input wire:model="magicLinkExpiration" type="number" min="1" max="1440"
                                class="w-full rounded-xl border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 px-4 py-2.5 pl-10 text-sm text-surface-900 dark:text-white placeholder:text-surface-400 focus:border-brand-400 focus:ring-brand-500/20 focus:ring-[3px]">
                            <svg class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 size-4 text-surface-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>
                        <p class="mt-1 text-xs text-surface-500 dark:text-surface-400">How long the magic link remains valid after being sent.</p>
                    </div>

                    {{-- Device restriction --}}
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Device restriction</label>
                        <div class="flex flex-wrap gap-2">
                            <label class="flex items-center gap-2 rounded-xl border border-surface-300 dark:border-surface-600 px-4 py-2.5 cursor-pointer transition-colors {{ $magicLinkDeviceRestriction === 'all' ? 'bg-brand-50 dark:bg-brand-500/10 border-brand-300 dark:border-brand-500/30' : 'bg-white dark:bg-surface-700 hover:bg-surface-50 dark:hover:bg-surface-600' }}">
                                <input type="radio" wire:model="magicLinkDeviceRestriction" value="all" class="sr-only">
                                <svg class="size-4 {{ $magicLinkDeviceRestriction === 'all' ? 'text-brand-600 dark:text-brand-400' : 'text-surface-400' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" />
                                </svg>
                                <span class="text-sm font-medium {{ $magicLinkDeviceRestriction === 'all' ? 'text-brand-700 dark:text-brand-300' : 'text-surface-700 dark:text-surface-300' }}">All devices</span>
                            </label>
                            <label class="flex items-center gap-2 rounded-xl border border-surface-300 dark:border-surface-600 px-4 py-2.5 cursor-pointer transition-colors {{ $magicLinkDeviceRestriction === 'mobile' ? 'bg-brand-50 dark:bg-brand-500/10 border-brand-300 dark:border-brand-500/30' : 'bg-white dark:bg-surface-700 hover:bg-surface-50 dark:hover:bg-surface-600' }}">
                                <input type="radio" wire:model="magicLinkDeviceRestriction" value="mobile" class="sr-only">
                                <svg class="size-4 {{ $magicLinkDeviceRestriction === 'mobile' ? 'text-brand-600 dark:text-brand-400' : 'text-surface-400' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                                </svg>
                                <span class="text-sm font-medium {{ $magicLinkDeviceRestriction === 'mobile' ? 'text-brand-700 dark:text-brand-300' : 'text-surface-700 dark:text-surface-300' }}">Mobile only</span>
                            </label>
                            <label class="flex items-center gap-2 rounded-xl border border-surface-300 dark:border-surface-600 px-4 py-2.5 cursor-pointer transition-colors {{ $magicLinkDeviceRestriction === 'desktop' ? 'bg-brand-50 dark:bg-brand-500/10 border-brand-300 dark:border-brand-500/30' : 'bg-white dark:bg-surface-700 hover:bg-surface-50 dark:hover:bg-surface-600' }}">
                                <input type="radio" wire:model="magicLinkDeviceRestriction" value="desktop" class="sr-only">
                                <svg class="size-4 {{ $magicLinkDeviceRestriction === 'desktop' ? 'text-brand-600 dark:text-brand-400' : 'text-surface-400' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" />
                                </svg>
                                <span class="text-sm font-medium {{ $magicLinkDeviceRestriction === 'desktop' ? 'text-brand-700 dark:text-brand-300' : 'text-surface-700 dark:text-surface-300' }}">Desktop only</span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Security info box --}}
                <div class="rounded-2xl border border-purple-200 dark:border-purple-500/20 bg-purple-50 dark:bg-purple-500/5 p-5">
                    <div class="flex gap-3">
                        <svg class="size-5 shrink-0 text-purple-600 dark:text-purple-400 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                        </svg>
                        <div>
                            <h4 class="text-sm font-semibold text-purple-700 dark:text-purple-300">About Magic Login</h4>
                            <p class="mt-1 text-sm text-purple-600 dark:text-purple-400">Passwordless authentication improves security by eliminating password-related risks. Users receive a one-time link via email that expires after the configured duration. Device restrictions add an extra layer of security by limiting which device types can use magic links.</p>
                        </div>
                    </div>
                </div>
            </div>

        {{-- ── RATE LIMITS TAB ───────────────────────────────────────────────────--}}
        @elseif ($activeTab === 'rate-limits')
            <div class="space-y-6">
                <div class="rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 p-6">
                    <h3 class="text-base font-semibold text-surface-900 dark:text-white mb-1">Rate Limits & Cooldowns</h3>
                    <p class="text-sm text-surface-500 dark:text-surface-400 mb-6">Configure security limits for OTP requests, sessions, and login attempts.</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        {{-- Max per IP --}}
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">
                                <span class="flex items-center gap-1.5">
                                    <svg class="size-4 text-surface-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                                    </svg>
                                    Max requests per IP
                                </span>
                            </label>
                            <input wire:model="otpRateLimitPerIp" type="number" min="1" max="1000"
                                class="w-full rounded-xl border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 px-4 py-2.5 text-sm text-surface-900 dark:text-white placeholder:text-surface-400 focus:border-brand-400 focus:ring-brand-500/20 focus:ring-[3px]">
                            <p class="mt-1 text-xs text-surface-500 dark:text-surface-400">Max OTP requests per IP address.</p>
                        </div>

                        {{-- Window --}}
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">
                                <span class="flex items-center gap-1.5">
                                    <svg class="size-4 text-surface-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    Window (minutes)
                                </span>
                            </label>
                            <input wire:model="otpRateLimitWindow" type="number" min="1" max="1440"
                                class="w-full rounded-xl border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 px-4 py-2.5 text-sm text-surface-900 dark:text-white placeholder:text-surface-400 focus:border-brand-400 focus:ring-brand-500/20 focus:ring-[3px]">
                            <p class="mt-1 text-xs text-surface-500 dark:text-surface-400">Time window for rate limit counting.</p>
                        </div>

                        {{-- Cooldown --}}
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">
                                <span class="flex items-center gap-1.5">
                                    <svg class="size-4 text-surface-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                    </svg>
                                    Resend cooldown (seconds)
                                </span>
                            </label>
                            <input wire:model="otpCooldownSeconds" type="number" min="0" max="300"
                                class="w-full rounded-xl border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 px-4 py-2.5 text-sm text-surface-900 dark:text-white placeholder:text-surface-400 focus:border-brand-400 focus:ring-brand-500/20 focus:ring-[3px]">
                            <p class="mt-1 text-xs text-surface-500 dark:text-surface-400">Minimum time before resend is allowed.</p>
                        </div>

                        {{-- Session timeout --}}
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">
                                <span class="flex items-center gap-1.5">
                                    <svg class="size-4 text-surface-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
                                    </svg>
                                    Session timeout (minutes)
                                </span>
                            </label>
                            <input wire:model="sessionTimeout" type="number" min="5" max="1440"
                                class="w-full rounded-xl border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 px-4 py-2.5 text-sm text-surface-900 dark:text-white placeholder:text-surface-400 focus:border-brand-400 focus:ring-brand-500/20 focus:ring-[3px]">
                            <p class="mt-1 text-xs text-surface-500 dark:text-surface-400">Max session duration before re-authentication.</p>
                        </div>

                        {{-- Login throttle attempts --}}
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">
                                <span class="flex items-center gap-1.5">
                                    <svg class="size-4 text-surface-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                    </svg>
                                    Login throttle attempts
                                </span>
                            </label>
                            <input wire:model="loginThrottleAttempts" type="number" min="1" max="100"
                                class="w-full rounded-xl border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 px-4 py-2.5 text-sm text-surface-900 dark:text-white placeholder:text-surface-400 focus:border-brand-400 focus:ring-brand-500/20 focus:ring-[3px]">
                            <p class="mt-1 text-xs text-surface-500 dark:text-surface-400">Failed attempts before throttling.</p>
                        </div>

                        {{-- Throttle decay --}}
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">
                                <span class="flex items-center gap-1.5">
                                    <svg class="size-4 text-surface-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    Throttle decay (seconds)
                                </span>
                            </label>
                            <input wire:model="loginThrottleDecay" type="number" min="1" max="3600"
                                class="w-full rounded-xl border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 px-4 py-2.5 text-sm text-surface-900 dark:text-white placeholder:text-surface-400 focus:border-brand-400 focus:ring-brand-500/20 focus:ring-[3px]">
                            <p class="mt-1 text-xs text-surface-500 dark:text-surface-400">Time after throttle resets.</p>
                        </div>
                    </div>
                </div>

                {{-- Warning alert --}}
                <div class="rounded-2xl border border-warning-200 dark:border-warning-500/20 bg-warning-50 dark:bg-warning-500/5 p-5">
                    <div class="flex gap-3">
                        <svg class="size-5 shrink-0 text-warning-600 dark:text-warning-400 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                        </svg>
                        <div>
                            <h4 class="text-sm font-semibold text-warning-700 dark:text-warning-300">Security Note</h4>
                            <p class="mt-1 text-sm text-warning-600 dark:text-warning-400">Rate limits protect your application from abuse and brute-force attacks. Set these values carefully based on your expected user traffic. Very restrictive limits may frustrate legitimate users, while very permissive limits may reduce security.</p>
                        </div>
                    </div>
                </div>
            </div>
        {{-- ── SOCIAL LOGIN TAB ─────────────────────────────────────────────────── --}}
        @elseif ($activeTab === 'social-login')
            <div class="space-y-6">
                {{-- Master toggle --}}
                <div class="rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start gap-4">
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-sky-500 to-blue-600 text-white shadow-lg">
                                <svg class="size-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-surface-900 dark:text-white">Social Login</h3>
                                <p class="mt-1 text-sm text-surface-500 dark:text-surface-400">Allow users to sign in with third-party OAuth providers. Toggle individual providers below.</p>
                            </div>
                        </div>
                        <div wire:click="$toggle('socialLoginEnabled')" role="switch"
                             aria-checked="{{ $socialLoginEnabled }}"
                             class="relative inline-flex h-7 w-14 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-400 focus-visible:ring-offset-2
                             {{ $socialLoginEnabled ? 'bg-brand-500' : 'bg-surface-300 dark:bg-surface-600' }}">
                            <span class="pointer-events-none inline-block h-6 w-6 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out
                                {{ $socialLoginEnabled ? 'translate-x-7' : 'translate-x-0' }}"></span>
                        </div>
                    </div>
                </div>

                {{-- Additional settings --}}
                <div class="rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 divide-y divide-surface-200 dark:divide-surface-700">
                    <div class="flex items-center justify-between gap-4 px-6 py-5">
                        <div class="flex items-start gap-3">
                            <svg class="size-5 mt-0.5 text-surface-400 dark:text-surface-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-surface-900 dark:text-white">Auto-link by email</p>
                                <p class="text-xs text-surface-500 dark:text-surface-400 mt-0.5">Automatically link a social provider to an existing user account with the same email address.</p>
                            </div>
                        </div>
                        <div wire:click="$toggle('socialLoginAutoLinkEmail')" role="switch"
                             aria-checked="{{ $socialLoginAutoLinkEmail }}"
                             class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-400 focus-visible:ring-offset-2
                             {{ $socialLoginAutoLinkEmail ? 'bg-brand-500' : 'bg-surface-300 dark:bg-surface-600' }}">
                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out
                                {{ $socialLoginAutoLinkEmail ? 'translate-x-5' : 'translate-x-0' }}"></span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-4 px-6 py-5">
                        <div class="flex items-start gap-3">
                            <svg class="size-5 mt-0.5 text-surface-400 dark:text-surface-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-surface-900 dark:text-white">Require verified email</p>
                                <p class="text-xs text-surface-500 dark:text-surface-400 mt-0.5">Only allow social login for users whose email has been verified by the provider.</p>
                            </div>
                        </div>
                        <div wire:click="$toggle('socialLoginRequireVerifiedEmail')" role="switch"
                             aria-checked="{{ $socialLoginRequireVerifiedEmail }}"
                             class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-400 focus-visible:ring-offset-2
                             {{ $socialLoginRequireVerifiedEmail ? 'bg-brand-500' : 'bg-surface-300 dark:bg-surface-600' }}">
                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out
                                {{ $socialLoginRequireVerifiedEmail ? 'translate-x-5' : 'translate-x-0' }}"></span>
                        </div>
                    </div>
                </div>

                {{-- Provider cards --}}
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="text-sm font-medium text-surface-700 dark:text-surface-300">Providers</span>
                        <span class="inline-flex items-center rounded-full bg-brand-50 dark:bg-brand-500/10 px-2.5 py-0.5 text-xs font-medium text-brand-700 dark:text-brand-400">
                            {{ $socialEnabledCount }} / {{ count($socialProviders) }} enabled
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($socialProviders as $name => $config)
                            @php
                                $providerLabels = [
                                    'google' => ['label' => 'Google', 'desc' => 'Sign in with Google account.', 'color' => 'bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400'],
                                    'github' => ['label' => 'GitHub', 'desc' => 'Sign in with GitHub account.', 'color' => 'bg-surface-900 dark:bg-white/10 text-white dark:text-white'],
                                    'facebook' => ['label' => 'Facebook', 'desc' => 'Sign in with Facebook account.', 'color' => 'bg-blue-600 text-white'],
                                    'twitter' => ['label' => 'Twitter / X', 'desc' => 'Sign in with X (Twitter) account.', 'color' => 'bg-black dark:bg-white text-white dark:text-black'],
                                    'linkedin' => ['label' => 'LinkedIn', 'desc' => 'Sign in with LinkedIn account.', 'color' => 'bg-[#0A66C2] text-white'],
                                    'gitlab' => ['label' => 'GitLab', 'desc' => 'Sign in with GitLab account.', 'color' => 'bg-[#E24329] text-white'],
                                    'microsoft' => ['label' => 'Microsoft', 'desc' => 'Sign in with Microsoft account.', 'color' => 'bg-[#00A4EF] text-white'],
                                ];
                                $info = $providerLabels[$name] ?? ['label' => ucfirst($name), 'desc' => '', 'color' => 'bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400'];
                            @endphp
                            <div class="rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 p-5 transition-all duration-200 hover:shadow-md {{ $config['enabled'] ? 'ring-1 ring-brand-100 dark:ring-brand-500/20' : '' }}">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-start gap-3 min-w-0">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $info['color'] }}">
                                            <x-ui-icon :name="$name" class="size-5" />
                                        </div>
                                        <div class="min-w-0">
                                            <h4 class="text-sm font-semibold text-surface-900 dark:text-white truncate">{{ $info['label'] }}</h4>
                                            <p class="text-xs text-surface-500 dark:text-surface-400 mt-0.5">{{ $info['desc'] }}</p>
                                        </div>
                                    </div>
                                    <div wire:click="$toggle('socialProviders.{{ $name }}.enabled')" role="switch"
                                         aria-checked="{{ $config['enabled'] }}"
                                         class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-400 focus-visible:ring-offset-2
                                         {{ $config['enabled'] ? 'bg-brand-500' : 'bg-surface-300 dark:bg-surface-600' }}">
                                        <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out
                                            {{ $config['enabled'] ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                    </div>
                                </div>

                                {{-- Inline credential fields --}}
                                @if ($config['enabled'])
                                    <div class="mt-4 pt-4 border-t border-surface-200 dark:border-surface-700 space-y-3">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-xs font-medium text-surface-600 dark:text-surface-400 mb-1">Client ID</label>
                                                <input wire:model="socialProviders.{{ $name }}.client_id" type="text" autocomplete="off"
                                                    class="w-full rounded-lg border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 px-3 py-2 text-xs text-surface-900 dark:text-white placeholder:text-surface-400 focus:border-brand-400 focus:ring-brand-500/20 focus:ring-[3px]">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-surface-600 dark:text-surface-400 mb-1">Client Secret</label>
                                                <input wire:model="socialProviders.{{ $name }}.client_secret" type="password" autocomplete="off"
                                                    class="w-full rounded-lg border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 px-3 py-2 text-xs text-surface-900 dark:text-white placeholder:text-surface-400 focus:border-brand-400 focus:ring-brand-500/20 focus:ring-[3px]">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-surface-600 dark:text-surface-400 mb-1">Redirect URI</label>
                                            <input type="text" readonly value="{{ route('auth.social.callback', $name) }}"
                                                class="w-full rounded-lg border border-surface-200 dark:border-surface-600 bg-surface-50 dark:bg-surface-700/50 px-3 py-2 text-xs text-surface-500 dark:text-surface-400 font-mono select-all cursor-text">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-surface-600 dark:text-surface-400 mb-1">Scopes</label>
                                            <input wire:model="socialProviders.{{ $name }}.scopes" type="text" autocomplete="off"
                                                class="w-full rounded-lg border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 px-3 py-2 text-xs text-surface-900 dark:text-white placeholder:text-surface-400 focus:border-brand-400 focus:ring-brand-500/20 focus:ring-[3px]">
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Security info --}}
                <div class="rounded-2xl border border-sky-200 dark:border-sky-500/20 bg-sky-50 dark:bg-sky-500/5 p-5">
                    <div class="flex gap-3">
                        <svg class="size-5 shrink-0 text-sky-600 dark:text-sky-400 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                        </svg>
                        <div>
                            <h4 class="text-sm font-semibold text-sky-700 dark:text-sky-300">About Social Login</h4>
                            <p class="mt-1 text-sm text-sky-600 dark:text-sky-400">OAuth providers handle authentication securely; your application never sees the user's password. Credentials are encrypted at rest using Laravel's application key. Redirect URIs must be registered in the provider's developer console. Use the auto-link feature carefully — matching by email alone can allow account takeover if a provider email is compromised.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- ════════════════════════════════════════════════════════════════════════════
         Credential Accordion Sections (always visible)
         ════════════════════════════════════════════════════════════════════════════ --}}
    <div class="space-y-4">
        <div class="flex items-center gap-2">
            <svg class="size-5 text-surface-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
            </svg>
            <h2 class="text-lg font-semibold text-surface-900 dark:text-white">Provider Credentials</h2>
            <span class="text-xs text-surface-500 dark:text-surface-400">Configure API keys and connection details for each provider.</span>
        </div>

        <div x-data="{ open: null }" class="divide-y divide-surface-200 dark:divide-surface-700 rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 overflow-hidden">
            {{-- SMS Gateway --}}
            <div>
                <button type="button" @click="open = (open === 'sms' ? null : 'sms')"
                    class="flex w-full items-center justify-between gap-3 px-6 py-4 text-left transition-colors hover:bg-surface-50 dark:hover:bg-surface-700/50">
                    <div class="flex items-center gap-3 min-w-0">
                        <svg class="size-5 shrink-0 text-surface-400 dark:text-surface-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                        </svg>
                        <div>
                            <span class="text-sm font-medium text-surface-900 dark:text-white">SMS Gateway</span>
                            <p class="text-xs text-surface-500 dark:text-surface-400 mt-0.5">Configure your SMS provider connection.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $channelToggles['sms'] ?? false ? 'bg-success-50 dark:bg-success-500/10 text-success-600 dark:text-success-400' : 'bg-surface-100 dark:bg-surface-700 text-surface-500 dark:text-surface-400' }}">
                            {{ ($channelToggles['sms'] ?? false) ? 'Enabled' : 'Disabled' }}
                        </span>
                        <svg class="size-4 shrink-0 text-surface-400 dark:text-surface-500 transition-transform duration-200"
                             :class="{ 'rotate-180': open === 'sms' }"
                             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>
                </button>
                <div x-show="open === 'sms'" x-collapse.duration.200ms class="px-6 pb-6">
                    <div class="pt-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Gateway</label>
                            <select wire:model="smsGateway"
                                class="w-full rounded-xl border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 px-4 py-2.5 text-sm text-surface-900 dark:text-white focus:border-brand-400 focus:ring-brand-500/20 focus:ring-[3px]">
                                <option value="sslwireless">SSL Wireless</option>
                                <option value="twilio">Twilio</option>
                                <option value="nexmo">Nexmo / Vonage</option>
                                <option value="custom">Custom</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Username / API Key</label>
                            <input wire:model="smsUsername" type="text" autocomplete="off"
                                class="w-full rounded-xl border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 px-4 py-2.5 text-sm text-surface-900 dark:text-white placeholder:text-surface-400 focus:border-brand-400 focus:ring-brand-500/20 focus:ring-[3px]">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Password / API Secret</label>
                            <input wire:model="smsPassword" type="password" autocomplete="off"
                                class="w-full rounded-xl border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 px-4 py-2.5 text-sm text-surface-900 dark:text-white placeholder:text-surface-400 focus:border-brand-400 focus:ring-brand-500/20 focus:ring-[3px]">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">SID / Account ID</label>
                            <input wire:model="smsSid" type="text" autocomplete="off"
                                class="w-full rounded-xl border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 px-4 py-2.5 text-sm text-surface-900 dark:text-white placeholder:text-surface-400 focus:border-brand-400 focus:ring-brand-500/20 focus:ring-[3px]">
                        </div>
                    </div>
                </div>
            </div>

            {{-- WhatsApp Cloud API --}}
            <div>
                <button type="button" @click="open = (open === 'whatsapp' ? null : 'whatsapp')"
                    class="flex w-full items-center justify-between gap-3 px-6 py-4 text-left transition-colors hover:bg-surface-50 dark:hover:bg-surface-700/50">
                    <div class="flex items-center gap-3 min-w-0">
                        <svg class="size-5 shrink-0 text-surface-400 dark:text-surface-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                        </svg>
                        <div>
                            <span class="text-sm font-medium text-surface-900 dark:text-white">WhatsApp Cloud API</span>
                            <p class="text-xs text-surface-500 dark:text-surface-400 mt-0.5">Facebook Cloud API credentials.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $channelToggles['whatsapp'] ?? false ? 'bg-success-50 dark:bg-success-500/10 text-success-600 dark:text-success-400' : 'bg-surface-100 dark:bg-surface-700 text-surface-500 dark:text-surface-400' }}">
                            {{ ($channelToggles['whatsapp'] ?? false) ? 'Enabled' : 'Disabled' }}
                        </span>
                        <svg class="size-4 shrink-0 text-surface-400 dark:text-surface-500 transition-transform duration-200"
                             :class="{ 'rotate-180': open === 'whatsapp' }"
                             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>
                </button>
                <div x-show="open === 'whatsapp'" x-collapse.duration.200ms class="px-6 pb-6">
                    <div class="pt-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">API URL</label>
                            <input wire:model="whatsappApiUrl" type="url" autocomplete="off" placeholder="https://graph.facebook.com/v17.0"
                                class="w-full rounded-xl border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 px-4 py-2.5 text-sm text-surface-900 dark:text-white placeholder:text-surface-400 focus:border-brand-400 focus:ring-brand-500/20 focus:ring-[3px]">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Phone Number ID</label>
                            <input wire:model="whatsappPhoneId" type="text" autocomplete="off"
                                class="w-full rounded-xl border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 px-4 py-2.5 text-sm text-surface-900 dark:text-white placeholder:text-surface-400 focus:border-brand-400 focus:ring-brand-500/20 focus:ring-[3px]">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Access Token</label>
                            <input wire:model="whatsappAccessToken" type="password" autocomplete="off"
                                class="w-full rounded-xl border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 px-4 py-2.5 text-sm text-surface-900 dark:text-white placeholder:text-surface-400 focus:border-brand-400 focus:ring-brand-500/20 focus:ring-[3px]">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Telegram Bot --}}
            <div>
                <button type="button" @click="open = (open === 'telegram' ? null : 'telegram')"
                    class="flex w-full items-center justify-between gap-3 px-6 py-4 text-left transition-colors hover:bg-surface-50 dark:hover:bg-surface-700/50">
                    <div class="flex items-center gap-3 min-w-0">
                        <svg class="size-5 shrink-0 text-surface-400 dark:text-surface-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                        </svg>
                        <div>
                            <span class="text-sm font-medium text-surface-900 dark:text-white">Telegram Bot</span>
                            <p class="text-xs text-surface-500 dark:text-surface-400 mt-0.5">Telegram Bot API configuration.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $channelToggles['telegram'] ?? false ? 'bg-success-50 dark:bg-success-500/10 text-success-600 dark:text-success-400' : 'bg-surface-100 dark:bg-surface-700 text-surface-500 dark:text-surface-400' }}">
                            {{ ($channelToggles['telegram'] ?? false) ? 'Enabled' : 'Disabled' }}
                        </span>
                        <svg class="size-4 shrink-0 text-surface-400 dark:text-surface-500 transition-transform duration-200"
                             :class="{ 'rotate-180': open === 'telegram' }"
                             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>
                </button>
                <div x-show="open === 'telegram'" x-collapse.duration.200ms class="px-6 pb-6">
                    <div class="pt-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Bot Token</label>
                            <input wire:model="telegramBotToken" type="password" autocomplete="off" placeholder="123456789:ABC..."
                                class="w-full rounded-xl border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 px-4 py-2.5 text-sm text-surface-900 dark:text-white placeholder:text-surface-400 focus:border-brand-400 focus:ring-brand-500/20 focus:ring-[3px]">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Bot Name</label>
                            <input wire:model="telegramBotName" type="text" autocomplete="off" placeholder="@YourBot"
                                class="w-full rounded-xl border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 px-4 py-2.5 text-sm text-surface-900 dark:text-white placeholder:text-surface-400 focus:border-brand-400 focus:ring-brand-500/20 focus:ring-[3px]">
                        </div>
                    </div>
                </div>
            </div>

            {{-- SMTP Credentials --}}
            <div>
                <button type="button" @click="open = (open === 'smtp' ? null : 'smtp')"
                    class="flex w-full items-center justify-between gap-3 px-6 py-4 text-left transition-colors hover:bg-surface-50 dark:hover:bg-surface-700/50">
                    <div class="flex items-center gap-3 min-w-0">
                        <svg class="size-5 shrink-0 text-surface-400 dark:text-surface-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                        </svg>
                        <div>
                            <span class="text-sm font-medium text-surface-900 dark:text-white">Email SMTP</span>
                            <p class="text-xs text-surface-500 dark:text-surface-400 mt-0.5">SMTP server configuration for email delivery.</p>
                        </div>
                    </div>
                    <svg class="size-4 shrink-0 text-surface-400 dark:text-surface-500 transition-transform duration-200"
                         :class="{ 'rotate-180': open === 'smtp' }"
                         xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>
                <div x-show="open === 'smtp'" x-collapse.duration.200ms class="px-6 pb-6">
                    <div class="pt-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">SMTP Host</label>
                            <input wire:model="smtpHost" type="text" autocomplete="off" placeholder="smtp.example.com"
                                class="w-full rounded-xl border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 px-4 py-2.5 text-sm text-surface-900 dark:text-white placeholder:text-surface-400 focus:border-brand-400 focus:ring-brand-500/20 focus:ring-[3px]">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">SMTP Port</label>
                            <input wire:model="smtpPort" type="number" placeholder="587"
                                class="w-full rounded-xl border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 px-4 py-2.5 text-sm text-surface-900 dark:text-white placeholder:text-surface-400 focus:border-brand-400 focus:ring-brand-500/20 focus:ring-[3px]">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Username</label>
                            <input wire:model="smtpUsername" type="text" autocomplete="off"
                                class="w-full rounded-xl border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 px-4 py-2.5 text-sm text-surface-900 dark:text-white placeholder:text-surface-400 focus:border-brand-400 focus:ring-brand-500/20 focus:ring-[3px]">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Password</label>
                            <input wire:model="smtpPassword" type="password" autocomplete="off"
                                class="w-full rounded-xl border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 px-4 py-2.5 text-sm text-surface-900 dark:text-white placeholder:text-surface-400 focus:border-brand-400 focus:ring-brand-500/20 focus:ring-[3px]">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Encryption</label>
                            <select wire:model="smtpEncryption"
                                class="w-full rounded-xl border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 px-4 py-2.5 text-sm text-surface-900 dark:text-white focus:border-brand-400 focus:ring-brand-500/20 focus:ring-[3px]">
                                <option value="tls">TLS</option>
                                <option value="ssl">SSL</option>
                                <option value="none">None</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">From Email</label>
                            <input wire:model="smtpFromEmail" type="email" autocomplete="off" placeholder="noreply@example.com"
                                class="w-full rounded-xl border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 px-4 py-2.5 text-sm text-surface-900 dark:text-white placeholder:text-surface-400 focus:border-brand-400 focus:ring-brand-500/20 focus:ring-[3px]">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">From Name</label>
                            <input wire:model="smtpFromName" type="text" autocomplete="off" placeholder="Your App Name"
                                class="w-full rounded-xl border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 px-4 py-2.5 text-sm text-surface-900 dark:text-white placeholder:text-surface-400 focus:border-brand-400 focus:ring-brand-500/20 focus:ring-[3px]">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

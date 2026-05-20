{{-- ═══════════════════════════════════════════════════════════════════════
     LOGIN  —  Design: Minimal, clean typography, brand accent system
═══════════════════════════════════════════════════════════════════════ --}}
<div class="space-y-7">

    {{-- ── Header ──────────────────────────────────────────────────────────── --}}
    <div>
        <div class="mx-auto mb-4 flex h-11 w-11 items-center justify-center rounded-2xl
            bg-gradient-to-br from-brand-500/12 to-brand-b-500/12
            shadow-[0_0_20px_rgba(108,59,255,.12)]
        ">
            <svg class="h-5 w-5 text-brand-500" fill="none"
                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
            </svg>
        </div>

        <h2 class="text-[1.625rem] leading-tight font-bold tracking-tight text-surface-900">
            Sign in
        </h2>

        <p class="mt-1.5 text-[0.9375rem] leading-relaxed text-surface-500">
            Do not have an account?
            <a href="{{ route('register') }}" wire:navigate
               class="font-semibold text-brand-500 decoration-brand-500/50 underline-offset-[3px]
                      transition-[color,text-decoration-color]
                      duration-[var(--duration-md)] ease-[var(--ease-out)]
                      hover:text-brand-600 hover:decoration-brand-600
                      focus-visible:outline-none
                      focus-visible:ring-2 focus-visible:ring-brand-400
                      focus-visible:ring-offset-2 focus-visible:ring-offset-white
                      rounded-sm">
                Create an account
            </a>
        </p>
    </div>

    {{-- ── Form ─────────────────────────────────────────────────────────────── --}}
    @if (! $showTwoFactor)

        <form wire:submit="submit" class="space-y-5" novalidate>

            {{-- Username / Email --}}
            <div>
                <label for="login"
                       class="block text-sm font-medium leading-5 text-surface-700 mb-1.5">
                    Username or Email
                </label>
                <input
                    wire:model.blur="login"
                    id="login"
                    type="text"
                    required
                    autocomplete="username"
                    placeholder="you@example.com"
                    class="
                        block w-full rounded-[var(--radius-field)]
                        border border-surface-200
                        bg-surface-50/60
                        px-4 py-2.75
                        text-sm leading-5 text-surface-900
                        placeholder:text-surface-400
                        shadow-[var(--shadow-sm)]
                        transition-[border-color,box-shadow]
                        duration-[var(--duration-md)]
                        ease-[var(--ease-out)]
                        focus:border-brand-400
                        focus:ring-[3px] focus:ring-brand-500/12
                        focus:bg-white
                    "
                >
                @error('login')
                    <p class="mt-1.5 text-xs font-medium leading-4 text-error-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password — with visibility toggle --}}
            <div>
                <label for="password"
                       class="block text-sm font-medium leading-5 text-surface-700 mb-1.5">
                    Password
                </label>
                <div x-data="{ show: false }" class="relative mt-1.5">
                    <input
                        :type="show ? 'text' : 'password'"
                        wire:model.blur="password"
                        id="password"
                        required
                        autocomplete="current-password"
                        class="
                            block w-full rounded-[var(--radius-field)]
                            border border-surface-200
                            bg-surface-50/60
                            px-4 py-2.75 pr-11
                            text-sm leading-5 text-surface-900
                            placeholder:text-surface-400
                            shadow-[var(--shadow-sm)]
                            transition-[border-color,box-shadow]
                            duration-[var(--duration-md)]
                            ease-[var(--ease-out)]
                            focus:border-brand-400
                            focus:ring-[3px] focus:ring-brand-500/12
                            focus:bg-white
                        "
                    >

                    <button
                        type="button"
                        @click="show = !show"
                        class="
                            absolute right-3 top-1/2 -translate-y-1/2
                            text-surface-400
                            transition-colors
                            duration-[var(--duration-md)]
                            ease-[var(--ease-out)]
                            hover:text-surface-600
                            focus-visible:outline-none
                            focus-visible:ring-2 focus-visible:ring-brand-400
                            focus-visible:ring-offset-2
                            focus-visible:ring-offset-white
                            rounded-sm
                            p-0.5
                        "
                        :aria-label="show ? 'Hide password' : 'Show password'"
                        :aria-pressed="show"
                    >
                        {{-- Eye open --}}
                        <svg x-show="!show" class="size-4.5"
                             fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{-- Eye closed --}}
                        <svg x-show="show"
                             x-cloak
                             class="size-4.5"
                             fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="mt-1.5 text-xs font-medium leading-4 text-error-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember me + secondary login options --}}
            <div class="flex items-center justify-between gap-4">
                <label class="flex items-center gap-2 cursor-pointer group">
                    <input wire:model="remember" type="checkbox"
                        class="
                            h-4 w-4 rounded
                            border-surface-300
                            text-brand-500
                            shadow-sm
                            transition-colors
                            duration-[var(--duration-md)]
                            ease-[var(--ease-out)]
                            focus:ring-brand-500/30
                            focus:ring-[2px]
                            focus:ring-offset-0
                            checked:bg-brand-500
                            checked:border-brand-500
                            cursor-pointer
                        "
                    >
                    <span class="text-sm leading-5 text-surface-600
                                 group-hover:text-surface-700
                                 transition-colors duration-200">
                        Remember me
                    </span>
                </label>

                <div class="flex items-center gap-x-3 gap-y-1 flex-wrap justify-end whitespace-nowrap">
                    <a href="{{ route('login.magic') }}" wire:navigate
                       class="text-sm font-medium leading-5 text-brand-500 no-underline
                              transition-[color,text-decoration-color]
                              duration-[var(--duration-md)]
                              ease-[var(--ease-out)]
                              hover:text-brand-600
                              focus-visible:outline-none
                              focus-visible:ring-2 focus-visible:ring-brand-400
                              focus-visible:ring-offset-2 focus-visible:ring-offset-white
                              rounded-sm">
                        Use magic link
                    </a>
                    <span class="text-surface-300" aria-hidden="true">/</span>

                    {{-- Channel selector ─────────────────────────────────────────── --}}
                    <div class="relative" x-data="{ open: @entangle('showChannelSelector') }">
                        <button type="button" @click="open = ! open"
                            class="text-sm font-medium leading-5 text-brand-500 no-underline
                                   transition-[color]
                                   duration-[var(--duration-md)]
                                   ease-[var(--ease-out)]
                                   hover:text-brand-600
                                   focus-visible:outline-none
                                   focus-visible:ring-2 focus-visible:ring-brand-400
                                   focus-visible:ring-offset-2 focus-visible:ring-offset-white
                                   rounded-sm">
                            Use OTP login
                        </button>

                        {{-- Dropdown panel — rendered x-show open ──────────────────── --}}
                        <div x-show="open" @click.outside="open = false; $wire.showChannelSelector = false"
                             x-transition class="absolute right-0 z-50 mt-2 w-56 origin-top-right
                             rounded-lg border border-surface-200 bg-white py-1.5 shadow-lg">
                            @foreach ($channels as $ch)
@php
                                        $labels = [
                                            'sms'      => '📱 SMS',
                                            'whatsapp' => '💬 WhatsApp',
                                            'telegram' => '✈️ Telegram',
                                            'email'    => '✉️ Email OTP',
                                        ];
                                        $routeMap = [
                                            'sms'      => route('login.phone'),
                                            'whatsapp' => route('login.whatsapp'),
                                            'telegram' => route('login.telegram'),
                                            'email'    => route('login.otp'),
                                        ];
                                        $route = $routeMap[$ch->value] ?? route('login.otp');
                                    @endphp
                                <a href="{{ $route }}" wire:navigate
                                   class="block px-3.5 py-2 text-sm text-surface-700
                                          hover:bg-brand-50 hover:text-brand-600 no-underline
                                          transition-colors duration-150">
                                    {{ $labels[$ch->value] ?? ucfirst($ch->value) }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Primary CTA --}}
            <button type="submit"
                class="
                    neon-glow
                    flex w-full items-center justify-center gap-2
                    rounded-[var(--radius-button)]
                    bg-gradient-to-r from-brand-500 to-brand-b-500
                    px-4 py-3
                    text-sm font-semibold leading-5 text-white
                    shadow-[var(--shadow-sm)]
                    transition-[opacity,box-shadow]
                    duration-[var(--duration-md)]
                    ease-[var(--ease-out)]
                    hover:brightness-110
                    hover:shadow-[var(--shadow-md)]
                    focus-visible:outline-none
                    focus-visible:ring-2
                    focus-visible:ring-brand-400
                    focus-visible:ring-offset-2
                    focus-visible:ring-offset-white
                    active:brightness-95
                    min-h-[44px]
                "
            >
                Sign in
                <svg class="size-4" fill="none"
                     viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                </svg>
            </button>

            {{-- Forgot password link --}}
            <div class="text-center">
                <a href="{{ route('password.request') }}" wire:navigate
                   class="text-sm font-medium leading-5 text-surface-500 no-underline
                          transition-[color]
                          duration-[var(--duration-md)]
                          ease-[var(--ease-out)]
                          hover:text-brand-500
                          focus-visible:outline-none
                          focus-visible:ring-2 focus-visible:ring-brand-400
                          focus-visible:ring-offset-2 focus-visible:ring-offset-white
                          rounded-sm">
                    Forgot your password?
                </a>
            </div>

            {{-- Social buttons — dynamic provider grid --}}
            @php
                $availableSocialProviders = app(\App\Services\Auth\ProviderConfigService::class)->getAvailableProviders();
                $socialLabels = [
                    'google' => 'Google', 'github' => 'GitHub', 'facebook' => 'Facebook',
                    'twitter' => 'Twitter', 'linkedin' => 'LinkedIn', 'gitlab' => 'GitLab', 'microsoft' => 'Microsoft',
                ];
            @endphp

            @if (count($availableSocialProviders) > 0)
                {{-- Divider --}}
                <div class="relative pt-1">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-surface-100"></div>
                    </div>
                    <div class="relative flex justify-center text-xs">
                        <span class="bg-white px-3 text-surface-400 font-medium tracking-wide uppercase">
                            Or continue with
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-{{ min(count($availableSocialProviders), 3) }} gap-3">
                    @foreach ($availableSocialProviders as $provider)
                        <a href="{{ route('auth.social.redirect', $provider) }}"
                            class="
                                flex items-center justify-center gap-2
                                rounded-[var(--radius-field)]
                                border border-surface-200
                                bg-white
                                px-3 py-2.5
                                text-sm font-medium text-surface-700
                                shadow-[var(--shadow-sm)]
                                no-underline
                                transition-[border-color,background-color,box-shadow]
                                duration-[var(--duration-md)]
                                ease-[var(--ease-out)]
                                hover:border-surface-300 hover:bg-surface-50 hover:shadow-[var(--shadow-md)]
                                focus-visible:outline-none
                                focus-visible:ring-2 focus-visible:ring-brand-400
                                focus-visible:ring-offset-2 focus-visible:ring-offset-white
                            ">
                            <x-ui-icon :name="$provider" class="h-5 w-5 shrink-0" />
                            <span class="hidden sm:inline">{{ $socialLabels[$provider] ?? ucfirst($provider) }}</span>
                        </a>
                    @endforeach
                </div>
            @endif
        </form>

    @else

        {{-- ── Two-Factor Challenge ────────────────────────────────────────────── --}}
        <div class="space-y-5">
            <div>
                <p class="text-sm font-medium text-surface-700 mb-1.5">
                    Authentication Code
                </p>
                <input
                    wire:model="twoFactorCode"
                    id="twoFactorCode"
                    type="text"
                    maxlength="6"
                    inputmode="numeric"
                    autocomplete="one-time-code"
                    placeholder="000000"
                    class="
                        block w-full rounded-[var(--radius-field)]
                        border border-surface-200
                        bg-surface-50/60
                        px-4 py-3.5
                        text-center text-2xl leading-none
                        tracking-[0.3em] font-mono
                        text-surface-900
                        placeholder:text-surface-400
                        shadow-[var(--shadow-sm)]
                        transition-[border-color,box-shadow]
                        duration-[var(--duration-md)]
                        ease-[var(--ease-out)]
                        focus:border-brand-400
                        focus:ring-[3px] focus:ring-brand-500/12
                        focus:bg-white
                    "
                >
                @error('twoFactorCode')
                    <p class="mt-1.5 text-xs font-medium text-error-500">{{ $message }}</p>
                @enderror
            </div>

            <button wire:click="verifyTwoFactor"
                class="
                    neon-glow
                    flex w-full items-center justify-center gap-2
                    rounded-[var(--radius-button)]
                    bg-gradient-to-r from-brand-500 to-brand-b-500
                    px-4 py-3
                    text-sm font-semibold leading-5 text-white
                    shadow-[var(--shadow-sm)]
                    transition-[opacity]
                    duration-[var(--duration-md)]
                    ease-[var(--ease-out)]
                    hover:brightness-110
                    focus-visible:outline-none
                    focus-visible:ring-2
                    focus-visible:ring-brand-400
                    focus-visible:ring-offset-2
                    focus-visible:ring-offset-white
                    active:brightness-95
                    min-h-[44px]
                "
            >
                Verify
                <svg class="size-4" fill="none"
                     viewBox="0 0 24 24"
                     stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </button>
        </div>

    @endif
</div>

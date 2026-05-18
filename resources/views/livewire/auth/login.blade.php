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
                    <a href="{{ route('login.otp') }}" wire:navigate
                       class="text-sm font-medium leading-5 text-brand-500 no-underline
                              transition-[color,text-decoration-color]
                              duration-[var(--duration-md)]
                              ease-[var(--ease-out)]
                              hover:text-brand-600
                              focus-visible:outline-none
                              focus-visible:ring-2 focus-visible:ring-brand-400
                              focus-visible:ring-offset-2 focus-visible:ring-offset-white
                              rounded-sm">
                        Use OTP login
                    </a>
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

            {{-- Social buttons — 3-column grid --}}
            <div class="grid grid-cols-3 gap-3">
                {{-- Google --}}
                <a href="{{ route('auth.social.redirect', 'google') }}"
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
                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24">
                        <path fill="#4285F4"
                              d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/>
                        <path fill="#34A853"
                              d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05"
                              d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335"
                              d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    <span class="hidden sm:inline">Google</span>
                </a>

                {{-- GitHub --}}
                <a href="{{ route('auth.social.redirect', 'github') }}"
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
                    <svg class="h-5 w-5 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205
                                 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015
                                 -2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72
                                 -1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015
                                 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105
                                 -.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925
                                 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0
                                 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135
                                 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12
                                 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625
                                 -5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895
                                 -.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12
                                 c0-6.63-5.37-12-12-12z"/>
                    </svg>
                    <span class="hidden sm:inline">GitHub</span>
                </a>

                {{-- Facebook --}}
                <a href="{{ route('auth.social.redirect', 'facebook') }}"
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
                    <svg class="h-5 w-5 shrink-0 text-[#1877F2]" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12
                                 c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h
                                 3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686
                                 .235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956
                                 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027
                                 24 18.062 24 12.073z"/>
                    </svg>
                    <span class="hidden sm:inline">Facebook</span>
                </a>
            </div>
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

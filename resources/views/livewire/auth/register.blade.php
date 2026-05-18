{{-- ═══════════════════════════════════════════════════════════════════════
     REGISTER  —  Minimal, clean, brand-accent system
═══════════════════════════════════════════════════════════════════════ --}}
<div class="space-y-6">

    {{-- ── Header ──────────────────────────────────────────────────────────── --}}
    <div class="text-center">
        <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-2xl
            bg-gradient-to-br from-brand-500/12 to-brand-b-500/12
            shadow-[0_0_20px_rgba(108,59,255,.12)]
        ">
            <svg class="h-6 w-6 text-brand-500" fill="none"
                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25
                         2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25
                         2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
            </svg>
        </div>

        <h2 class="text-[1.625rem] leading-tight font-bold tracking-tight text-surface-900">
            Create an account
        </h2>

        <p class="mt-1.5 text-[0.9375rem] leading-relaxed text-surface-500">
            Already have an account?
            <a href="{{ route('login') }}" wire:navigate
               class="font-semibold text-brand-500 decoration-brand-500/50 underline-offset-[3px]
                      transition-[color,text-decoration-color]
                      duration-[var(--duration-md)]
                      ease-[var(--ease-out)]
                      hover:text-brand-600 hover:decoration-brand-600
                      focus-visible:outline-none
                      focus-visible:ring-2 focus-visible:ring-brand-400
                      focus-visible:ring-offset-2 focus-visible:ring-offset-white
                      rounded-sm">
                Login
            </a>
        </p>
    </div>

    {{-- ── Step indicator ───────────────────────────────────────────────────── --}}
    <div class="flex items-center gap-2">
        @foreach ([1 => 'Email', 2 => 'Password', 3 => 'Details'] as $num => $label)
            <div class="flex items-center gap-2">
                <div @class([
                    'flex size-7 shrink-0 items-center justify-center
                     rounded-full text-[11px] font-bold
                     transition-all duration-[var(--duration-md)] ease-[var(--ease-out)]',
                    'bg-gradient-to-r from-brand-500 to-brand-b-500 text-white
                     shadow-[0_0_14px_rgba(108,59,255,.30)]' => $step === $num,
                    'bg-brand-500/12 text-brand-500'                            => $step > $num,
                    'bg-surface-200 text-surface-400'                          => $step < $num,
                ])>
                    @if ($step > $num)
                        <svg class="size-3.5" fill="none"
                             viewBox="0 0 24 24"
                             stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                    @else
                        {{ $num }}
                    @endif
                </div>
                <span @class([
                    'text-xs font-medium hidden sm:inline transition-colors',
                    'text-brand-500'       => $step === $num,
                    'text-surface-700'     => $step > $num,
                    'text-surface-400'     => $step < $num,
                ])>{{ $label }}</span>
            </div>
            @if (! $loop->last)
                <div @class([
                    'h-px flex-1 transition-colors duration-[var(--duration-md)]',
                    'bg-gradient-to-r from-brand-500 to-brand-b-500' => $step > $num,
                    'bg-surface-200'                                  => $step <= $num,
                ])></div>
            @endif
        @endforeach
    </div>

    {{-- ════════════════════════════════════════════════════════════════════
         STEP 1 — Email & Username
    ════════════════════════════════════════════════════════════════════ --}}
    @if ($step === 1)
        <div class="space-y-5">
            <div>
                <label for="email"
                       class="block text-sm font-medium leading-5 text-surface-700 mb-1.5">
                    Email
                </label>
                <input
                    wire:model.live.debounce.300ms="email"
                    id="email"
                    type="email"
                    required
                    autocomplete="email"
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
                @error('email')
                    <p class="mt-1.5 text-xs font-medium text-error-500">{{ $message }}</p>
                @enderror

                @if ($emailExists)
                    <div class="mt-3 rounded-xl border border-warning-200 bg-warning-50 p-4">
                        <p class="text-sm leading-5 font-medium text-surface-800">
                            An account with this email already exists.
                        </p>
                        <div class="mt-2 flex gap-4 text-sm">
                            <a href="{{ route('login') }}" wire:navigate
                               class="font-semibold text-brand-500 hover:text-brand-600
                                      transition-colors duration-200">Sign in</a>
                            <a href="{{ route('password.request') }}" wire:navigate
                               class="font-semibold text-brand-500 hover:text-brand-600
                                      transition-colors duration-200">Reset password</a>
                        </div>
                    </div>
                @endif
            </div>

            <div>
                <label for="username"
                       class="block text-sm font-medium leading-5 text-surface-700 mb-1.5">
                    Username
                </label>
                <input
                    wire:model.blur="username"
                    id="username"
                    type="text"
                    required
                    autocomplete="username"
                    placeholder="johndoe"
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
                @error('username')
                    <p class="mt-1.5 text-xs font-medium text-error-500">{{ $message }}</p>
                @enderror
            </div>

            <button type="button" wire:click="nextStep"
                @disabled($emailExists || blank($email))
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
                    disabled:cursor-not-allowed disabled:opacity-45 disabled:hover:brightness-100
                    min-h-[44px]
                "
            >
                Continue
                <svg class="size-4" fill="none"
                     viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                </svg>
            </button>

            <div class="relative pt-1">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-surface-100"></div>
                </div>
                <div class="relative flex justify-center text-xs">
                    <span class="bg-white px-3 text-surface-400 font-medium tracking-wide uppercase">
                        Or register with
                    </span>
                </div>
            </div>

            <a href="{{ route('auth.social.redirect', 'google') }}"
                class="
                    flex w-full items-center justify-center gap-3
                    rounded-[var(--radius-field)]
                    border border-surface-200
                    bg-white px-4 py-2.5
                    text-sm font-medium text-surface-700
                    shadow-[var(--shadow-sm)]
                    no-underline
                    transition-[border-color,background-color,box-shadow]
                    duration-[var(--duration-md)]
                    ease-[var(--ease-out)]
                    hover:border-surface-300 hover:bg-surface-50 hover:shadow-[var(--shadow-md)]
                    focus-visible:outline-none
                    focus-visible:ring-2
                    focus-visible:ring-brand-400
                    focus-visible:ring-offset-2 focus-visible:ring-offset-white
                "
            >
                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Create account with Google
            </a>
        </div>
    @endif

    {{-- ════════════════════════════════════════════════════════════════════
         STEP 2 — Password
    ════════════════════════════════════════════════════════════════════ --}}
    @if ($step === 2)
        <div class="space-y-5" wire:key="step-2">
            <div>
                {{-- Password input --}}
                <label for="password"
                       class="block text-sm font-medium leading-5 text-surface-700 mb-1.5">
                    Password
                </label>
                <div x-data="{ show: false }" class="relative mt-1.5">
                    <input
                        :type="show ? 'text' : 'password'"
                        wire:model.live="password"
                        id="password"
                        required
                        autocomplete="new-password"
                        placeholder="Create a strong password"
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
                    <button @click="show = !show" type="button"
                        class="
                            absolute right-3 top-1/2 -translate-y-1/2
                            text-surface-400
                            transition-colors duration-200
                            hover:text-surface-600
                            focus-visible:outline-none
                            focus-visible:ring-2 focus-visible:ring-brand-400
                            focus-visible:ring-offset-2
                            focus-visible:ring-offset-surface-50
                            rounded-sm p-0.5
                        "
                        :aria-label="show ? 'Hide password' : 'Show password'"
                        :aria-pressed="show"
                    >
                        <svg x-show="!show" class="size-4.5"
                             fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51
                                     7.36 4.5 12 4.5c4.638 0 8.573 3.007
                                     9.963 7.178.07.207.07.431 0 .639
                                     C20.577 16.49 16.64 19.5 12 19.5c-4.638 0
                                     -8.573-3.007-9.963-7.178z"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <svg x-show="show" x-cloak class="size-4.5"
                             fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226
                                     16.338 7.244 19.5 12 19.5c.993 0 1.953-.138
                                     2.863-.395M6.228 6.228A10.45 10.45 0 0112
                                     4.5c4.756 0 8.773 3.162 10.065 7.498a10.523
                                     10.523 0 01-4.293 5.774M6.228 6.228L3
                                     3m3.228 3.228l3.65 3.65m7.894 7.894L21
                                     21m-3.228-3.228l-3.65-3.65m0 0a3 3 0
                                     10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                        </svg>
                    </button>
                </div>

                {{-- Strength bar --}}
                @if ($passwordScore > 0)
                    @php
                        $barGradient = match ($passwordScore) {
                            0, 1 => 'from-error-500 to-error-600',
                            2, 3 => 'from-warning-500 to-warning-600',
                            4      => 'from-brand-l-300 to-brand-l-400',
                            5      => 'from-success-500 to-success-600',
                            default => 'from-surface-200 to-surface-300',
                        };
                        $barTextColor = match ($passwordLabel) {
                            'Weak'      => 'text-error-500',
                            'Fair'      => 'text-warning-500',
                            'Good'      => 'text-brand-l-300',
                            'Strong'    => 'text-success-500',
                            default     => 'text-surface-500',
                        };
                    @endphp
                    <div class="mt-3 space-y-1.5">
                        <div class="h-1.5 w-full overflow-hidden rounded-full bg-surface-100">
                            <div class="
                                h-full rounded-full
                                bg-gradient-to-r {{ $barGradient }}
                                transition-all duration-500 ease-out
                            " style="width:{{ ($passwordScore / 5) * 100 }}%"></div>
                        </div>
                        <p class="text-xs font-semibold leading-4 {{ $barTextColor }}">
                            {{ $passwordLabel }}
                        </p>
                    </div>
                @endif

                {{-- Criteria checklist --}}
                <ul class="mt-3 space-y-1.5">
                    @foreach ([
                        ['key' => 'length',    'label' => 'At least 8 characters'],
                        ['key' => 'uppercase', 'label' => 'One uppercase letter'],
                        ['key' => 'lowercase', 'label' => 'One lowercase letter'],
                        ['key' => 'digit',     'label' => 'One number'],
                        ['key' => 'special',   'label' => 'One special character'],
                    ] as $item)
                        <li class="flex items-center gap-2 text-sm">
                            @if ($passwordCriteria[$item['key']])
                                <svg class="size-3.5 shrink-0 text-success-500" fill="none"
                                     viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M4.5 12.75l6 6 9-13.5"/>
                                </svg>
                                <span class="text-surface-700">{{ $item['label'] }}</span>
                            @else
                                <svg class="size-3.5 shrink-0 text-surface-300" fill="none"
                                     viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 4.5v15m7.5-7.5h-15"/>
                                </svg>
                                <span class="text-surface-400">{{ $item['label'] }}</span>
                            @endif
                        </li>
                    @endforeach
                </ul>

                @error('password')
                    <p class="mt-1.5 text-xs font-medium text-error-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="passwordConfirmation"
                       class="block text-sm font-medium leading-5 text-surface-700 mb-1.5">
                    Confirm Password
                </label>
                <div x-data="{ show: false }" class="relative mt-1.5">
                    <input
                        :type="show ? 'text' : 'password'"
                        wire:model.live="passwordConfirmation"
                        id="passwordConfirmation"
                        required
                        autocomplete="new-password"
                        placeholder="Repeat your password"
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
                    <button @click="show = !show" type="button"
                        class="
                            absolute right-3 top-1/2 -translate-y-1/2
                            text-surface-400
                            transition-colors duration-200
                            hover:text-surface-600
                            focus-visible:outline-none
                            focus-visible:ring-2 focus-visible:ring-brand-400
                            focus-visible:ring-offset-2
                            focus-visible:ring-offset-surface-50
                            rounded-sm p-0.5
                        "
                        :aria-label="show ? 'Hide password' : 'Show password'"
                        :aria-pressed="show"
                    >
                        <svg x-show="!show" class="size-4.5"
                             fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M2.036 12.322a1.012 1.012 0 010-.639C3.423
                                     7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007
                                     9.963 7.178.07.207.07.431 0 .639
                                     C20.577 16.49 16.64 19.5 12 19.5c-4.638 0
                                     -8.573-3.007-9.963-7.178z"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <svg x-show="show" x-cloak class="size-4.5"
                             fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3.98 8.223A10.477 10.477 0 001.934
                                     12C3.226 16.338 7.244 19.5 12 19.5c.993 0
                                     1.953-.138 2.863-.395M6.228 6.228A10.45
                                     10.45 0 0112 4.5c4.756 0 8.773 3.162
                                     10.065 7.498a10.523 10.523 0 01-4.293
                                     5.774M6.228 6.228L3 3m3.228
                                     3.228l3.65 3.65m7.894 7.894L21
                                     21m-3.228-3.228l-3.65-3.65m0
                                     0a3 3 0 10-4.243-4.243m4.242
                                     4.242L9.88 9.88"/>
                        </svg>
                    </button>
                </div>
                @error('passwordConfirmation')
                    <p class="mt-1.5 text-xs font-medium text-error-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Breach check feedback --}}
            @if ($breachChecking)
                <div class="flex items-center gap-2 text-xs text-surface-400" aria-hidden="true">
                    <svg class="size-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                    Checking password against known data breaches…
                </div>
            @elseif ($breachChecked)
                @if ($breachCount > 0)
                    <div class="rounded-xl border border-error-200 bg-error-50 p-4" role="alert">
                        <div class="flex items-start gap-2.5">
                            <svg class="mt-0.5 size-4 shrink-0 text-error-500" fill="none"
                                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374
                                         1.948 3.374h14.71c1.73 0 2.813-1.874
                                         1.948-3.374L13.949 3.378c-.866-1.5-3.032
                                         -1.5-3.898 0L2.697 16.126zM12 15.75h.007
                                         v.008H12v-.008z"/>
                            </svg>
                            <div>
                                <p class="text-sm leading-5 font-medium text-error-800">
                                    {{ trans_choice(
                                        'This password was found in :count data breach|This
                                         password was found in :count data breaches.',
                                        $breachCount, ['count' => $breachCount]) }}
                                </p>
                                <p class="mt-0.5 text-xs leading-4 text-error-600">
                                    Choose a different password for better security.
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-2 text-xs text-success-500">
                        <svg class="size-4 shrink-0" fill="none"
                             viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0
                                     11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Password not found in known data breaches
                    </div>
                @endif
            @endif

            {{-- Navigation --}}
            <div class="flex gap-3">
                <button type="button" wire:click="previousStep"
                    class="
                        flex-1
                        rounded-[var(--radius-button)]
                        border border-surface-200
                        bg-white
                        px-4 py-2.5
                        text-sm font-semibold leading-5 text-surface-700
                        shadow-[var(--shadow-sm)]
                        transition-[border-color,background-color,box-shadow]
                        duration-[var(--duration-md)]
                        ease-[var(--ease-out)]
                        hover:border-surface-300 hover:bg-surface-50
                        focus-visible:outline-none
                        focus-visible:ring-2
                        focus-visible:ring-brand-400
                        focus-visible:ring-offset-2
                        focus-visible:ring-offset-white
                        min-h-[44px]
                    "
                >
                    Back
                </button>
                <button type="button" wire:click="nextStep"
                    @disabled(blank($password))
                    class="
                        neon-glow
                        flex flex-1 items-center justify-center gap-2
                        rounded-[var(--radius-button)]
                        bg-gradient-to-r from-brand-500 to-brand-b-500
                        px-4 py-2.5
                        text-sm font-semibold leading-5 text-white
                        shadow-[var(--shadow-sm)]
                        transition-[opacity,box-shadow]
                        duration-[var(--duration-md)]
                        ease-[var(--ease-out)]
                        hover:brightness-110 hover:shadow-[var(--shadow-md)]
                        focus-visible:outline-none
                        focus-visible:ring-2
                        focus-visible:ring-brand-400
                        focus-visible:ring-offset-2
                        focus-visible:ring-offset-white
                        active:brightness-95
                        disabled:cursor-not-allowed
                        disabled:opacity-45
                        disabled:hover:brightness-100
                        min-h-[44px]
                    "
                >
                    Continue
                    <svg class="size-4" fill="none"
                         viewBox="0 0 24 24"
                         stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    {{-- ════════════════════════════════════════════════════════════════════
         STEP 3 — Profile Details
    ════════════════════════════════════════════════════════════════════ --}}
    @if ($step === 3)
        <div class="space-y-5" wire:key="step-3">
            <div>
                <label for="name"
                       class="block text-sm font-medium leading-5 text-surface-700 mb-1.5">
                    Full Name <span class="text-error-500" aria-hidden="true">*</span>
                </label>
                <input
                    wire:model.blur="name"
                    id="name"
                    type="text"
                    required
                    autocomplete="name"
                    placeholder="John Doe"
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
                @error('name')
                    <p class="mt-1.5 text-xs font-medium text-error-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="phone"
                       class="block text-sm font-medium leading-5 text-surface-700 mb-1.5">
                    Phone <span class="text-surface-400 font-normal">(optional)</span>
                </label>
                <input
                    wire:model.blur="phone"
                    id="phone"
                    type="tel"
                    autocomplete="tel"
                    placeholder="+1 (555) 000-0000"
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
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-4">
                <div class="sm:col-span-2">
                    <label for="address_line_1"
                           class="block text-sm font-medium leading-5 text-surface-700 mb-1.5">
                        Address <span class="text-surface-400 font-normal">(optional)</span>
                    </label>
                    <input
                        wire:model.blur="address_line_1"
                        id="address_line_1"
                        type="text"
                        autocomplete="address-line1"
                        placeholder="123 Main St"
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
                </div>

                <div class="sm:col-span-2">
                    <label for="address_line_2"
                           class="block text-sm font-medium leading-5 text-surface-700 mb-1.5">
                        Apt / Suite <span class="text-surface-400 font-normal">(optional)</span>
                    </label>
                    <input
                        wire:model.blur="address_line_2"
                        id="address_line_2"
                        type="text"
                        autocomplete="address-line2"
                        placeholder="Apt 4B"
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
                </div>

                <div>
                    <label for="city"
                           class="block text-sm font-medium leading-5 text-surface-700 mb-1.5">
                        City
                    </label>
                    <input
                        wire:model.blur="city"
                        id="city"
                        type="text"
                        autocomplete="address-level2"
                        placeholder="City"
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
                </div>

                <div>
                    <label for="state"
                           class="block text-sm font-medium leading-5 text-surface-700 mb-1.5">
                        State
                    </label>
                    <input
                        wire:model.blur="state"
                        id="state"
                        type="text"
                        autocomplete="address-level1"
                        placeholder="State"
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
                </div>

                <div>
                    <label for="postal_code"
                           class="block text-sm font-medium leading-5 text-surface-700 mb-1.5">
                        Postal Code
                    </label>
                    <input
                        wire:model.blur="postal_code"
                        id="postal_code"
                        type="text"
                        autocomplete="postal-code"
                        placeholder="12345"
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
                </div>

                <div>
                    <label for="country"
                           class="block text-sm font-medium leading-5 text-surface-700 mb-1.5">
                        Country
                    </label>
                    <input
                        wire:model.blur="country"
                        id="country"
                        type="text"
                        autocomplete="country-name"
                        placeholder="United States"
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
                </div>
            </div>

            <div>
                <label for="company"
                       class="block text-sm font-medium leading-5 text-surface-700 mb-1.5">
                    Company <span class="text-surface-400 font-normal">(optional)</span>
                </label>
                <input
                    wire:model.blur="company"
                    id="company"
                    type="text"
                    autocomplete="organization"
                    placeholder="Acme Inc."
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
            </div>

            {{-- Navigation --}}
            <div class="flex gap-3">
                <button type="button" wire:click="previousStep"
                    class="
                        flex-1
                        rounded-[var(--radius-button)]
                        border border-surface-200
                        bg-white
                        px-4 py-2.5
                        text-sm font-semibold leading-5 text-surface-700
                        shadow-[var(--shadow-sm)]
                        transition-[border-color,background-color,box-shadow]
                        duration-[var(--duration-md)]
                        ease-[var(--ease-out)]
                        hover:border-surface-300 hover:bg-surface-50
                        focus-visible:outline-none
                        focus-visible:ring-2
                        focus-visible:ring-brand-400
                        focus-visible:ring-offset-2
                        focus-visible:ring-offset-white
                        min-h-[44px]
                    "
                >
                    Back
                </button>
                <button type="button" wire:click="submit"
                    class="
                        neon-glow
                        flex flex-1 items-center justify-center gap-2
                        rounded-[var(--radius-button)]
                        bg-gradient-to-r from-brand-500 to-brand-b-500
                        px-4 py-2.5
                        text-sm font-semibold leading-5 text-white
                        shadow-[var(--shadow-sm)]
                        transition-[opacity,box-shadow]
                        duration-[var(--duration-md)]
                        ease-[var(--ease-out)]
                        hover:brightness-110 hover:shadow-[var(--shadow-md)]
                        focus-visible:outline-none
                        focus-visible:ring-2
                        focus-visible:ring-brand-400
                        focus-visible:ring-offset-2
                        focus-visible:ring-offset-white
                        active:brightness-95
                        min-h-[44px]
                    "
                >
                    Create account
                    <svg class="size-4" fill="none"
                         viewBox="0 0 24 24"
                         stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 12.75L11.25 15 15 9.75M21
                                 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    {{-- ── Social footer — all steps ─────────────────────────────────────────── --}}
    <div class="pt-2 border-t border-surface-100">
        <div class="flex items-center justify-center gap-4">
            <span class="text-xs text-surface-400 font-medium tracking-wide uppercase">Follow us</span>
            <div class="flex gap-3">
                <a href="#" class="text-surface-300 hover:text-[#1877F2]
                                  transition-colors duration-200" aria-label="Facebook">
                    <svg class="size-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12
                                 5.373-12 12c0 5.99 4.388 10.954 10.125
                                 11.854v-8.385H7.078v-3.47h3.047V9.43c0
                                 -3.007 1.792-4.669 4.533-4.669 1.312 0
                                 2.686.235 2.686.235v2.953H15.83c-1.491
                                 0-1.956.925-1.956 1.874v2.25h3.328l-.532
                                 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                </a>
                <a href="#" class="text-surface-300 hover:text-[#1DA1F2]
                                  transition-colors duration-200" aria-label="Twitter / X">
                    <svg class="size-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M18.244 2.25h3.308l-7.227
                                 8.26 8.502 11.24H16.17l-5.214-6.817L4.99
                                 21.75H1.68l7.73-8.835L1.254
                                 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084
                                 4.126H5.117z"/>
                    </svg>
                </a>
                <a href="#" class="text-surface-300 hover:text-[#E4405F]
                                  transition-colors duration-200" aria-label="Instagram">
                    <svg class="size-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2.163c3.204 0 3.584.012
                                 4.85.07 3.252.148 4.771 1.691 4.919
                                 4.919.058 1.265.069 1.645.069 4.849
                                 0 3.205-.012 3.584-.069 4.849-.149
                                 3.227-1.664 4.771-4.919 4.919-1.266.058
                                 -1.644.07-4.85.07-3.204 0-3.584-.012-4.849
                                 -.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265
                                 -.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849
                                 .149-3.227 1.664-4.771 4.919-4.919 1.266-.057
                                 1.645-.069 4.849-.069zm0-2C8.741 0 8.333
                                 .014 7.053.072 2.695.272.273 2.69.073
                                 7.052.014 8.333 0 8.741 0 12c0
                                 3.259.014 3.668.072 4.948.2 4.358
                                 2.618 6.78 6.98 6.98C8.333 23.986
                                 8.741 24 12 24c3.259 0 3.668-.014
                                 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98
                                 .059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667
                                 -.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668
                                 .014 15.259 0 12 0zm0 5.838c3.403 0 6.162
                                 2.75 6.162 6.162S15.403 17.162 12 17.162
                                 5.838 14.412 5.838 11S8.597 5.838 12 5.838zM12
                                 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845
                                 a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                    </svg>
                </a>
                <a href="#" class="text-surface-300 hover:text-[#0A66C2]
                                  transition-colors duration-200" aria-label="LinkedIn">
                    <svg class="size-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20.447 20.452h-3.554v-5.569
                                 c0-1.328-.027-3.037-1.852-3.037-1.853
                                 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414
                                 v1.561h.046c.477-.9 1.637-1.85 3.37-1.85
                                 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337
                                 7.433a2.062 2.062 0 01-2.063-2.065 2.064
                                 2.064 0 112.063 2.065zm1.782
                                 13.019H3.555V9h3.564v11.452zM22.225
                                 0H1.771C.792 0 0 .774 0 1.729v20.542
                                 C0 23.227.792 24 1.771 24h20.451
                                 C23.2 24 24 23.227 24 22.271V1.729
                                 C24 .774 23.2 0 22.222 0h.003z"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>

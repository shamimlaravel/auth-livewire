{{-- ── Phone Login — SMS OTP ────────────────────────────────────────────────── --}}
<div class="space-y-6">
    <div>
        <div class="mx-auto mb-4 flex h-11 w-11 items-center justify-center rounded-2xl
                    bg-brand-500/12 shadow-[0_0_20px_rgba(108,59,255,.12)]">
            <svg class="h-5 w-5 text-brand-500" fill="none"
                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5A2.25 2.25 0 008.25
                         21h7.5A2.25 2.25 0 0018 18.75V3.75A2.25 2.25 0 0015.75 1.5h-2.5
                         m0 0L12 6m0 0L9.5 1.5M12 6v6"/>
            </svg>
        </div>

        <h2 class="text-[1.625rem] leading-tight font-bold tracking-tight text-surface-900">
            {{ __('auth.phone_login_title') }}
        </h2>

        <p class="mt-1.5 text-[0.9375rem] leading-relaxed text-surface-500">
            {{ __('auth.phone_login_intro') }}
        </p>
    </div>

    @if ($status)
        <div class="rounded-xl border border-success-200 bg-success-50 p-4 text-sm text-success-700">
            {{ $status }}
        </div>
    @endif

    @if (! $showOtpInput)
        <form wire:submit="sendOtp" class="space-y-5" novalidate>
            <div>
                <label for="phone" class="block text-sm font-medium leading-5 text-surface-700 mb-1.5">
                    {{ __('auth.phone_number') }}
                </label>
                <input
                    wire:model.blur="phone"
                    id="phone"
                    type="tel"
                    required
                    autocomplete="tel"
                    placeholder="+1-555-000-0000"
                    class="
                        block w-full rounded-[var(--radius-field)]
                        border border-surface-200
                        bg-surface-50/60
                        px-4 py-2.75
                        text-sm leading-5 text-surface-900
                        placeholder:text-surface-400
                        shadow-[var(--shadow-sm)]
                        transition-[border-color,box-shadow]
                        duration-[var(--duration-md)] ease-[var(--ease-out)]
                        focus:border-brand-400
                        focus:ring-[3px] focus:ring-brand-500/12
                        focus:bg-white
                    "
                >
                @error('phone')
                    <p class="mt-1.5 text-xs font-medium leading-4 text-error-500">{{ $message }}</p>
                @enderror
            </div>

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
                    duration-[var(--duration-md)] ease-[var(--ease-out)]
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
                {{ __('auth.phone_login_button') }}
            </button>

            <div class="flex items-center justify-center gap-4 text-sm">
                <a href="{{ route('login') }}" wire:navigate
                   class="font-semibold text-brand-500 no-underline transition-[color]
                          duration-[var(--duration-md)] ease-[var(--ease-out)]
                          hover:text-brand-600 focus-visible:outline-none
                          focus-visible:ring-2 focus-visible:ring-brand-400
                          focus-visible:ring-offset-2 focus-visible:ring-offset-white rounded-sm">
                    {{ __('auth.use_password_instead') }}
                </a>
                <span class="text-surface-300" aria-hidden="true">|</span>
                <a href="{{ route('login.otp') }}" wire:navigate
                   class="font-semibold text-brand-500 no-underline transition-[color]
                          duration-[var(--duration-md)] ease-[var(--ease-out)]
                          hover:text-brand-600 focus-visible:outline-none
                          focus-visible:ring-2 focus-visible:ring-brand-400
                          focus-visible:ring-offset-2 focus-visible:ring-offset-white rounded-sm">
                    {{ __('auth.otp_login_link') }}
                </a>
            </div>
        </form>
    @else
        <div class="space-y-5">
            <div>
                <label for="code" class="block text-sm font-medium leading-5 text-surface-700 mb-1.5">
                    {{ __('auth.otp_code_label') }}
                </label>
                <input
                    wire:model.blur="code"
                    id="code"
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
                        duration-[var(--duration-md)] ease-[var(--ease-out)]
                        focus:border-brand-400
                        focus:ring-[3px] focus:ring-brand-500/12
                        focus:bg-white
                    "
                >
                @error('code')
                    <p class="mt-1.5 text-xs font-medium leading-4 text-error-500">{{ $message }}</p>
                @enderror
            </div>

            <button wire:click="verifyOtp" type="button"
                class="
                    neon-glow
                    flex w-full items-center justify-center gap-2
                    rounded-[var(--radius-button)]
                    bg-gradient-to-r from-brand-500 to-brand-b-500
                    px-4 py-3
                    text-sm font-semibold leading-5 text-white
                    shadow-[var(--shadow-sm)]
                    transition-[opacity,box-shadow]
                    duration-[var(--duration-md)] ease-[var(--ease-out)]
                    hover:brightness-110 hover:shadow-[var(--shadow-md)]
                    focus-visible:outline-none focus-visible:ring-2
                    focus-visible:ring-brand-400 focus-visible:ring-offset-2
                    focus-visible:ring-offset-white active:brightness-95
                    min-h-[44px]
                "
            >
                {{ __('auth.otp_verify_button') }}
            </button>

            <div class="flex items-center justify-between">
                <button type="button" wire:click="back"
                    class="text-sm font-semibold text-brand-500 no-underline transition-[color]
                           duration-[var(--duration-md)] ease-[var(--ease-out)]
                           hover:text-brand-600 focus-visible:outline-none
                           focus-visible:ring-2 focus-visible:ring-brand-400
                           focus-visible:ring-offset-2 focus-visible:ring-offset-white rounded-sm">
                    {{ __('auth.back') }}
                </button>
                <button type="button" wire:click="resendOtp"
                    class="text-sm font-semibold text-brand-500 no-underline transition-[color]
                           duration-[var(--duration-md)] ease-[var(--ease-out)]
                           hover:text-brand-600 focus-visible:outline-none
                           focus-visible:ring-2 focus-visible:ring-brand-400
                           focus-visible:ring-offset-2 focus-visible:ring-offset-white rounded-sm">
                    {{ __('auth.otp_resend') }}
                </button>
            </div>
        </div>
    @endif
</div>

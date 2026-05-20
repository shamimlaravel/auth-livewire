{{-- ── WhatsApp Login ────────────────────────────────────────────────────────── --}}
<div class="space-y-6">
    <div>
        <div class="mx-auto mb-4 flex h-11 w-11 items-center justify-center rounded-2xl
                    bg-brand-500/12 shadow-[0_0_20px_rgba(108,59,255,.12)]">
            <svg class="h-5 w-5 text-brand-500" fill="none"
                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0
                          H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0
                          H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0
                          h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555
                          -.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474
                          -.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226
                          C3.2 17.18 3 16.55 3 16c0-4.556 4.03-8.25 9-8.25s9 3.694
                          9 8.25z"/>
            </svg>
        </div>

        <h2 class="text-[1.625rem] leading-tight font-bold tracking-tight text-surface-900">
            {{ __('auth.whatsapp_login_title') }}
        </h2>

        <p class="mt-1.5 text-[0.9375rem] leading-relaxed text-surface-500">
            {{ __('auth.whatsapp_login_intro') }}
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
                <label for="whatsapp_number" class="block text-sm font-medium leading-5 text-surface-700 mb-1.5">
                    {{ __('auth.whatsapp_number') }}
                </label>
                <input
                    wire:model.blur="whatsapp_number"
                    id="whatsapp_number"
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
                @error('whatsapp_number')
                    <p class="mt-1.5 text-xs font-medium leading-4 text-error-500">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="
                    neon-glow
                    flex w-full items-center justify-center gap-2
                    rounded-[var(--radius-button)]
                    bg-[#25D366] hover:bg-[#20BD5A]
                    px-4 py-3
                    text-sm font-semibold leading-5 text-white
                    shadow-[var(--shadow-sm)]
                    transition-[opacity,box-shadow]
                    duration-[var(--duration-md)] ease-[var(--ease-out)]
                    hover:brightness-110 hover:shadow-[var(--shadow-md)]
                    focus-visible:outline-none
                    focus-visible:ring-2
                    focus-visible:ring-[#25D366]
                    focus-visible:ring-offset-2
                    focus-visible:ring-offset-white
                    active:brightness-95
                    min-h-[44px]
                "
            >
                {{ __('auth.whatsapp_send_button') }}
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
                    bg-[#25D366] hover:bg-[#20BD5A]
                    px-4 py-3
                    text-sm font-semibold leading-5 text-white
                    shadow-[var(--shadow-sm)]
                    transition-[opacity,box-shadow]
                    duration-[var(--duration-md)] ease-[var(--ease-out)]
                    hover:brightness-110 hover:shadow-[var(--shadow-md)]
                    focus-visible:outline-none focus-visible:ring-2
                    focus-visible:ring-[#25D366] focus-visible:ring-offset-2
                    focus-visible:ring-offset-white active:brightness-95
                    min-h-[44px]
                "
            >
                {{ __('auth.otp_verify_button') }}
                <svg class="size-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94
                             1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059
                             -.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198
                             .05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008
                             -.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479
                             0 1.462 1.065 2.875 1.213
                             3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195
                             1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413
                             -.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361
                             -.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45
                             4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0
                             012.893-2.799c2.92 2.201 4.48 5.43 4.48 8.884a9.86 9.86 0 01-9.86
                             9.86z"/>
                </svg>
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

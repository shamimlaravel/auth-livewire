{{-- ═══════════════════════════════════════════════════════════════════════
     2FA CHALLENGE  —  Clean single-column, focus-first form
═══════════════════════════════════════════════════════════════════════ --}}
<div class="space-y-6">

    <div>
        <div class="mx-auto mb-4 flex h-10 w-10 items-center justify-center rounded-2xl
            bg-gradient-to-br from-brand-500/12 to-brand-b-500/12
            shadow-[0_0_20px_rgba(108,59,255,.12)]
        ">
            <svg class="h-5 w-5 text-brand-500" fill="none"
                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598
                         6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623
                         5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598
                         -3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
            </svg>
        </div>

        <h2 class="text-[1.625rem] leading-tight font-bold tracking-tight text-surface-900">
            Two-Factor Authentication
        </h2>

        <p class="mt-1.5 text-[0.9375rem] leading-relaxed text-surface-500">
            Enter the authentication code from your authenticator app.
        </p>
    </div>

    <div class="space-y-5">
        <div>
            <label for="code"
                   class="block text-sm font-medium leading-5 text-surface-700 mb-1.5">
                Authentication Code
            </label>
            <input
                wire:model.blur="code"
                id="code"
                type="text"
                maxlength="6"
                inputmode="numeric"
                required
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
            @error('code')
                <p class="mt-1.5 text-xs font-medium text-error-500">{{ $message }}</p>
            @enderror
        </div>

        <button wire:click="submit"
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
            Verify
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

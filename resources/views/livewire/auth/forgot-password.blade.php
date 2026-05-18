<div class="space-y-6">
    <div>
        <div class="mx-auto mb-4 flex h-11 w-11 items-center justify-center rounded-2xl
            bg-[rgba(108,59,255,.12)]
            shadow-[0_0_20px_rgba(108,59,255,.12)]
        ">
            <svg class="h-5 w-5 text-brand-500" fill="none"
                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25
                         2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25
                         2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
            </svg>
        </div>
        <h2 class="text-[1.625rem] leading-tight font-bold tracking-tight text-surface-900">
            {{ __('Forgot your password?') }}
        </h2>
        <p class="mt-1.5 text-[0.9375rem] leading-relaxed text-surface-500">
            {{ __('No problem. Just let us know your email address and we will email you a password reset link that will instantly let you create a new one.') }}
        </p>
    </div>

    @if ($status)
        <div class="rounded-xl border border-success-200 bg-success-50 p-4 text-sm text-success-700">
            {{ $status }}
        </div>
    @endif

    <form wire:submit="submit" class="space-y-5" novalidate>
        <div>
            <label for="email"
                   class="block text-sm font-medium leading-5 text-surface-700 mb-1.5">
                {{ __('auth.email') }}
            </label>
            <input
                wire:model.blur="email"
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
            {{ __('Send password reset link') }}
            <svg class="size-4" fill="none"
                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M21.75 9v.906a2.25 2.25 0 01-1.183 1.981l-6.478 3.488M2.25 9v.906a2.25 2.25 0 001.183 1.981l6.478 3.488m8.839 2.51l-4.66-2.51m0 0l-1.023-.55a2.25 2.25 0 00-2.134 0l-1.022.55m0 0l-4.661 2.51m16.5 1.615a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V8.844a2.25 2.25 0 011.183-1.98l7.5-4.04a2.25 2.25 0 012.134 0l7.5 4.04a2.25 2.25 0 011.183 1.98V19.5z" />
            </svg>
        </button>
    </form>
</div>

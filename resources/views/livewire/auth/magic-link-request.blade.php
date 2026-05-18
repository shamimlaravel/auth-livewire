<div class="space-y-6">
    <div>
        <h2 class="text-[1.625rem] leading-tight font-bold tracking-tight text-surface-900">{{ __('Passwordless Login') }}</h2>
        <p class="mt-1.5 text-[0.9375rem] leading-relaxed text-surface-500">
            {{ __('Enter your email and we will send you a magic link to sign in instantly.') }}
        </p>
    </div>

    @if ($status)
        <div class="rounded-xl border border-brand-200 bg-brand-50 p-4 text-sm text-brand-700">
            {{ $status }}
        </div>
    @endif

    <form wire:submit="submit" class="space-y-5">
        <div>
            <label for="email" class="block text-sm font-medium text-surface-700 mb-1.5">{{ __('Email') }}</label>
            <input wire:model.blur="email" id="email" type="email" required autocomplete="email"
                class="
                    block w-full rounded-[var(--radius-field)]
                    border border-surface-200
                    bg-surface-50/60
                    px-4 py-2.75
                    text-sm text-surface-900
                    placeholder:text-surface-400
                    shadow-[var(--shadow-sm)]
                    transition-[border-color,box-shadow]
                    duration-[var(--duration-md)]
                    ease-[var(--ease-out)]
                    focus:border-brand-400
                    focus:ring-[3px] focus:ring-brand-500/12
                    focus:bg-white
                "
                placeholder="you@company.com">
            @error('email') <p class="mt-1.5 text-xs font-medium text-error-500">{{ $message }}</p> @enderror
        </div>

        <button type="submit"
            class="
                neon-glow flex w-full items-center justify-center gap-2
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
            {{ __('Send magic link') }}
        </button>
    </form>

    <div class="text-center">
        <a href="{{ route('login') }}" wire:navigate class="text-sm font-medium text-brand-500 no-underline transition-[color] duration-[var(--duration-md)] ease-[var(--ease-out)] hover:text-brand-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-400 focus-visible:ring-offset-2 focus-visible:ring-offset-white rounded-sm">
            {{ __('Back to sign in') }}
        </a>
    </div>
</div>

<div class="space-y-6">
    <div>
        <div class="mx-auto mb-4 flex h-10 w-10 items-center justify-center rounded-2xl bg-brand-500/12 neon-glow">
            <svg class="h-5 w-5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold tracking-tight text-surface-900">{{ __('Reset your password') }}</h2>
        <p class="mt-1 text-sm text-surface-500">
            {{ __('Remember your password?') }}
            <a href="{{ route('login') }}" wire:navigate class="font-semibold text-brand-500 hover:text-brand-600 transition-colors duration-[var(--duration-md)] ease-[var(--ease-out)]">
                {{ __('Sign in') }}
            </a>
        </p>
    </div>

    <form wire:submit="submit" class="space-y-5">
        <div>
            <label for="email" class="block text-sm font-medium text-surface-700">{{ __('auth.email') }}</label>
            <input wire:model.blur="email" id="email" type="email" readonly
                class="mt-1.5 block w-full rounded-[var(--radius-field)] border border-surface-200 bg-surface-50/60 px-4 py-2.75 text-sm text-surface-500 shadow-[var(--shadow-sm)]">
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-surface-700">{{ __('auth.password') }}</label>
            <div x-data="{ show: false }" class="relative mt-1.5">
                <input :type="show ? 'text' : 'password'" wire:model.live="password" id="password" required autocomplete="new-password"
                    class="block w-full rounded-[var(--radius-field)] border border-surface-200 bg-surface-50/60 px-4 py-2.75 pr-11 text-sm shadow-[var(--shadow-sm)] transition-[border-color,box-shadow] duration-[var(--duration-md)] ease-[var(--ease-out)] placeholder:text-surface-400 focus:border-brand-400 focus:ring-[3px] focus:ring-brand-500/12 focus:bg-white"
                    placeholder="{{ __('Password') }}">
                <button @click="show = !show" type="button"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-surface-400 transition-colors duration-[var(--duration-md)] ease-[var(--ease-out)] hover:text-surface-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-400 focus-visible:ring-offset-2 focus-visible:ring-offset-white rounded-sm p-0.5"
                    :aria-label="show ? 'Hide password' : 'Show password'" :aria-pressed="show">
                    <svg x-show="!show" class="size-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <svg x-show="show" x-cloak class="size-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                    </svg>
                </button>
            </div>
            @error('password') <p class="mt-1.5 text-xs font-medium text-error-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="passwordConfirmation" class="block text-sm font-medium text-surface-700">{{ __('Confirm Password') }}</label>
            <div x-data="{ show: false }" class="relative mt-1.5">
                <input :type="show ? 'text' : 'password'" wire:model.live="passwordConfirmation" id="passwordConfirmation" required autocomplete="new-password"
                    class="block w-full rounded-[var(--radius-field)] border border-surface-200 bg-surface-50/60 px-4 py-2.75 pr-11 text-sm shadow-[var(--shadow-sm)] transition-[border-color,box-shadow] duration-[var(--duration-md)] ease-[var(--ease-out)] placeholder:text-surface-400 focus:border-brand-400 focus:ring-[3px] focus:ring-brand-500/12 focus:bg-white"
                    placeholder="{{ __('Confirm new password') }}">
                <button @click="show = !show" type="button"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-surface-400 transition-colors duration-[var(--duration-md)] ease-[var(--ease-out)] hover:text-surface-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-400 focus-visible:ring-offset-2 focus-visible:ring-offset-white rounded-sm p-0.5"
                    :aria-label="show ? 'Hide password' : 'Show password'" :aria-pressed="show">
                    <svg x-show="!show" class="size-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <svg x-show="show" x-cloak class="size-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                    </svg>
                </button>
            </div>
            @error('passwordConfirmation') <p class="mt-1.5 text-xs font-medium text-error-500">{{ $message }}</p> @enderror
        </div>

        <button type="submit"
            class="
                neon-glow flex w-full items-center justify-center gap-2
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
            {{ __('Reset password') }}
            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </button>
    </form>
</div>

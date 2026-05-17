<div class="space-y-6">
    <div>
        <div class="mx-auto mb-4 flex h-10 w-10 items-center justify-center rounded-2xl bg-gradient-to-br from-neon-purple/10 to-deep-blue/10 neon-glow">
            <svg class="h-5 w-5 text-neon-purple" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold tracking-tight text-gray-900">Sign in</h2>
        <p class="mt-1 text-sm text-gray-500">
            Or
            <a href="{{ route('register') }}" wire:navigate class="font-semibold text-neon-purple hover:text-deep-blue transition-colors">
                create a new account
            </a>
        </p>
    </div>

    @if (! $showTwoFactor)
        <form wire:submit="submit" class="space-y-5">
            <div>
                <label for="login" class="block text-sm font-medium text-gray-700">Username or Email</label>
                <input wire:model.blur="login" id="login" type="text" required autocomplete="username"
                    class="mt-1.5 block w-full rounded-xl border border-gray-200 bg-white/50 px-4 py-2.5 text-sm shadow-sm transition-all placeholder:text-gray-400 focus:border-neon-purple focus:outline-none focus:ring-2 focus:ring-neon-purple/20"
                    placeholder="username@example.com">
                @error('login') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <div x-data="{ show: false }" class="relative mt-1.5">
                    <input :type="show ? 'text' : 'password'" wire:model.blur="password" id="password" required autocomplete="current-password"
                        class="block w-full rounded-xl border border-gray-200 bg-white/50 px-4 py-2.5 pr-10 text-sm shadow-sm transition-all placeholder:text-gray-400 focus:border-neon-purple focus:outline-none focus:ring-2 focus:ring-neon-purple/20">
                    <button @click="show = !show" type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                        <svg x-show="!show" class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <svg x-show="show" class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" x-cloak>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                        </svg>
                    </button>
                </div>
                @error('password') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2">
                    <input wire:model="remember" type="checkbox"
                        class="rounded border-gray-300 text-neon-purple shadow-sm focus:ring-neon-purple/30">
                    <span class="text-sm text-gray-600">Remember me</span>
                </label>
                <a href="{{ route('login.magic') }}" wire:navigate class="text-sm font-semibold text-neon-purple hover:text-deep-blue transition-colors">
                    Use magic link
                </a>
            </div>

            <button type="submit"
                class="neon-glow flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-neon-purple to-deep-blue px-4 py-3 text-sm font-semibold text-white shadow-sm transition-all hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-neon-purple/40 focus:ring-offset-2">
                Sign in
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                </svg>
            </button>

            <div class="text-center">
                <a href="{{ route('password.request') }}" wire:navigate class="text-sm font-semibold text-neon-purple hover:text-deep-blue transition-colors">
                    Forgot your password?
                </a>
            </div>

            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-100"></div>
                </div>
                <div class="relative flex justify-center text-xs">
                    <span class="bg-white px-3 text-gray-400">Or continue with</span>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <a href="{{ route('auth.social.redirect', 'google') }}"
                    class="flex items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition-all hover:border-gray-300 hover:bg-gray-50 hover:shadow">
                    <svg class="h-5 w-5" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                </a>
                <a href="{{ route('auth.social.redirect', 'github') }}"
                    class="flex items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition-all hover:border-gray-300 hover:bg-gray-50 hover:shadow">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
                </a>
                <a href="{{ route('auth.social.redirect', 'facebook') }}"
                    class="flex items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition-all hover:border-gray-300 hover:bg-gray-50 hover:shadow">
                    <svg class="h-5 w-5 text-[#1877F2]" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                </a>
            </div>
        </form>
    @else
        <div class="space-y-5">
            <div>
                <label for="twoFactorCode" class="block text-sm font-medium text-gray-700">Authentication Code</label>
                <input wire:model="twoFactorCode" id="twoFactorCode" type="text" maxlength="6" inputmode="numeric"
                    class="mt-1.5 block w-full rounded-xl border border-gray-200 bg-white/50 px-4 py-3 text-center text-2xl tracking-widest shadow-sm transition-all focus:border-neon-purple focus:outline-none focus:ring-2 focus:ring-neon-purple/20">
                @error('twoFactorCode') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <button wire:click="verifyTwoFactor"
                class="neon-glow flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-neon-purple to-deep-blue px-4 py-3 text-sm font-semibold text-white shadow-sm transition-all hover:opacity-90">
                Verify
            </button>
        </div>
    @endif
</div>

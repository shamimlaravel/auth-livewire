<div class="space-y-6">
    <div>
        <div class="mx-auto mb-4 flex h-10 w-10 items-center justify-center rounded-2xl bg-gradient-to-br from-neon-purple/10 to-deep-blue/10 neon-glow">
            <svg class="h-5 w-5 text-neon-purple" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold tracking-tight text-gray-900">Reset your password</h2>
        <p class="mt-1 text-sm text-gray-500">
            Remember your password?
            <a href="{{ route('login') }}" wire:navigate class="font-semibold text-neon-purple hover:text-deep-blue transition-colors">
                Sign in
            </a>
        </p>
    </div>

    <form wire:submit="submit" class="space-y-5">
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input wire:model.blur="email" id="email" type="email" readonly
                class="mt-1.5 block w-full rounded-xl border border-gray-200 bg-gray-50/50 px-4 py-2.5 text-sm text-gray-500 shadow-sm">
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
            <div x-data="{ show: false }" class="relative mt-1.5">
                <input :type="show ? 'text' : 'password'" wire:model.blur="password" id="password" required autocomplete="new-password"
                    class="block w-full rounded-xl border border-gray-200 bg-white/50 px-4 py-2.5 pr-10 text-sm shadow-sm transition-all placeholder:text-gray-400 focus:border-neon-purple focus:outline-none focus:ring-2 focus:ring-neon-purple/20"
                    placeholder="Enter new password">
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

        <div>
            <label for="passwordConfirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
            <div x-data="{ show: false }" class="relative mt-1.5">
                <input :type="show ? 'text' : 'password'" wire:model.blur="passwordConfirmation" id="passwordConfirmation" required autocomplete="new-password"
                    class="block w-full rounded-xl border border-gray-200 bg-white/50 px-4 py-2.5 pr-10 text-sm shadow-sm transition-all placeholder:text-gray-400 focus:border-neon-purple focus:outline-none focus:ring-2 focus:ring-neon-purple/20"
                    placeholder="Confirm new password">
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
        </div>

        <button type="submit"
            class="neon-glow flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-neon-purple to-deep-blue px-4 py-3 text-sm font-semibold text-white shadow-sm transition-all hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-neon-purple/40 focus:ring-offset-2">
            Reset password
            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </button>
    </form>
</div>

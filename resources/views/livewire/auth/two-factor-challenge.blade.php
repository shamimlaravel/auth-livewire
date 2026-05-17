<div class="space-y-6">
    <div>
        <div class="mx-auto mb-4 flex h-10 w-10 items-center justify-center rounded-2xl bg-gradient-to-br from-neon-purple/10 to-deep-blue/10 neon-glow">
            <svg class="h-5 w-5 text-neon-purple" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold tracking-tight text-gray-900">Two-Factor Authentication</h2>
        <p class="mt-1 text-sm text-gray-500">
            Enter the authentication code from your authenticator app.
        </p>
    </div>

    <div class="space-y-5">
        <div>
            <label for="code" class="block text-sm font-medium text-gray-700">Authentication Code</label>
            <input wire:model.blur="code" id="code" type="text" maxlength="6" inputmode="numeric" required autocomplete="one-time-code"
                class="mt-1.5 block w-full rounded-xl border border-gray-200 bg-white/50 px-4 py-3 text-center text-2xl tracking-[0.3em] shadow-sm transition-all focus:border-neon-purple focus:outline-none focus:ring-2 focus:ring-neon-purple/20"
                placeholder="000000">
            @error('code') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <button type="button" wire:click="submit"
            class="neon-glow flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-neon-purple to-deep-blue px-4 py-3 text-sm font-semibold text-white shadow-sm transition-all hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-neon-purple/40 focus:ring-offset-2">
            Verify
            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </button>
    </div>
</div>

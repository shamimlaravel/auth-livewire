<div class="space-y-6">
    <div>
        <div class="mx-auto mb-4 flex h-10 w-10 items-center justify-center rounded-2xl bg-gradient-to-br from-neon-purple/10 to-deep-blue/10 neon-glow">
            <svg class="h-5 w-5 text-neon-purple" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
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

    @if ($status)
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">
            {{ $status }}
        </div>
    @endif

    <form wire:submit="submit" class="space-y-5">
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input wire:model.blur="email" id="email" type="email" required autocomplete="email"
                class="mt-1.5 block w-full rounded-xl border border-gray-200 bg-white/50 px-4 py-2.5 text-sm shadow-sm transition-all placeholder:text-gray-400 focus:border-neon-purple focus:outline-none focus:ring-2 focus:ring-neon-purple/20">
            @error('email') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <button type="submit"
            class="neon-glow flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-neon-purple to-deep-blue px-4 py-3 text-sm font-semibold text-white shadow-sm transition-all hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-neon-purple/40 focus:ring-offset-2">
            Send password reset link
            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 9v.906a2.25 2.25 0 01-1.183 1.981l-6.478 3.488M2.25 9v.906a2.25 2.25 0 001.183 1.981l6.478 3.488m8.839 2.51l-4.66-2.51m0 0l-1.023-.55a2.25 2.25 0 00-2.134 0l-1.022.55m0 0l-4.661 2.51m16.5 1.615a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V8.844a2.25 2.25 0 011.183-1.98l7.5-4.04a2.25 2.25 0 012.134 0l7.5 4.04a2.25 2.25 0 011.183 1.98V19.5z" />
            </svg>
        </button>
    </form>
</div>

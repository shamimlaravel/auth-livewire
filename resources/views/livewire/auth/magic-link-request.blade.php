<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ __("Passwordless Login") }}</h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Enter your email and we will send you a magic link to sign in instantly.") }}
        </p>
    </div>

    @if ($status)
        <div class="rounded-lg bg-green-50 p-4 text-sm text-green-700 dark:bg-green-900/50 dark:text-green-300">
            {{ $status }}
        </div>
    @endif

    <form wire:submit="submit" class="space-y-5">
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __("Email") }}</label>
            <input wire:model.blur="email" id="email" type="email" required autocomplete="email"
                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-400">
            @error("email") <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
        </div>

        <button type="submit"
            class="flex w-full justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition-colors">
            {{ __("Send magic link") }}
        </button>

        <div class="text-center">
            <a href="{{ route("login") }}" wire:navigate class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">
                {{ __("Back to sign in") }}
            </a>
        </div>
    </form>
</div>
<div class="space-y-6 text-center">
    <div>
        <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ __("Verify your email") }}</h2>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you did not receive the email, we will gladly send you another.") }}
        </p>
    </div>

    @if (session("status"))
        <div class="rounded-lg bg-green-50 p-4 text-sm text-green-700 dark:bg-green-900/50 dark:text-green-300">
            {{ session("status") }}
        </div>
    @endif

    <form method="POST" action="{{ route("verification.send") }}" class="space-y-3">
        @csrf
        <button type="submit"
            class="w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors">
            {{ __("Resend verification email") }}
        </button>
    </form>

    <form method="POST" action="{{ route("logout") }}">
        @csrf
        <button type="submit"
            class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">
            {{ __("Logout") }}
        </button>
    </form>
</div>
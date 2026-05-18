<div class="space-y-6 text-center">
    <div>
        <h2 class="text-[1.625rem] leading-tight font-bold tracking-tight text-surface-900">{{ __('Verify your email') }}</h2>
        <p class="mt-1.5 text-[0.9375rem] leading-relaxed text-surface-500">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you did not receive the email, we will gladly send you another.') }}
        </p>
    </div>

    @if (session('status'))
        <div class="rounded-xl border border-success-200 bg-success-50 p-4 text-sm text-success-700">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}" class="space-y-3">
        @csrf
        <button type="submit"
            class="
                flex w-full items-center justify-center gap-2
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
            {{ __('Resend verification email') }}
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit"
            class="text-sm font-medium text-brand-500 no-underline
                   transition-[color]
                   duration-[var(--duration-md)]
                   ease-[var(--ease-out)]
                   hover:text-brand-600
                   focus-visible:outline-none
                   focus-visible:ring-2 focus-visible:ring-brand-400
                   focus-visible:ring-offset-2 focus-visible:ring-offset-white
                   rounded-sm">
            {{ __('Logout') }}
        </button>
    </form>
</div>

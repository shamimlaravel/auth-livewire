<div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
    <div class="rounded-lg border border-surface-200 bg-white p-6">
        <h1 class="text-2xl font-bold tracking-tight text-surface-900">
            {{ __('Seller Dashboard') }}
        </h1>
        <p class="mt-1 text-sm text-surface-600">{{ __('Welcome to your seller portal, :name!', ['name' => auth()->user()->name]) }}</p>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
        <div class="rounded-lg border border-surface-200 bg-white p-6">
            <p class="text-sm font-medium text-surface-600">{{ __('Products') }}</p>
            <p class="mt-2 text-3xl font-bold text-surface-900">--</p>
            <p class="mt-1 text-xs text-surface-500">{{ __('Product management coming soon.') }}</p>
        </div>
        <div class="rounded-lg border border-surface-200 bg-white p-6">
            <p class="text-sm font-medium text-surface-600">{{ __('Orders') }}</p>
            <p class="mt-2 text-3xl font-bold text-surface-900">--</p>
            <p class="mt-1 text-xs text-surface-500">{{ __('Order management coming soon.') }}</p>
        </div>
    </div>
</div>
<div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ __('Account Settings') }}</h1>
    <p class="mt-2 text-sm text-gray-600">{{ __('Manage your profile, password, and security settings.') }}</p>

    @if (session('status'))
        <div class="mt-6 rounded-lg bg-green-50 p-4 text-sm text-green-700">{{ session('status') }}</div>
    @endif
    @if (session('error'))
        <div class="mt-6 rounded-lg bg-red-50 p-4 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    {{-- Profile --}}
    <div class="mt-8 rounded-lg border border-gray-200 bg-white p-6">
        <h2 class="text-lg font-semibold text-gray-900">{{ __('Profile') }}</h2>
        <form wire:submit="updateProfile" class="mt-4 space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">{{ __('Name') }}</label>
                <input wire:model="name" id="name" type="text"
                    class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500">
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Email') }}</label>
                <input wire:model="email" id="email" type="email"
                    class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500">
                @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">{{ __('Phone') }}</label>
                <input wire:model="phone" id="phone" type="tel"
                    class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500">
                @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <button type="submit"
                class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                {{ __('Update Profile') }}
            </button>
        </form>
    </div>

    {{-- Password --}}
    <div class="mt-8 rounded-lg border border-gray-200 bg-white p-6">
        <h2 class="text-lg font-semibold text-gray-900">{{ __('Change Password') }}</h2>
        <form wire:submit="updatePassword" class="mt-4 space-y-4">
            <div>
                <label for="currentPassword" class="block text-sm font-medium text-gray-700">{{ __('Current Password') }}</label>
                <input wire:model="currentPassword" id="currentPassword" type="password"
                    class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500">
                @error('currentPassword') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="newPassword" class="block text-sm font-medium text-gray-700">{{ __('New Password') }}</label>
                <input wire:model="newPassword" id="newPassword" type="password"
                    class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500">
                @error('newPassword') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="newPasswordConfirmation" class="block text-sm font-medium text-gray-700">{{ __('Confirm New Password') }}</label>
                <input wire:model="newPasswordConfirmation" id="newPasswordConfirmation" type="password"
                    class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500">
            </div>
            <button type="submit"
                class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                {{ __('Update Password') }}
            </button>
        </form>
    </div>

    {{-- Two-Factor Authentication --}}
    <div class="mt-8 rounded-lg border border-gray-200 bg-white p-6">
        <h2 class="text-lg font-semibold text-gray-900">{{ __('Two-Factor Authentication') }}</h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Add an extra layer of security to your account.') }}
        </p>

        @if ($hasTwoFactor)
            <div class="mt-4 rounded-lg bg-green-50 p-4">
                <p class="text-sm font-medium text-green-800">{{ __('2FA is enabled.') }}</p>
            </div>

            <div class="mt-4">
                <h3 class="text-sm font-medium text-gray-700">{{ __('Recovery Codes') }}</h3>
                @if (count($storedRecoveryCodes) > 0)
                    <div class="mt-2 grid grid-cols-2 gap-2">
                        @foreach ($storedRecoveryCodes as $code)
                            <code class="rounded bg-gray-100 px-2 py-1 text-sm">{{ $code }}</code>
                        @endforeach
                    </div>
                @endif
                <button wire:click="generateRecoveryCodes"
                    class="mt-3 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    {{ __('Regenerate Recovery Codes') }}
                </button>
            </div>

            <div class="mt-6">
                <button wire:click="disableTwoFactor"
                    onclick="return confirm('{{ __('Are you sure you want to disable 2FA?') }}')"
                    class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                    {{ __('Disable 2FA') }}
                </button>
            </div>
        @else
            @if ($twoFactorSecret)
                <div class="mt-4 space-y-4">
                    <p class="text-sm text-gray-700">{{ __('Scan this QR code with your authenticator app:') }}</p>
                    <div class="flex justify-center">
                        <img src="{{ $qrCodeUrl }}" alt="QR Code" class="rounded-lg border">
                    </div>
                    <p class="text-xs text-gray-500">{{ __('Or enter this key manually:') }} <code class="rounded bg-gray-100 px-1">{{ $twoFactorSecret }}</code></p>

                    <div>
                        <label for="confirmCode" class="block text-sm font-medium text-gray-700">{{ __('Confirm with code from app') }}</label>
                        <input wire:model="confirmCode" id="confirmCode" type="text" maxlength="6"
                            class="mt-1 block w-48 rounded-lg border border-gray-300 px-3 py-2 text-center text-2xl tracking-widest shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500">
                        @error('confirmCode') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <button wire:click="confirmTwoFactor"
                        class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                        {{ __('Verify & Enable 2FA') }}
                    </button>
                </div>
            @else
                <button wire:click="setupTwoFactor"
                    class="mt-4 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                    {{ __('Set Up 2FA') }}
                </button>
            @endif
        @endif
    </div>

    {{-- Social Accounts --}}
    @if (count($socialAccounts) > 0)
        <div class="mt-8 rounded-lg border border-gray-200 bg-white p-6">
            <h2 class="text-lg font-semibold text-gray-900">{{ __('Linked Social Accounts') }}</h2>
            <div class="mt-4 space-y-3">
                @foreach ($socialAccounts as $account)
                    <div class="flex items-center justify-between rounded-lg border border-gray-100 bg-gray-50 px-4 py-3">
                        <span class="text-sm font-medium text-gray-700">{{ ucfirst($account->provider) }}</span>
                        <span class="text-xs text-gray-500">{{ __('Linked') }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

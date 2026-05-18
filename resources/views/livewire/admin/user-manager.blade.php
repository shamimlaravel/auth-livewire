<div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
    <div class="mb-8 flex items-center justify-between">
        <div>
    <h1 class="text-3xl font-bold tracking-tight text-surface-900">{{ __('User Management') }}</h1>
    <p class="mt-2 text-sm text-surface-600">{{ __('Manage users, roles, and account status.') }}</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" wire:navigate
           class="text-sm font-medium text-brand-600 hover:text-brand-500">
            &larr; {{ __('Back to Dashboard') }}
        </a>
    </div>

    @if (session('status'))
        <div class="mb-6 rounded-lg bg-success-50 p-4 text-sm text-success-700">{{ session('status') }}</div>
    @endif

    <div class="mb-6 rounded-lg border border-surface-200 bg-white p-4">
        <div class="flex flex-wrap gap-4">
            <div class="flex-1">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="{{ __('Search by name or email...') }}"
                    class="w-full rounded-lg border border-surface-300 px-3 py-2 text-sm focus:border-brand-500 focus:outline-none focus:ring-brand-500">
            </div>
            <select wire:model.live="roleFilter" class="rounded-lg border border-surface-300 px-3 py-2 text-sm">
                <option value="">{{ __('All Roles') }}</option>
                @foreach (Spatie\Permission\Models\Role::all() as $role)
                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                @endforeach
            </select>
            <select wire:model.live="statusFilter" class="rounded-lg border border-surface-300 px-3 py-2 text-sm">
                <option value="">{{ __('All Statuses') }}</option>
                <option value="active">{{ __('Active') }}</option>
                <option value="suspended">{{ __('Suspended') }}</option>
                <option value="banned">{{ __('Banned') }}</option>
            </select>
        </div>
    </div>

    <div class="overflow-hidden rounded-lg border border-surface-200 bg-white">
        <table class="min-w-full divide-y divide-surface-200">
            <thead class="bg-surface-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-surface-500">{{ __('Name') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-surface-500">{{ __('Email') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-surface-500">{{ __('Role') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-surface-500">{{ __('Status') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-surface-500">{{ __('2FA') }}</th>
                    <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-surface-500">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-surface-200">
                @foreach ($users as $user)
                    <tr class="hover:bg-surface-50">
                        @if ($editingUserId === $user->id)
                            <td class="px-6 py-4 text-sm font-medium text-surface-900" colspan="6">
                                <form wire:submit="saveUser" class="flex flex-wrap items-end gap-4">
                                    <div>
                                        <label class="block text-xs font-medium text-surface-500">{{ __('Role') }}</label>
                                        <select wire:model="editingRole" class="mt-1 rounded-lg border border-surface-300 px-3 py-2 text-sm">
                                            @foreach (Spatie\Permission\Models\Role::all() as $role)
                                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-surface-500">{{ __('Status') }}</label>
                                        <select wire:model="editingStatus" class="mt-1 rounded-lg border border-surface-300 px-3 py-2 text-sm">
                                            <option value="active">{{ __('Active') }}</option>
                                            <option value="suspended">{{ __('Suspended') }}</option>
                                            <option value="banned">{{ __('Banned') }}</option>
                                        </select>
                                    </div>
                                    <button type="submit"
                                        class="rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white hover:bg-brand-700">
                                        {{ __('Save') }}
                                    </button>
                                    <button type="button" wire:click="cancelEdit"
                                        class="rounded-lg border border-surface-300 bg-white px-4 py-2 text-sm font-medium text-surface-700 hover:bg-surface-50">
                                        {{ __('Cancel') }}
                                    </button>
                                </form>
                            </td>
                        @else
                            <td class="px-6 py-4 text-sm font-medium text-surface-900">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-sm text-surface-600">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                @foreach ($user->roles as $role)
                                    <span class="inline-flex items-center rounded-full bg-brand-100 px-2.5 py-0.5 text-xs font-medium text-brand-800">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                    {{ $user->status === 'active' ? 'bg-success-100 text-success-800' : ($user->status === 'suspended' ? 'bg-yellow-100 text-yellow-800' : 'bg-error-100 text-error-800') }}">
                                    {{ $user->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-surface-600">
                                {{ $user->hasTwoFactorEnabled() ? __('Yes') : __('No') }}
                            </td>
                            <td class="px-6 py-4 text-right text-sm">
                                <button wire:click="edit({{ $user->id }})"
                                    class="font-medium text-brand-600 hover:text-brand-500">
                                    {{ __('Edit') }}
                                </button>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>

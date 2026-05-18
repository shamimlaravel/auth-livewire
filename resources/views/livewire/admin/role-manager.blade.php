<div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
    <div class="mb-8">
    <h1 class="text-3xl font-bold tracking-tight text-surface-900">{{ __('Role Manager') }}</h1>
    <p class="mt-2 text-sm text-surface-600">{{ __('Create, edit, and manage roles and permissions.') }}</p>
    </div>

    @if (session('status'))
        <div class="mb-6 rounded-lg bg-success-50 p-4 text-sm text-success-700">{{ session('status') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-6 rounded-lg bg-error-50 p-4 text-sm text-error-700">{{ session('error') }}</div>
    @endif

    <div class="mb-8 rounded-lg border border-surface-200 bg-white p-6">
        <h2 class="mb-4 text-lg font-semibold">
            {{ $editingRoleId ? __('Edit Role') : __('Create New Role') }}
        </h2>
        <form wire:submit="{{ $editingRoleId ? 'update' : 'create' }}">
            <div class="mb-4">
                <label for="roleName" class="block text-sm font-medium text-surface-700">{{ __('Role Name') }}</label>
                <input wire:model.blur="roleName" id="roleName" type="text" required
                    class="mt-1 block w-full rounded-lg border border-surface-300 px-3 py-2 shadow-sm focus:border-brand-500 focus:outline-none focus:ring-brand-500">
                @error('roleName') <p class="mt-1 text-sm text-error-600">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-surface-700">{{ __('Permissions') }}</label>
                <div class="mt-2 grid grid-cols-2 gap-2 sm:grid-cols-3 lg:grid-cols-4">
                    @foreach ($allPermissions as $permission)
                        <label class="flex items-center gap-2 rounded-lg border border-surface-200 p-3 text-sm hover:bg-surface-50">
                            <input wire:model="selectedPermissions" type="checkbox" value="{{ $permission->name }}"
                                class="rounded border-surface-300 text-brand-600 focus:ring-brand-500">
                            {{ $permission->name }}
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white hover:bg-brand-700">
                    {{ $editingRoleId ? __('Update') : __('Create') }}
                </button>
                @if ($editingRoleId)
                    <button type="button" wire:click="resetForm"
                        class="rounded-lg border border-surface-300 bg-white px-4 py-2 text-sm font-medium text-surface-700 hover:bg-surface-50">
                        {{ __('Cancel') }}
                    </button>
                @endif
            </div>
        </form>
    </div>

    <div class="rounded-lg border border-surface-200 bg-white">
        <table class="min-w-full divide-y divide-surface-200">
            <thead class="bg-surface-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-surface-500">{{ __('Role') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-surface-500">{{ __('Permissions') }}</th>
                    <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-surface-500">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-surface-200">
                @foreach ($roles as $role)
                    <tr class="hover:bg-surface-50">
                        <td class="px-6 py-4 text-sm font-medium text-surface-900">{{ $role->name }}</td>
                        <td class="px-6 py-4 text-sm text-surface-600">
                            <div class="flex flex-wrap gap-1">
                                @foreach ($role->permissions as $permission)
                                    <span class="inline-flex items-center rounded-full bg-brand-100 px-2.5 py-0.5 text-xs font-medium text-brand-800">
                                        {{ $permission->name }}
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right text-sm">
                            <button wire:click="edit({{ $role->id }})"
                                class="font-medium text-brand-600 hover:text-brand-500">{{ __('Edit') }}</button>
                            <button wire:click="delete({{ $role->id }})"
                                onclick="return confirm('{{ __('Are you sure?') }}')"
                                class="ml-3 font-medium text-error-600 hover:text-error-500">{{ __('Delete') }}</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

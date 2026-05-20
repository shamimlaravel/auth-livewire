<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    {{-- Status messages --}}
    @if (session('status'))
        <div class="rounded-2xl border border-success-200 dark:border-success-500/20 bg-success-50 dark:bg-success-500/5 p-4 text-sm font-medium text-success-700 dark:text-success-300 flex items-center gap-2">
            <svg class="size-4 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            {{ session('status') }}
        </div>
    @endif
    @if (session('error'))
        <div class="rounded-2xl border border-error-200 dark:border-error-500/20 bg-error-50 dark:bg-error-500/5 p-4 text-sm font-medium text-error-700 dark:text-error-300 flex items-center gap-2">
            <svg class="size-4 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
            </svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Role form --}}
    <div class="rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400">
                <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                </svg>
            </div>
            <div>
                <h2 class="text-base font-semibold text-surface-900 dark:text-white">
                    {{ $editingRoleId ? __('Edit Role') : __('Create New Role') }}
                </h2>
                <p class="text-xs text-surface-500 dark:text-surface-400">{{ __('Define role name and assign permissions.') }}</p>
            </div>
        </div>

        <form wire:submit="{{ $editingRoleId ? 'update' : 'create' }}" class="space-y-5">
            <div>
                <label for="roleName" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">{{ __('Role Name') }}</label>
                <input wire:model.blur="roleName" id="roleName" type="text" required placeholder="e.g. editor, manager"
                    class="w-full rounded-xl border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 px-4 py-2.5 text-sm text-surface-900 dark:text-white placeholder:text-surface-400 focus:border-brand-400 focus:ring-brand-500/20 focus:ring-[3px]">
                @error('roleName') <p class="mt-1 text-sm text-error-600 dark:text-error-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">{{ __('Permissions') }}</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2">
                    @foreach ($allPermissions as $permission)
                        <label class="flex items-center gap-2.5 rounded-xl border border-surface-200 dark:border-surface-600 bg-white dark:bg-surface-700 px-3.5 py-2.5 text-sm cursor-pointer transition-all hover:border-brand-300 dark:hover:border-brand-500/30 hover:bg-brand-50/50 dark:hover:bg-brand-500/5">
                            <input wire:model="selectedPermissions" type="checkbox" value="{{ $permission->name }}"
                                class="rounded border-surface-300 dark:border-surface-500 text-brand-600 focus:ring-brand-500 focus:ring-offset-0">
                            <span class="text-surface-700 dark:text-surface-300">{{ $permission->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-brand-500 to-brand-b-500 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-500/25 hover:brightness-110 transition-all duration-200">
                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    {{ $editingRoleId ? __('Update Role') : __('Create Role') }}
                </button>
                @if ($editingRoleId)
                    <button type="button" wire:click="resetForm"
                        class="inline-flex items-center gap-2 rounded-xl border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 px-5 py-2.5 text-sm font-medium text-surface-700 dark:text-surface-300 hover:bg-surface-50 dark:hover:bg-surface-600 transition-all duration-200">
                        <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                        {{ __('Cancel') }}
                    </button>
                @endif
            </div>
        </form>
    </div>

    {{-- Roles table --}}
    <div class="overflow-hidden rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-surface-200 dark:divide-surface-700">
                <thead>
                    <tr class="bg-surface-50 dark:bg-surface-800/50">
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-surface-500 dark:text-surface-400">{{ __('Role') }}</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-surface-500 dark:text-surface-400">{{ __('Permissions') }}</th>
                        <th class="px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-surface-500 dark:text-surface-400">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-200 dark:divide-surface-700">
                    @foreach ($roles as $role)
                        <tr class="hover:bg-surface-50 dark:hover:bg-surface-700/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-brand-100 dark:bg-brand-500/20 text-xs font-bold text-brand-700 dark:text-brand-300 uppercase">
                                        {{ substr($role->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm font-medium text-surface-900 dark:text-white">{{ $role->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach ($role->permissions as $permission)
                                        <span class="inline-flex items-center rounded-full bg-brand-50 dark:bg-brand-500/10 px-2.5 py-0.5 text-xs font-medium text-brand-700 dark:text-brand-300 ring-1 ring-brand-200 dark:ring-brand-500/20">
                                            {{ $permission->name }}
                                        </span>
                                    @endforeach
                                    @if ($role->permissions->isEmpty())
                                        <span class="text-xs text-surface-400 dark:text-surface-500 italic">{{ __('No permissions') }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right text-sm">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="edit({{ $role->id }})"
                                        class="inline-flex items-center gap-1.5 rounded-xl border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 px-3 py-1.5 text-xs font-medium text-surface-700 dark:text-surface-300 hover:bg-surface-50 dark:hover:bg-surface-600 transition-colors">
                                        <svg class="size-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                        {{ __('Edit') }}
                                    </button>
                                    <button wire:click="delete({{ $role->id }})"
                                        onclick="return confirm('{{ __('Are you sure?') }}')"
                                        class="inline-flex items-center gap-1.5 rounded-xl border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 px-3 py-1.5 text-xs font-medium text-error-600 dark:text-error-400 hover:bg-error-50 dark:hover:bg-error-500/10 transition-colors">
                                        <svg class="size-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                        {{ __('Delete') }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

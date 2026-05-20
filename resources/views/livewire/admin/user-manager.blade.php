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

    {{-- Filters --}}
    <div class="rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 p-5">
        <div class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <div class="relative">
                    <svg class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 size-4 text-surface-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="{{ __('Search by name or email...') }}"
                        class="w-full rounded-xl border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 pl-10 pr-4 py-2.5 text-sm text-surface-900 dark:text-white placeholder:text-surface-400 focus:border-brand-400 focus:ring-brand-500/20 focus:ring-[3px]">
                </div>
            </div>
            <select wire:model.live="roleFilter"
                class="rounded-xl border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 px-4 py-2.5 text-sm text-surface-900 dark:text-white focus:border-brand-400 focus:ring-brand-500/20 focus:ring-[3px]">
                <option value="">{{ __('All Roles') }}</option>
                @foreach (Spatie\Permission\Models\Role::all() as $role)
                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                @endforeach
            </select>
            <select wire:model.live="statusFilter"
                class="rounded-xl border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 px-4 py-2.5 text-sm text-surface-900 dark:text-white focus:border-brand-400 focus:ring-brand-500/20 focus:ring-[3px]">
                <option value="">{{ __('All Statuses') }}</option>
                <option value="active">{{ __('Active') }}</option>
                <option value="suspended">{{ __('Suspended') }}</option>
                <option value="banned">{{ __('Banned') }}</option>
            </select>
        </div>
    </div>

    {{-- Users table --}}
    <div class="overflow-hidden rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-surface-200 dark:divide-surface-700">
                <thead>
                    <tr class="bg-surface-50 dark:bg-surface-800/50">
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-surface-500 dark:text-surface-400">{{ __('Name') }}</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-surface-500 dark:text-surface-400">{{ __('Email') }}</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-surface-500 dark:text-surface-400">{{ __('Role') }}</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-surface-500 dark:text-surface-400">{{ __('Status') }}</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-surface-500 dark:text-surface-400">{{ __('2FA') }}</th>
                        <th class="px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-surface-500 dark:text-surface-400">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-200 dark:divide-surface-700">
                    @foreach ($users as $user)
                        <tr class="hover:bg-surface-50 dark:hover:bg-surface-700/30 transition-colors">
                            @if ($editingUserId === $user->id)
                                <td class="px-6 py-4 text-sm font-medium text-surface-900 dark:text-white" colspan="6">
                                    <form wire:submit="saveUser" class="flex flex-wrap items-end gap-4">
                                        <div>
                                            <label class="block text-xs font-medium text-surface-500 dark:text-surface-400 mb-1">{{ __('Role') }}</label>
                                            <select wire:model="editingRole"
                                                class="rounded-xl border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 px-3 py-2 text-sm text-surface-900 dark:text-white focus:border-brand-400 focus:ring-brand-500/20 focus:ring-[3px]">
                                                @foreach (Spatie\Permission\Models\Role::all() as $role)
                                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-surface-500 dark:text-surface-400 mb-1">{{ __('Status') }}</label>
                                            <select wire:model="editingStatus"
                                                class="rounded-xl border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 px-3 py-2 text-sm text-surface-900 dark:text-white focus:border-brand-400 focus:ring-brand-500/20 focus:ring-[3px]">
                                                <option value="active">{{ __('Active') }}</option>
                                                <option value="suspended">{{ __('Suspended') }}</option>
                                                <option value="banned">{{ __('Banned') }}</option>
                                            </select>
                                        </div>
                                        <button type="submit"
                                            class="rounded-xl bg-gradient-to-r from-brand-500 to-brand-b-500 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-brand-500/25 hover:brightness-110 transition-all">
                                            {{ __('Save') }}
                                        </button>
                                        <button type="button" wire:click="cancelEdit"
                                            class="rounded-xl border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 px-4 py-2 text-sm font-medium text-surface-700 dark:text-surface-300 hover:bg-surface-50 dark:hover:bg-surface-600 transition-colors">
                                            {{ __('Cancel') }}
                                        </button>
                                    </form>
                                </td>
                            @else
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-brand-100 dark:bg-brand-500/20 text-xs font-bold text-brand-700 dark:text-brand-300">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <span class="text-sm font-medium text-surface-900 dark:text-white">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-surface-600 dark:text-surface-400">{{ $user->email }}</td>
                                <td class="px-6 py-4">
                                    @foreach ($user->roles as $role)
                                        <span class="inline-flex items-center rounded-full bg-brand-50 dark:bg-brand-500/10 px-2.5 py-0.5 text-xs font-medium text-brand-700 dark:text-brand-300 ring-1 ring-brand-200 dark:ring-brand-500/20">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1
                                        {{ $user->status === 'active' ? 'bg-success-50 dark:bg-success-500/10 text-success-700 dark:text-success-400 ring-success-200 dark:ring-success-500/20' : ($user->status === 'suspended' ? 'bg-warning-50 dark:bg-warning-500/10 text-yellow-700 dark:text-yellow-400 ring-yellow-200 dark:ring-yellow-500/20' : 'bg-error-50 dark:bg-error-500/10 text-error-700 dark:text-error-400 ring-error-200 dark:ring-error-500/20') }}">
                                        {{ $user->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-surface-600 dark:text-surface-400">
                                    {{ $user->hasTwoFactorEnabled() ? __('Yes') : __('No') }}
                                </td>
                                <td class="px-6 py-4 text-right text-sm">
                                    <button wire:click="edit({{ $user->id }})"
                                        class="inline-flex items-center gap-1.5 rounded-xl border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 px-3 py-1.5 text-xs font-medium text-surface-700 dark:text-surface-300 hover:bg-surface-50 dark:hover:bg-surface-600 transition-colors">
                                        <svg class="size-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                        {{ __('Edit') }}
                                    </button>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="flex justify-center">
        {{ $users->links() }}
    </div>
</div>

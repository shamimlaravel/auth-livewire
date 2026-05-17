<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class App extends Component
{
    public function render(): View|Closure|string
    {
        return function (array $data) {
            $sidebar = $data['sidebar'] ?? '';

            if (! trim($sidebar)) {
                $sidebar = '
                    <nav class="space-y-1">
                        <x-ui-sidebar-link :href="route(\'admin.dashboard\')" icon="home" :active="request()->routeIs(\'admin.dashboard\')" wire:navigate>
                            '.__('Dashboard').'
                        </x-ui-sidebar-link>
                        <x-ui-sidebar-link :href="route(\'admin.users\')" icon="users" :active="request()->routeIs(\'admin.users\')" wire:navigate>
                            '.__('Users').'
                        </x-ui-sidebar-link>
                        <x-ui-sidebar-link :href="route(\'admin.roles\')" icon="shield" :active="request()->routeIs(\'admin.roles\')" wire:navigate>
                            '.__('Roles').'
                        </x-ui-sidebar-link>
                    </nav>
                ';
            }

            $header = $data['header'] ?? '
                <div class="flex items-center gap-4">
                    <x-ui-dropdown align="right">
                        <x-slot:trigger>
                            <span class="text-sm font-medium">'.e(auth()->user()?->name ?? '').'</span>
                        </x-slot:trigger>
                        <a href="'.e(route('settings')).'" wire:navigate class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">'.__('Settings').'</a>
                        <form method="POST" action="'.e(route('logout')).'">
                            '.csrf_field().'
                            <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100">'.__('Logout').'</button>
                        </form>
                    </x-ui-dropdown>
                </div>
            ';

            $footer = $data['footer'] ?? '<p class="text-xs text-gray-400">'.__('Admin Panel').' &copy; '.date('Y').'</p>';

            return view('components.admin.app', compact('sidebar', 'header', 'footer'));
        };
    }
}

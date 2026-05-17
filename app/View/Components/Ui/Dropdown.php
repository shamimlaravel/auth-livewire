<?php

namespace App\View\Components\Ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Dropdown extends Component
{
    public function __construct(
        public string $align = 'right',
    ) {}

    public function render(): View|Closure|string
    {
        return function (array $data) {
            $alignClass = $this->align === 'left' ? 'left-0' : 'right-0';

            return '<div x-data="{ open: false }" class="relative">
                <button @click="open = !open" @click.away="open = false" type="button" class="flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-gray-900">
                    '.($data['trigger'] ?? '').'
                    '.app(Icon::class, ['name' => 'chevron-down', 'class' => 'h-4 w-4'])->render().'
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute '.e($alignClass).' z-50 mt-2 w-48 origin-top-right rounded-lg border border-gray-200 bg-white py-1 shadow-lg" @click="open = false">
                    '.($data['slot'] ?? '').'
                </div>
            </div>';
        };
    }
}

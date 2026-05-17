<?php

namespace App\View\Components\Ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Button extends Component
{
    public function __construct(
        public string $type = 'button',
        public string $variant = 'primary',
        public string $size = 'md',
        public ?string $href = null,
        public bool $navigate = false,
    ) {}

    public function render(): View|Closure|string
    {
        $base = 'inline-flex items-center justify-center font-semibold rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';

        $variants = [
            'primary' => 'bg-indigo-600 text-white hover:bg-indigo-500 focus:ring-indigo-500',
            'secondary' => 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 focus:ring-indigo-500',
            'danger' => 'bg-red-600 text-white hover:bg-red-500 focus:ring-red-500',
            'ghost' => 'text-gray-600 hover:bg-gray-100 focus:ring-gray-500',
        ];

        $sizes = [
            'sm' => 'px-3 py-1.5 text-xs',
            'md' => 'px-4 py-2 text-sm',
            'lg' => 'px-6 py-3 text-base',
        ];

        $classes = trim(implode(' ', [$base, $variants[$this->variant] ?? $variants['primary'], $sizes[$this->size] ?? $sizes['md']]));

        if ($this->href) {
            $attrs = 'href="'.e($this->href).'" class="'.e($classes).'"';
            if ($this->navigate) {
                $attrs .= ' wire:navigate';
            }

            return '<a '.$attrs.'>'.e($this->slot ?? '').'</a>';
        }

        return '<button type="'.e($this->type).'" class="'.e($classes).'">'.($this->slot ?? '').'</button>';
    }
}

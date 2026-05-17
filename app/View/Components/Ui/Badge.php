<?php

namespace App\View\Components\Ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Badge extends Component
{
    public function __construct(
        public string $variant = 'default',
        public string $size = 'sm',
    ) {}

    public function render(): View|Closure|string
    {
        $base = 'inline-flex items-center font-medium rounded-full';

        $variants = [
            'default' => 'bg-gray-100 text-gray-700',
            'primary' => 'bg-indigo-100 text-indigo-700',
            'success' => 'bg-green-100 text-green-700',
            'warning' => 'bg-yellow-100 text-yellow-700',
            'danger' => 'bg-red-100 text-red-700',
        ];

        $sizes = [
            'sm' => 'px-2 py-0.5 text-xs',
            'md' => 'px-2.5 py-1 text-sm',
        ];

        $class = trim($base.' '.($variants[$this->variant] ?? $variants['default']).' '.($sizes[$this->size] ?? $sizes['sm']));

        return '<span class="'.e($class).'">'.($this->slot ?? '').'</span>';
    }
}

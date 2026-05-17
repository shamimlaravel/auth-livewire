<?php

namespace App\View\Components\Ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SidebarLink extends Component
{
    public function __construct(
        public string $href,
        public string $icon = '',
        public bool $active = false,
        public bool $navigate = false,
    ) {}

    public function render(): View|Closure|string
    {
        $base = 'group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors';
        $activeClass = $this->active
            ? 'bg-indigo-50 text-indigo-700'
            : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900';

        $class = trim($base.' '.$activeClass);

        $attrs = 'href="'.e($this->href).'" class="'.e($class).'"';
        if ($this->navigate) {
            $attrs .= ' wire:navigate';
        }

        $html = '<a '.$attrs.'>';

        if ($this->icon) {
            $iconClass = $this->active ? 'h-5 w-5 text-indigo-500' : 'h-5 w-5 text-gray-400 group-hover:text-gray-500';
            $html .= app(Icon::class, ['name' => $this->icon, 'class' => $iconClass])->render().' ';
        }

        $html .= e($this->slot ?? '').'</a>';

        return $html;
    }
}

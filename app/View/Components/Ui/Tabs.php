<?php

namespace App\View\Components\Ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Tabs extends Component
{
    public function __construct(
        public string $active = '',
        public string $wire = 'switchTab',
    ) {}

    public function render(): View|Closure|string
    {
        return function (array $data) {
            $tabs = $data['tabs'] ?? $data['slot'] ?? [];

            if (is_string($tabs)) {
                return $tabs;
            }

            $html = '<div class="border-b border-surface-200 dark:border-surface-700">';
            $html .= '<nav class="-mb-px flex space-x-1 overflow-x-auto scrollbar-none" role="tablist">';

            foreach ($tabs as $tab) {
                $id = $tab['id'] ?? '';
                $label = $tab['label'] ?? '';
                $icon = $tab['icon'] ?? '';
                $isActive = $id === $this->active;

                $html .= '<button'
                    .' type="button"'
                    .' role="tab"'
                    .' aria-selected="'.($isActive ? 'true' : 'false').'"'
                    .' wire:click="'.e($this->wire).'(\''.e($id).'\')"'
                    .' class="group inline-flex items-center gap-2 whitespace-nowrap px-4 py-3 text-sm font-medium transition-all duration-200 border-b-2 -mb-px'
                    .($isActive
                        ? ' border-brand-500 text-brand-600 dark:text-brand-400'
                        : ' border-transparent text-surface-500 dark:text-surface-400 hover:text-surface-700 dark:hover:text-surface-300 hover:border-surface-300 dark:hover:border-surface-600'
                    )
                    .'">';

                if ($icon) {
                    $html .= '<svg class="size-4 '.($isActive ? 'text-brand-500' : 'text-surface-400 dark:text-surface-500 group-hover:text-surface-500').'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">';
                    $html .= $icon;
                    $html .= '</svg>';
                }

                $html .= e($label);
                $html .= '</button>';
            }

            $html .= '</nav>';
            $html .= '</div>';

            return $html;
        };
    }
}

<?php

namespace App\View\Components\Ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Toggle extends Component
{
    public function __construct(
        public string $model = '',
        public bool $enabled = false,
        public string $size = 'md',
        public ?string $label = null,
        public ?string $description = null,
    ) {}

    public function render(): View|Closure|string
    {
        return function (array $data) {
            $sizes = [
                'sm' => ['toggle' => 'h-5 w-9', 'thumb' => 'h-3.5 w-3.5', 'translate' => 'translate-x-3.5'],
                'md' => ['toggle' => 'h-6 w-11', 'thumb' => 'h-5 w-5', 'translate' => 'translate-x-5'],
                'lg' => ['toggle' => 'h-7 w-14', 'thumb' => 'h-6 w-6', 'translate' => 'translate-x-6'],
            ];

            $size = $sizes[$this->size] ?? $sizes['md'];

            $html = '<div class="flex items-center justify-between gap-4">';

            if ($this->label || $this->description) {
                $html .= '<div class="flex-1 min-w-0">';
                if ($this->label) {
                    $html .= '<p class="text-sm font-medium text-surface-900 dark:text-white">'.e($this->label).'</p>';
                }
                if ($this->description) {
                    $html .= '<p class="text-xs text-surface-500 dark:text-surface-400 mt-0.5">'.e($this->description).'</p>';
                }
                $html .= '</div>';
            }

            $wireClick = $this->model ? '$toggle(\''.e($this->model).'\')' : '';

            $html .= '<button type="button"'
                .($wireClick ? ' wire:click="'.$wireClick.'"' : '')
                .' role="switch"'
                .' aria-checked="'.($this->enabled ? 'true' : 'false').'"'
                .' class="relative inline-flex '.$size['toggle'].' shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-400 focus-visible:ring-offset-2'
                .($this->enabled ? ' bg-brand-500' : ' bg-surface-300 dark:bg-surface-600')
                .'"'
                .' x-data>'
                .'<span class="pointer-events-none inline-block '.$size['thumb'].' transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out'
                .($this->enabled ? ' '.$size['translate'] : ' translate-x-0')
                .'"></span>'
                .'</button>';

            $html .= '</div>';

            return $html;
        };
    }
}

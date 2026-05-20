<?php

namespace App\View\Components\Ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SettingsRow extends Component
{
    public function __construct(
        public string $label = '',
        public string $description = '',
        public ?string $icon = null,
    ) {}

    public function render(): View|Closure|string
    {
        return function (array $data) {
            $html = '<div class="flex items-start justify-between gap-4 py-4">';
            $html .= '<div class="flex items-start gap-3 min-w-0 flex-1">';

            if ($this->icon) {
                $html .= '<svg class="size-5 shrink-0 mt-0.5 text-surface-400 dark:text-surface-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">';
                $html .= $this->icon;
                $html .= '</svg>';
            }

            $html .= '<div class="min-w-0">';
            if ($this->label) {
                $html .= '<p class="text-sm font-medium text-surface-900 dark:text-white">'.e($this->label).'</p>';
            }
            if ($this->description) {
                $html .= '<p class="text-xs text-surface-500 dark:text-surface-400 mt-0.5">'.e($this->description).'</p>';
            }
            $html .= '</div>';
            $html .= '</div>';

            $html .= '<div class="shrink-0">';
            $html .= $data['slot'] ?? '';
            $html .= '</div>';

            $html .= '</div>';

            return $html;
        };
    }
}

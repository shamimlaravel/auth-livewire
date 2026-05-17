<?php

namespace App\View\Components\Ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Modal extends Component
{
    public function __construct(
        public string $id = 'modal',
        public string $maxWidth = '2xl',
    ) {}

    public function render(): View|Closure|string
    {
        $widths = [
            'sm' => 'max-w-sm',
            'md' => 'max-w-md',
            'lg' => 'max-w-lg',
            'xl' => 'max-w-xl',
            '2xl' => 'max-w-2xl',
            '3xl' => 'max-w-3xl',
            '4xl' => 'max-w-4xl',
            '5xl' => 'max-w-5xl',
        ];

        $maxWidthClass = $widths[$this->maxWidth] ?? 'max-w-2xl';

        return function (array $data) {
            $html = '<div x-data="{ show: false }" x-on:open-modal.window="if ($event.detail.id === \''.e($this->id).'\') show = true" x-on:close-modal.window="if ($event.detail.id === \''.e($this->id).'\') show = false" x-show="show" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">';
            $html .= '<div x-show="show" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/50" @click="show = false"></div>';
            $html .= '<div class="flex min-h-full items-center justify-center p-4">';
            $html .= '<div x-show="show" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4" class="relative w-full '.e($maxWidthClass).' rounded-xl bg-white p-6 shadow-xl">';

            if (isset($data['header'])) {
                $html .= '<div class="mb-4 flex items-center justify-between">';
                $html .= '<h3 class="text-lg font-semibold text-gray-900">'.$data['header'].'</h3>';
                $html .= '<button @click="show = false" type="button" class="text-gray-400 hover:text-gray-500">';
                $html .= app(Icon::class, ['name' => 'x-mark', 'class' => 'h-5 w-5'])->render();
                $html .= '</button></div>';
            }

            $html .= '<div>'.($data['slot'] ?? '').'</div>';

            if (isset($data['footer'])) {
                $html .= '<div class="mt-6 flex justify-end gap-3">'.$data['footer'].'</div>';
            }

            $html .= '</div></div></div>';

            return $html;
        };
    }
}

<?php

namespace App\View\Components\Ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Input extends Component
{
    public function __construct(
        public string $type = 'text',
        public string $name = '',
        public string $label = '',
        public string $value = '',
        public string $placeholder = '',
        public ?string $error = null,
        public ?string $icon = null,
        public bool $required = false,
        public bool $disabled = false,
    ) {}

    public function render(): View|Closure|string
    {
        return function (array $data) {
            $html = '';

            if ($this->label) {
                $html .= '<label for="'.e($this->name).'" class="block text-sm font-medium text-gray-700 mb-1">';
                $html .= e($this->label);
                if ($this->required) {
                    $html .= ' <span class="text-red-500">*</span>';
                }
                $html .= '</label>';
            }

            $inputClass = 'block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 disabled:bg-gray-100 disabled:text-gray-500';
            if ($this->error) {
                $inputClass .= ' border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500';
            }
            if ($this->icon) {
                $inputClass .= ' pl-10';
            }

            $attrs = 'type="'.e($this->type).'" name="'.e($this->name).'" id="'.e($this->name).'" value="'.e($this->value).'" placeholder="'.e($this->placeholder).'" class="'.e($inputClass).'"';
            if ($this->required) {
                $attrs .= ' required';
            }
            if ($this->disabled) {
                $attrs .= ' disabled';
            }

            $wrapperClass = 'relative';
            $html .= '<div class="'.$wrapperClass.'">';

            if ($this->icon) {
                $html .= '<div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">';
                $html .= app(Icon::class, ['name' => $this->icon, 'class' => 'h-5 w-5 text-gray-400'])->render();
                $html .= '</div>';
            }

            $html .= '<input '.$attrs.' />';
            $html .= '</div>';

            if ($this->error) {
                $html .= '<p class="mt-1 text-sm text-red-600">'.e($this->error).'</p>';
            }

            return $html;
        };
    }
}

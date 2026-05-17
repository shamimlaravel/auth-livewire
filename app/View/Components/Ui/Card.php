<?php

namespace App\View\Components\Ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Card extends Component
{
    public function __construct(
        public bool $padding = true,
        public string $class = '',
    ) {}

    public function render(): View|Closure|string
    {
        return function (array $data) {
            $paddingClass = $this->padding ? 'p-6' : '';
            $class = trim('bg-white rounded-lg border border-gray-200 '.$paddingClass.' '.$this->class);

            $html = '<div class="'.e($class).'">';

            if (isset($data['header']) && trim($data['header']->toHtml())) {
                $html .= '<div class="mb-4">'.$data['header'].'</div>';
            }

            $html .= '<div>'.($data['slot'] ?? '').'</div>';

            if (isset($data['footer']) && trim($data['footer']->toHtml())) {
                $html .= '<div class="mt-4 pt-4 border-t border-gray-200">'.$data['footer'].'</div>';
            }

            $html .= '</div>';

            return $html;
        };
    }
}

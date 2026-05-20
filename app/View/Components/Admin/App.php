<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class App extends Component
{
    public function __construct(
        public string $header = 'Admin Panel',
    ) {}

    public function render(): View|Closure|string
    {
        return function (array $data) {
            $header = $this->header;

            return view('components.admin.app', compact('header'));
        };
    }
}

<?php

namespace App\View\Components\Ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Accordion extends Component
{
    public function __construct(
        public string $id = 'accordion',
        public bool $multiple = false,
    ) {}

    public function render(): View|Closure|string
    {
        return function (array $data) {
            $items = $data['items'] ?? [];

            $html = '<div'
                .' x-data="{ open: '.($this->multiple ? '[]' : 'null').' }"'
                .' class="divide-y divide-surface-200 dark:divide-surface-700 rounded-xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 overflow-hidden"'
                .'>';

            foreach ($items as $index => $item) {
                $itemId = $item['id'] ?? $index;
                $title = $item['title'] ?? '';
                $icon = $item['icon'] ?? '';
                $summary = $item['summary'] ?? '';
                $content = $item['content'] ?? '';
                $isEnabled = $item['enabled'] ?? null;

                $isOpen = $this->multiple
                    ? 'open.includes('.json_encode((string) $itemId).')'
                    : 'open === '.json_encode((string) $itemId);

                $html .= '<div class="group">';
                $html .= '<button'
                    .' type="button"'
                    .' @click="open = '.($this->multiple
                        ? 'open.includes('.json_encode((string) $itemId).') ? open.filter(i => i !== '.json_encode((string) $itemId).') : [...open, '.json_encode((string) $itemId).']'
                        : '(open === '.json_encode((string) $itemId).' ? null : '.json_encode((string) $itemId).')'
                    ).'"'
                    .' class="flex w-full items-center justify-between gap-3 px-6 py-4 text-left transition-colors hover:bg-surface-50 dark:hover:bg-surface-700/50"'
                    .'>'
                    .'<div class="flex items-center gap-3 min-w-0">';

                if ($icon) {
                    $html .= '<svg class="size-5 shrink-0 text-surface-400 dark:text-surface-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">';
                    $html .= $icon;
                    $html .= '</svg>';
                }

                $html .= '<div class="min-w-0">';
                $html .= '<span class="text-sm font-medium text-surface-900 dark:text-white">'.e($title).'</span>';
                if ($summary) {
                    $html .= '<p class="text-xs text-surface-500 dark:text-surface-400 mt-0.5">'.e($summary).'</p>';
                }
                $html .= '</div>';
                $html .= '</div>';

                $html .= '<div class="flex items-center gap-3 shrink-0">';

                if ($isEnabled !== null) {
                    $html .= '<span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium'
                        .($isEnabled
                            ? ' bg-success-50 text-success-600 dark:bg-success-500/10 dark:text-success-400'
                            : ' bg-surface-100 text-surface-500 dark:bg-surface-700 dark:text-surface-400'
                        )
                        .'">'
                        .($isEnabled ? 'Enabled' : 'Disabled')
                        .'</span>';
                }

                $html .= '<svg'
                    .' class="size-4 shrink-0 text-surface-400 dark:text-surface-500 transition-transform duration-200"'
                    .' :class="{ \'rotate-180\': '.$isOpen.' }"'
                    .' xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">'
                    .'<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />'
                    .'</svg>';

                $html .= '</div>';
                $html .= '</button>';

                $html .= '<div'
                    .' x-show="'.$isOpen.'"'
                    .' x-collapse.duration.200ms'
                    .' class="px-6 pb-4"'
                    .'>';

                if ($content) {
                    $html .= '<div class="pt-2">'.$content.'</div>';
                }

                $html .= '</div>';
                $html .= '</div>';
            }

            $html .= '</div>';

            return $html;
        };
    }

    public static function item(string $id, string $title, string $content, ?string $icon = null, ?string $summary = null, ?bool $enabled = null): array
    {
        return [
            'id' => $id,
            'title' => $title,
            'icon' => $icon,
            'summary' => $summary,
            'content' => $content,
            'enabled' => $enabled,
        ];
    }
}

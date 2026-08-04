<?php

namespace App\NativeComponents;

use Illuminate\View\View;

class ShowcaseHome extends ShowcaseScreen
{
    /** @var list<array{label: string, tag: string, description: string, path: string}> */
    public array $components = [
        [
            'label' => 'Button',
            'tag' => '<firstlight:button>',
            'description' => 'Labelled actions, variants, sizes, icons, and states',
            'path' => '/button',
        ],
        [
            'label' => 'Segmented',
            'tag' => '<firstlight:segmented>',
            'description' => 'Server-authoritative single selection',
            'path' => '/segmented',
        ],
        [
            'label' => 'Status Label',
            'tag' => '<firstlight:status-label>',
            'description' => 'Compact semantic status metadata',
            'path' => '/status-label',
        ],
        [
            'label' => 'Text Field',
            'tag' => '<firstlight:text-field>',
            'description' => 'Native text entry, validation, and affordances',
            'path' => '/text-field',
        ],
    ];

    public function showsBackButton(): bool
    {
        return false;
    }

    public function navTitle(): string
    {
        return 'Firstlight UI';
    }

    public function render(): View
    {
        return view('native.showcase-home');
    }
}

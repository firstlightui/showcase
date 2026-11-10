<?php

namespace App\NativeComponents;

use Illuminate\View\View;

/**
 * Icons from resources/icons/*.svg, installed with `php artisan firstlight:icon`
 * and drawn natively as `custom:<name>` in Firstlight controls.
 */
class CustomIconsShowcase extends ShowcaseScreen
{
    public string $lastAction = 'None';

    public string $filter = 'plants';

    public string $branch = 'main';

    /** @var list<array{value: string, label: string, icon: string}> */
    public array $filterOptions = [
        ['value' => 'plants', 'label' => 'Plants', 'icon' => 'custom:plant-2'],
        ['value' => 'pets', 'label' => 'Pets', 'icon' => 'custom:paw-print-fill'],
        ['value' => 'food', 'label' => 'Food', 'icon' => 'custom:nutrition-outline'],
    ];

    public function recordPress(string $action): void
    {
        $this->lastAction = $action;
    }

    public function navTitle(): string
    {
        return 'Custom Icons';
    }

    public function render(): View
    {
        return view('native.custom-icons-showcase');
    }
}

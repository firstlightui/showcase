<?php

namespace App\NativeComponents;

use Illuminate\View\View;

class ShowcaseHome extends ShowcaseScreen
{
    /** @var list<array{label: string, tag: string, description: string, path: string}> */
    public array $components = [
        [
            'label' => 'Activity Indicator',
            'tag' => '<firstlight:activity-indicator>',
            'description' => 'Indeterminate native activity with semantic sizes',
            'path' => '/activity-indicator',
        ],
        [
            'label' => 'Button',
            'tag' => '<firstlight:button>',
            'description' => 'Labelled actions, variants, sizes, icons, and states',
            'path' => '/button',
        ],
        [
            'label' => 'Badge',
            'tag' => '<firstlight:badge>',
            'description' => 'Compact display-only counts and semantic markers',
            'path' => '/badge',
        ],
        [
            'label' => 'Checkbox',
            'tag' => '<firstlight:checkbox>',
            'description' => 'Server-authoritative Boolean form and checklist state',
            'path' => '/checkbox',
        ],
        [
            'label' => 'Choice Group',
            'tag' => '<firstlight:choice-group>',
            'description' => 'Visible single-radio and multiple-checkbox choices',
            'path' => '/choice-group',
        ],
        [
            'label' => 'Confirmation Dialog',
            'tag' => '<firstlight:confirmation-dialog>',
            'description' => 'Native confirmation, cancellation, and destructive roles',
            'path' => '/confirmation-dialog',
        ],
        [
            'label' => 'Date Picker',
            'tag' => '<firstlight:date-picker>',
            'description' => 'Nullable calendar dates, bounds, and native confirmation',
            'path' => '/date-picker',
        ],
        [
            'label' => 'Time Picker',
            'tag' => '<firstlight:time-picker>',
            'description' => 'Nullable wall-clock times and native confirmation',
            'path' => '/time-picker',
        ],
        [
            'label' => 'Icon Button',
            'tag' => '<firstlight:icon-button>',
            'description' => 'Compact accessible actions with native icon controls',
            'path' => '/icon-button',
        ],
        [
            'label' => 'List Item',
            'tag' => '<firstlight:list-item>',
            'description' => 'Tappable application rows with identity and affordances',
            'path' => '/list-item',
        ],
        [
            'label' => 'Pill Group',
            'tag' => '<firstlight:pill-group>',
            'description' => 'Compact single- and multiple-selection options',
            'path' => '/pill-group',
        ],
        [
            'label' => 'Progress',
            'tag' => '<firstlight:progress>',
            'description' => 'Determinate and indeterminate work state',
            'path' => '/progress',
        ],
        [
            'label' => 'Segmented',
            'tag' => '<firstlight:segmented>',
            'description' => 'Server-authoritative single selection',
            'path' => '/segmented',
        ],
        [
            'label' => 'Search Field',
            'tag' => '<firstlight:search-field>',
            'description' => 'Native query entry, clear, and submission behaviour',
            'path' => '/search-field',
        ],
        [
            'label' => 'Select',
            'tag' => '<firstlight:select>',
            'description' => 'Stable single selection with automatic search',
            'path' => '/select',
        ],
        [
            'label' => 'Slider',
            'tag' => '<firstlight:slider>',
            'description' => 'Strict stepped numeric ranges with native gestures',
            'path' => '/slider',
        ],
        [
            'label' => 'Stepper',
            'tag' => '<firstlight:stepper>',
            'description' => 'Exact bounded increments with server authority',
            'path' => '/stepper',
        ],
        [
            'label' => 'Status Label',
            'tag' => '<firstlight:status-label>',
            'description' => 'Compact semantic status metadata',
            'path' => '/status-label',
        ],
        [
            'label' => 'Switch',
            'tag' => '<firstlight:switch>',
            'description' => 'Server-authoritative boolean settings',
            'path' => '/switch',
        ],
        [
            'label' => 'Text Field',
            'tag' => '<firstlight:text-field>',
            'description' => 'Native text entry, validation, and affordances',
            'path' => '/text-field',
        ],
        [
            'label' => 'Text Area',
            'tag' => '<firstlight:text-area>',
            'description' => 'Native multiline editing, validation, and synchronisation',
            'path' => '/text-area',
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

<?php

namespace App\NativeComponents;

use Illuminate\View\View;

class ShowcaseHome extends ShowcaseScreen
{
    public string $query = '';

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
            'label' => 'Callout',
            'tag' => '<firstlight:callout>',
            'description' => 'Persistent semantic messages with an optional action',
            'path' => '/callout',
        ],
        [
            'label' => 'Transient Feedback',
            'tag' => 'Transient Feedback',
            'description' => 'Queued app-level outcomes that survive native navigation',
            'path' => '/transient-feedback',
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
            'label' => 'Media',
            'tag' => '<firstlight:media>',
            'description' => 'One image or document with Storage and optional crop',
            'path' => '/media',
        ],
        [
            'label' => 'Choice Group',
            'tag' => '<firstlight:choice-group>',
            'description' => 'Visible single-radio and multiple-checkbox choices',
            'path' => '/choice-group',
        ],
        [
            'label' => 'Alert Dialog',
            'tag' => '<firstlight:alert-dialog>',
            'description' => 'Native one-button acknowledgement',
            'path' => '/alert-dialog',
        ],
        [
            'label' => 'Confirmation Dialog',
            'tag' => '<firstlight:confirmation-dialog>',
            'description' => 'Native confirmation, cancellation, and destructive roles',
            'path' => '/confirmation-dialog',
        ],
        [
            'label' => 'Modal',
            'tag' => '<firstlight:modal>',
            'description' => 'Full-screen overlay for authored content',
            'path' => '/modal',
        ],
        [
            'label' => 'Bottom Sheet',
            'tag' => '<firstlight:bottom-sheet>',
            'description' => 'Bottom panel for authored content',
            'path' => '/bottom-sheet',
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
            'label' => 'Collection List',
            'tag' => '<firstlight:list> · <firstlight:list-section>',
            'description' => 'Grouped sections, refresh, pagination and authorized sample deletion',
            'path' => '/list',
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
            'label' => 'Tabs',
            'tag' => '<firstlight:tabs>',
            'description' => 'Server-authoritative sections with counts',
            'path' => '/tabs',
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
            'label' => 'Metric',
            'tag' => '<firstlight:metric>',
            'description' => 'One accessible measurement with tabular figures',
            'path' => '/metric',
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
        [
            'label' => 'Code Editor',
            'tag' => '<firstlight:code-editor>',
            'description' => 'Monospaced JSON, YAML, and env editing with a key row and live-log follow',
            'path' => '/code-editor',
        ],
        [
            'label' => 'Validation',
            'tag' => 'ValidatesFields · SubmitsForms',
            'description' => 'Laravel validation, accepted submissions and success feedback',
            'path' => '/validation',
        ],
    ];

    public function clearSearch(): void
    {
        $this->query = '';
    }

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
        $query = mb_strtolower(trim($this->query));
        $sections = collect($this->components)
            ->filter(fn (array $component): bool => $query === '' || str_contains(
                mb_strtolower(implode(' ', $component)), $query,
            ))
            ->sortBy('label')
            ->groupBy(fn (array $component): string => match ($component['path']) {
                '/button', '/icon-button' => 'Actions',
                '/checkbox', '/choice-group', '/pill-group', '/segmented', '/select', '/switch', '/tabs' => 'Choices and settings',
                '/date-picker', '/time-picker', '/slider', '/stepper' => 'Dates and numbers',
                '/alert-dialog', '/confirmation-dialog', '/modal', '/bottom-sheet' => 'Dialogs and overlays',
                '/list', '/list-item' => 'Lists and sections',
                '/media', '/search-field', '/text-field', '/text-area', '/code-editor' => 'Text and media',
                '/validation' => 'Laravel workflows',
                default => 'Status and feedback',
            })
            ->sortKeys();

        return view('native.showcase-home', ['sections' => $sections]);
    }
}

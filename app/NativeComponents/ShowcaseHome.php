<?php

namespace App\NativeComponents;

use Illuminate\View\View;

/**
 * The Components tab: every published control and its exact Blade tag.
 * Tags are searchable but not shown; each demo lives at /components/<slug>
 * so native chrome pushes it inside this tab.
 */
class ShowcaseHome extends ShowcaseScreen
{
    public string $query = '';

    /** @var array<string, string> Semantic icon tone for each catalogue section. */
    public const SECTION_TONES = [
        'Actions' => 'info',
        'Choices and settings' => 'success',
        'Dates and numbers' => 'warning',
        'Dialogs and overlays' => 'neutral',
        'Laravel workflows' => 'neutral',
        'Lists' => 'info',
        'Status and feedback' => 'warning',
        'Text and media' => 'success',
    ];

    /** @var list<array{label: string, tag: string, description: string, slug: string, section: string, icon: array{0: string, 1: string, 2: string}}> */
    public array $components = [
        ['label' => 'Activity Indicator', 'tag' => '<firstlight:activity-indicator>', 'description' => 'A spinner for work without a known end', 'slug' => 'activity-indicator', 'section' => 'Status and feedback', 'icon' => ['autorenew', 'rays', 'autorenew']],
        ['label' => 'Button', 'tag' => '<firstlight:button>', 'description' => 'Labelled actions in every variant and state', 'slug' => 'button', 'section' => 'Actions', 'icon' => ['touch_app', 'hand.tap.fill', 'touch_app']],
        ['label' => 'Callout', 'tag' => '<firstlight:callout>', 'description' => 'A message that stays, with an optional action', 'slug' => 'callout', 'section' => 'Status and feedback', 'icon' => ['announcement', 'exclamationmark.bubble.fill', 'announcement']],
        ['label' => 'Transient Feedback', 'tag' => 'Transient Feedback', 'description' => 'Brief outcomes with Undo that survive navigation', 'slug' => 'transient-feedback', 'section' => 'Status and feedback', 'icon' => ['chat_bubble', 'text.bubble.fill', 'chat_bubble']],
        ['label' => 'Badge', 'tag' => '<firstlight:badge>', 'description' => 'Counts and short markers', 'slug' => 'badge', 'section' => 'Status and feedback', 'icon' => ['new_releases', 'app.badge.fill', 'new_releases']],
        ['label' => 'Checkbox', 'tag' => '<firstlight:checkbox>', 'description' => 'Yes-or-no answers in forms and checklists', 'slug' => 'checkbox', 'section' => 'Choices and settings', 'icon' => ['check_box', 'checkmark.square.fill', 'check_box']],
        ['label' => 'Media', 'tag' => '<firstlight:media>', 'description' => 'Pick a photo or document, crop it and store it', 'slug' => 'media', 'section' => 'Text and media', 'icon' => ['photo_library', 'photo.on.rectangle.angled', 'photo_library']],
        ['label' => 'Choice Group', 'tag' => '<firstlight:choice-group>', 'description' => 'Every option in view, one or many', 'slug' => 'choice-group', 'section' => 'Choices and settings', 'icon' => ['radio_button_checked', 'list.bullet.circle.fill', 'radio_button_checked']],
        ['label' => 'Alert Dialog', 'tag' => '<firstlight:alert-dialog>', 'description' => 'One message the user must acknowledge', 'slug' => 'alert-dialog', 'section' => 'Dialogs and overlays', 'icon' => ['error', 'exclamationmark.circle.fill', 'error']],
        ['label' => 'Confirmation Dialog', 'tag' => '<firstlight:confirmation-dialog>', 'description' => 'Confirm or cancel, including destructive actions', 'slug' => 'confirmation-dialog', 'section' => 'Dialogs and overlays', 'icon' => ['help', 'questionmark.circle.fill', 'help']],
        ['label' => 'Modal', 'tag' => '<firstlight:modal>', 'description' => 'A full-screen layer for focused content', 'slug' => 'modal', 'section' => 'Dialogs and overlays', 'icon' => ['fullscreen', 'rectangle.inset.filled', 'fullscreen']],
        ['label' => 'Bottom Sheet', 'tag' => '<firstlight:bottom-sheet>', 'description' => 'A panel that rises from the bottom edge', 'slug' => 'bottom-sheet', 'section' => 'Dialogs and overlays', 'icon' => ['vertical_align_bottom', 'rectangle.bottomhalf.inset.filled', 'vertical_align_bottom']],
        ['label' => 'Date Picker', 'tag' => '<firstlight:date-picker>', 'description' => 'Calendar dates with limits and a system sheet', 'slug' => 'date-picker', 'section' => 'Dates and numbers', 'icon' => ['event', 'calendar', 'event']],
        ['label' => 'Time Picker', 'tag' => '<firstlight:time-picker>', 'description' => 'Clock times in the user’s own format', 'slug' => 'time-picker', 'section' => 'Dates and numbers', 'icon' => ['schedule', 'clock.fill', 'schedule']],
        ['label' => 'Icon Button', 'tag' => '<firstlight:icon-button>', 'description' => 'Compact symbol actions with spoken names', 'slug' => 'icon-button', 'section' => 'Actions', 'icon' => ['add_circle', 'plus.circle.fill', 'add_circle']],
        ['label' => 'List Item', 'tag' => '<firstlight:list-item>', 'description' => 'Rows with icons, swipe actions and context menus', 'slug' => 'list-item', 'section' => 'Lists', 'icon' => ['view_list', 'list.bullet.rectangle.fill', 'view_list']],
        ['label' => 'Collection List', 'tag' => '<firstlight:list> · <firstlight:list-section>', 'description' => 'Sections, pull to refresh, paging and deletion', 'slug' => 'list', 'section' => 'Lists', 'icon' => ['view_agenda', 'rectangle.grid.1x2.fill', 'view_agenda']],
        ['label' => 'Pill Group', 'tag' => '<firstlight:pill-group>', 'description' => 'Compact chips for filters and tags', 'slug' => 'pill-group', 'section' => 'Choices and settings', 'icon' => ['filter_list', 'line.3.horizontal.decrease.circle.fill', 'filter_list']],
        ['label' => 'Progress', 'tag' => '<firstlight:progress>', 'description' => 'How far along work is, or that it is running', 'slug' => 'progress', 'section' => 'Status and feedback', 'icon' => ['hourglass_empty', 'hourglass', 'hourglass_empty']],
        ['label' => 'Segmented', 'tag' => '<firstlight:segmented>', 'description' => 'Pick one of a few side-by-side options', 'slug' => 'segmented', 'section' => 'Choices and settings', 'icon' => ['view_week', 'rectangle.split.3x1.fill', 'view_week']],
        ['label' => 'Tabs', 'tag' => '<firstlight:tabs>', 'description' => 'Switch sections in place, with counts', 'slug' => 'tabs', 'section' => 'Choices and settings', 'icon' => ['tab', 'rectangle.topthird.inset.filled', 'tab']],
        ['label' => 'Search Field', 'tag' => '<firstlight:search-field>', 'description' => 'Type, clear and submit a query', 'slug' => 'search-field', 'section' => 'Text and media', 'icon' => ['search', 'magnifyingglass', 'search']],
        ['label' => 'Select', 'tag' => '<firstlight:select>', 'description' => 'One choice from a long list, with search', 'slug' => 'select', 'section' => 'Choices and settings', 'icon' => ['unfold_more', 'chevron.up.chevron.down', 'unfold_more']],
        ['label' => 'Slider', 'tag' => '<firstlight:slider>', 'description' => 'Drag through a stepped range', 'slug' => 'slider', 'section' => 'Dates and numbers', 'icon' => ['tune', 'slider.horizontal.3', 'tune']],
        ['label' => 'Stepper', 'tag' => '<firstlight:stepper>', 'description' => 'Exact counts, one step at a time', 'slug' => 'stepper', 'section' => 'Dates and numbers', 'icon' => ['exposure', 'plusminus', 'exposure']],
        ['label' => 'Status Label', 'tag' => '<firstlight:status-label>', 'description' => 'A short status with a meaningful colour', 'slug' => 'status-label', 'section' => 'Status and feedback', 'icon' => ['label', 'tag.fill', 'label']],
        ['label' => 'Metric', 'tag' => '<firstlight:metric>', 'description' => 'One measurement with tabular figures', 'slug' => 'metric', 'section' => 'Status and feedback', 'icon' => ['numbers', 'number', 'numbers']],
        ['label' => 'Switch', 'tag' => '<firstlight:switch>', 'description' => 'Turn a setting on or off', 'slug' => 'switch', 'section' => 'Choices and settings', 'icon' => ['toggle_on', 'switch.2', 'toggle_on']],
        ['label' => 'Text Field', 'tag' => '<firstlight:text-field>', 'description' => 'Single-line entry with keyboards and validation', 'slug' => 'text-field', 'section' => 'Text and media', 'icon' => ['text_fields', 'character.cursor.ibeam', 'text_fields']],
        ['label' => 'Text Area', 'tag' => '<firstlight:text-area>', 'description' => 'Multi-line writing that grows with its text', 'slug' => 'text-area', 'section' => 'Text and media', 'icon' => ['notes', 'text.alignleft', 'notes']],
        ['label' => 'Code Editor', 'tag' => '<firstlight:code-editor>', 'description' => 'JSON, YAML and env files with a key row', 'slug' => 'code-editor', 'section' => 'Text and media', 'icon' => ['code', 'chevron.left.forwardslash.chevron.right', 'code']],
        ['label' => 'Validation', 'tag' => 'ValidatesFields · SubmitsForms', 'description' => 'Laravel rules, error messages and success feedback', 'slug' => 'validation', 'section' => 'Laravel workflows', 'icon' => ['verified', 'checkmark.seal.fill', 'verified']],
    ];

    public function openDemo(string $slug): void
    {
        if (in_array($slug, array_column($this->components, 'slug'), true)) {
            $this->navigate('/components/'.$slug);
        }
    }

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
        return 'Components';
    }

    public function render(): View
    {
        $query = mb_strtolower(trim($this->query));

        $sections = collect($this->components)
            ->filter(fn (array $component): bool => $query === '' || str_contains(
                mb_strtolower(implode(' ', [$component['label'], $component['tag'], $component['description'], $component['section']])),
                $query,
            ))
            ->sortBy('label')
            ->groupBy('section')
            ->sortKeys();

        return view('native.showcase-home', [
            'sections' => $sections,
            'tones' => self::SECTION_TONES,
        ]);
    }
}

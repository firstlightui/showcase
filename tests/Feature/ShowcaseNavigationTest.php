<?php

use App\Models\Post;
use App\NativeComponents\ShowcaseHome;
use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function showcaseNodesOfType(array $tree, string $type): array
{
    $nodes = ($tree['type'] ?? null) === $type ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...showcaseNodesOfType($child, $type)];
    }

    return $nodes;
}

/** @return list<string> */
function showcaseHeadlines(array $tree): array
{
    return array_column(array_column(showcaseNodesOfType($tree, 'firstlight.list-item'), 'props'), 'headline');
}

/** Every published component demo, keyed by catalogue headline. */
dataset('component demos', [
    ['Activity Indicator', '/components/activity-indicator'],
    ['Button', '/components/button'],
    ['Callout', '/components/callout'],
    ['Transient Feedback', '/components/transient-feedback'],
    ['Badge', '/components/badge'],
    ['Checkbox', '/components/checkbox'],
    ['Media', '/components/media'],
    ['Choice Group', '/components/choice-group'],
    ['Alert Dialog', '/components/alert-dialog'],
    ['Confirmation Dialog', '/components/confirmation-dialog'],
    ['Modal', '/components/modal'],
    ['Bottom Sheet', '/components/bottom-sheet'],
    ['Date Picker', '/components/date-picker'],
    ['Time Picker', '/components/time-picker'],
    ['Icon Button', '/components/icon-button'],
    ['List Item', '/components/list-item'],
    ['Collection List', '/components/list'],
    ['Pill Group', '/components/pill-group'],
    ['Progress', '/components/progress'],
    ['Segmented', '/components/segmented'],
    ['Search Field', '/components/search-field'],
    ['Select', '/components/select'],
    ['Slider', '/components/slider'],
    ['Stepper', '/components/stepper'],
    ['Status Label', '/components/status-label'],
    ['Metric', '/components/metric'],
    ['Switch', '/components/switch'],
    ['Tabs', '/components/tabs'],
    ['Text Field', '/components/text-field'],
    ['Text Area', '/components/text-area'],
    ['Code Editor', '/components/code-editor'],
    ['Validation', '/components/validation'],
]);

it('puts Gallery, Recipes, Components and Docs in one native tab bar', function (string $path, string $title, string $activeTab) {
    $screen = Native::visit($path)
        ->assertHasTabBar()
        ->assertHasTab('Gallery')
        ->assertHasTab('Recipes')
        ->assertHasTab('Components')
        ->assertHasTab('Docs')
        ->assertTabActive($activeTab)
        ->assertNavTitle($title)
        ->assertAccessible();

    $tabs = showcaseNodesOfType($screen->tree(), 'bottom_nav_item');
    $root = showcaseNodesOfType($screen->tree(), 'native_root_tabs')[0];

    expect(array_column(array_column($tabs, 'props'), 'url'))->toBe(['/', '/recipes', '/components', '/docs'])
        ->and($root['props']['nav_back'])->toBeFalse()
        // System-drawn bars: no colours, so both platforms follow the device appearance.
        ->and($root['props'])->not->toHaveKeys(['background_color', 'active_color', 'text_color', 'dark', 'nav_background_color']);
})->with([
    'gallery' => ['/', 'Gallery', 'Gallery'],
    'recipes' => ['/recipes', 'Recipes', 'Recipes'],
    'components' => ['/components', 'Components', 'Components'],
    'documentation' => ['/docs', 'Documentation', 'Docs'],
]);

it('lists every published component with a tinted icon and no Blade tag overline', function () {
    $screen = Native::visit('/components');
    $items = showcaseNodesOfType($screen->tree(), 'firstlight.list-item');
    $catalogue = (new ShowcaseHome)->components;

    expect(showcaseHeadlines($screen->tree()))->toEqualCanonicalizing(array_column($catalogue, 'label'))
        ->and($items)->toHaveCount(32);

    foreach ($items as $item) {
        expect($item['props'])->not->toHaveKey('overline')
            ->and($item['props']['leading_type'] ?? null)->toBe('icon')
            ->and($item['props']['leading_icon_tone'] ?? null)->toBeIn(['neutral', 'info', 'success', 'warning'])
            ->and($item['props']['supporting'] ?? '')->not->toBe('')
            ->and($item['props']['supporting'])->not->toContain('<firstlight:');
    }

    expect(array_column(array_column(showcaseNodesOfType($screen->tree(), 'list_section'), 'props'), 'header'))->toBe([
        'Actions', 'Choices and settings', 'Dates and numbers', 'Dialogs and overlays',
        'Laravel workflows', 'Lists', 'Status and feedback', 'Text and media',
    ]);
});

it('keeps the exact Blade tag of every catalogue entry searchable', function () {
    foreach ((new ShowcaseHome)->components as $component) {
        expect($component['tag'])->not->toBe('');
    }

    $screen = Native::visit('/components')->select('query', '  FIRSTLIGHT:list-section ');
    expect(showcaseHeadlines($screen->tree()))->toBe(['Collection List']);

    $screen->select('query', ' CLOCK ');
    expect(showcaseHeadlines($screen->tree()))->toBe(['Time Picker']);
    $screen->tap('Time Picker')->assertNavigatedTo('/components/time-picker');
});

it('explains an empty catalogue search and clears it', function () {
    $screen = Native::visit('/components')
        ->select('query', 'no such control')
        ->assertAccessible();
    $callout = showcaseNodesOfType($screen->tree(), 'firstlight.callout')[0];

    expect(showcaseHeadlines($screen->tree()))->toBe([])
        ->and($callout['props'])->toMatchArray([
            'message' => 'No component matches that search.',
            'action_label' => 'Clear search',
        ]);

    $screen->press('clearSearch')->assertSet('query', '');
    expect(showcaseHeadlines($screen->tree()))->toHaveCount(32);
});

it('pushes each component demo inside the Components tab', function (string $label, string $path) {
    Native::visit('/components')
        ->tap($label)
        ->assertNavigatedTo($path);

    Native::visit($path)
        ->assertSee('On-device checks')
        ->assertTabActive('Components')
        ->assertAccessible();

    expect(Native::visit($path)->tree()['props']['nav_back'] ?? null)->toBeTrue();
})->with('component demos');

it('opens each documented recipe from the Recipes tab', function (string $label) {
    $this->artisan('migrate');
    $newest = Post::query()->latest()->orderByDesc('id')->value('id');
    $path = match ($label) {
        'Posts' => '/posts',
        'Post detail' => '/posts/'.$newest,
        'New post' => '/posts/create',
        'Settings' => '/settings',
        'Contacts' => '/contacts',
        'Cover photo' => '/posts/'.$newest.'/cover',
    };

    Native::visit('/recipes')
        ->tap($label)
        ->assertNavigatedTo($path);

    Native::visit($path)->assertAccessible();
})->with(['Posts', 'Post detail', 'Contacts', 'New post', 'Settings', 'Cover photo']);

it('presents the recipes as app entry points with icons and descriptions', function () {
    $screen = Native::visit('/recipes')->assertAccessible();
    $items = showcaseNodesOfType($screen->tree(), 'firstlight.list-item');

    expect(array_column(array_column($items, 'props'), 'headline'))->toBe(['Posts', 'Post detail', 'Contacts', 'New post', 'Settings', 'Cover photo']);

    foreach ($items as $item) {
        expect($item['props']['leading_type'] ?? null)->toBe('icon')
            ->and($item['props']['supporting'] ?? '')->not->toBe('');
    }
});

it('keeps capture fixtures free of tabs and back navigation', function () {
    foreach (['/captures/activity-indicator', '/captures/button', '/captures/callout', '/captures/transient-feedback', '/captures/badge', '/captures/checkbox', '/captures/media', '/captures/choice-group', '/captures/alert-dialog', '/captures/confirmation-dialog', '/captures/modal', '/captures/bottom-sheet', '/captures/date-picker', '/captures/time-picker', '/captures/tabs', '/captures/icon-button', '/captures/list-item', '/captures/list', '/captures/search-field', '/captures/select', '/captures/slider', '/captures/stepper', '/captures/text-area', '/captures/code-editor', '/captures/validate-fields'] as $path) {
        $tree = Native::visit($path)->tree();

        expect($tree['type'])->toBe('native_root_stack')
            ->and($tree['props']['back'] ?? null)->toBeFalse()
            ->and(showcaseNodesOfType($tree, 'bottom_nav_item'))->toBe([]);
    }
});

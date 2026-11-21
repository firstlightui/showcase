<?php

use App\NativeComponents\CustomTabsDemoScreen;
use FirstlightUI\CustomTabs\Facades\CustomTabs;
use FirstlightUI\CustomTabs\Screens\EditorScreen;
use FirstlightUI\CustomTabs\Screens\MoreScreen;
use Native\Mobile\Testing\Native;

beforeEach(fn () => CustomTabs::reset('tabs-demo'));
afterEach(fn () => CustomTabs::reset('tabs-demo'));

/** @return list<array<string, mixed>> */
function customTabsNodes(array $node, string $type): array
{
    $found = ($node['type'] ?? null) === $type ? [$node] : [];
    foreach ($node['children'] ?? [] as $child) {
        $found = [...$found, ...customTabsNodes($child, $type)];
    }

    return $found;
}

/** @return list<string> */
function customTabsBar(string $uri): array
{
    return array_map(fn ($tab) => $tab['props']['label'], customTabsNodes(Native::visit($uri)->tree(), 'bottom_nav_item'));
}

it('opens from Recipes into a bar with Home first and More last', function () {
    Native::visit('/recipes')->call('openCustomTabs')->assertNavigatedTo('/tabs/home');

    Native::visit('/tabs/home')->assertScreen(CustomTabsDemoScreen::class);
    expect(customTabsBar('/tabs/home'))->toBe(['Home', 'Inbox', 'Calendar', 'Reports', 'More']);
});

it('lists People and Files on the More screen and edits from there', function () {
    $rows = customTabsNodes(Native::visit('/tabs/more')->assertScreen(MoreScreen::class)->tree(), 'firstlight.list-item');

    expect(array_map(fn ($row) => $row['props']['headline'], $rows))->toBe(['People', 'Files']);

    Native::visit('/tabs/more')->call('edit')->assertNavigatedTo('/tabs/more/edit');
});

it('changes the real bar only when the editor saves', function () {
    $editor = Native::visit('/tabs/more/edit')->assertScreen(EditorScreen::class);
    $editor->call('move', ['bar' => ['home', 'files', 'inbox', 'calendar', 'reports'], 'more' => ['people']]);

    expect(customTabsBar('/tabs/home'))->toBe(['Home', 'Inbox', 'Calendar', 'Reports', 'More']);

    $editor->call('done');

    expect(customTabsBar('/tabs/home'))->toBe(['Home', 'Files', 'Inbox', 'Calendar', 'More']);
});

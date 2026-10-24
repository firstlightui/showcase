<?php

use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function listShowcaseNodes(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.list' ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...listShowcaseNodes($child)];
    }

    return $nodes;
}

/** @return list<array<string, mixed>> */
function listShowcaseItemNodes(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.list-item' ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...listShowcaseItemNodes($child)];
    }

    return $nodes;
}

it('publishes grouped list fixtures through the native wire tree', function () {
    $screen = Native::visit('/components/list');
    $lists = listShowcaseNodes($screen->tree());
    $items = listShowcaseItemNodes($screen->tree());

    expect($screen->tree()['props']['nav_title'] ?? null)->toBe('Firstlight List')
        ->and($lists)->toHaveCount(1)
        ->and($lists[0]['props']['separator'] ?? null)->toBeTrue()
        ->and($lists[0]['props']['on_refresh'] ?? null)->toBeInt()
        ->and($lists[0]['props']['on_end_reached'] ?? null)->toBeInt()
        ->and($items)->toHaveCount(11)
        ->and(array_column(array_column(array_slice($items, 0, 3), 'props'), 'headline'))->toBe([
            'Account',
            'Notifications',
            'Privacy',
        ]);

    $sections = array_values(array_filter(
        $lists[0]['children'] ?? [],
        fn (array $child): bool => ($child['type'] ?? null) === 'list_section',
    ));

    expect($sections)->toHaveCount(2)
        ->and($sections[0]['props'])->toMatchArray([
            'header' => 'Preferences',
            'footer' => 'Demo rows record presses; they do not open settings.',
        ]);

    foreach ($items as $item) {
        expect($item['on_press'] ?? null)->toBeInt();
    }

    $screen->assertAccessible();
});

it('dispatches row presses and refresh callbacks through PHP', function () {
    $screen = Native::visit('/components/list');
    $screen->tap('Privacy');

    expect($screen->get('pressCount'))->toBe(1)
        ->and($screen->get('lastPressed'))->toBe('Privacy');
});

it('loads sample pages once and refreshes to the first page', function () {
    $screen = Native::visit('/components/list');
    expect(array_column($screen->get('listItems'), 'id'))->toBe(range(1001, 1008));

    $screen->press('loadMore')->press('loadMore')->press('loadMore');
    expect(array_column($screen->get('listItems'), 'id'))->toBe(range(1001, 1024));
    $screen->assertSet('listHasMore', false)->assertSee('End of sample data');

    $screen->press('reloadItems');
    expect(array_column($screen->get('listItems'), 'id'))->toBe(range(1001, 1008));
    $screen->assertSet('refreshCount', 1)->assertSet('listHasMore', true);
});

it('keeps a cancelled sample row and removes only a confirmed stable key', function () {
    $screen = Native::visit('/components/list')->tap('Sample item 1003')
        ->dismissSheet('deleteSampleDialog');
    expect(array_column($screen->get('listItems'), 'id'))->toBe(range(1001, 1008));

    $screen->tap('Sample item 1003')->tap('deleteSampleDialog');
    expect(array_column($screen->get('listItems'), 'id'))->toBe([1001, 1002, 1004, 1005, 1006, 1007, 1008]);
    $screen->tap('Load next page')->tap('Load next page');
    expect(array_column($screen->get('listItems'), 'id'))->toBe(array_values(array_diff(range(1001, 1024), [1003])));

    $screen->press('reloadItems');
    expect(array_column($screen->get('listItems'), 'id'))->not->toContain(1003);

    $screen->tap('Test options')->assertSet('testOptionsVisible', true)
        ->tap('Reset sample data')->tap('Return to list')->assertSet('testOptionsVisible', false);
    expect(array_column($screen->get('listItems'), 'id'))->toBe(range(1001, 1008));
});

it('rechecks sample authorization when confirmation arrives', function () {
    $screen = Native::visit('/components/list')->tap('Sample item 1003')
        ->select('deletionAllowed', false)->tap('deleteSampleDialog');

    expect(array_column($screen->get('listItems'), 'id'))->toBe(range(1001, 1008));
    $screen->assertSet('confirmingListDestruction', false);
});

it('lets the tester switch list presentation and restore an empty preview', function () {
    $screen = Native::visit('/components/list')->tap('Test options')->assertSet('testOptionsVisible', true)
        ->select('plain', true)->select('separators', false)
        ->select('deletionAllowed', false);
    $list = listShowcaseNodes($screen->tree())[0];
    expect($list['props']['plain'])->toBeTrue()
        ->and($list['props']['separator'] ?? false)->toBeFalse();
    foreach (array_slice(listShowcaseItemNodes($screen->tree()), 3) as $item) {
        expect($item['props']['disabled'])->toBeTrue();
    }

    $screen->select('empty', true);
    expect(listShowcaseItemNodes($screen->tree()))->toBe([]);
    $screen->assertSee('Empty list')->tap('Reset sample data');
    expect(listShowcaseItemNodes($screen->tree()))->toHaveCount(11);
    $screen->dismissSheet('testOptions')->assertSet('testOptionsVisible', false);
});

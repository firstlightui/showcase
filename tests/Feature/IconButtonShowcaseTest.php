<?php

use Native\Mobile\Events\Async\AsyncTaskFinished;
use Native\Mobile\Support\AsyncTaskTransport;
use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function iconButtonShowcaseNodes(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.icon-button' ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...iconButtonShowcaseNodes($child)];
    }

    return $nodes;
}

it('publishes the complete Icon Button gallery through the native wire tree', function () {
    $screen = Native::visit('/components/icon-button');
    $nodes = iconButtonShowcaseNodes($screen->tree());

    expect($screen->tree()['props']['nav_title'] ?? null)->toBe('Firstlight Icon Button')
        ->and($nodes)->toHaveCount(14)
        ->and(array_column(array_slice(array_column($nodes, 'props'), 0, 5), 'variant'))->toBe([
            'primary',
            'secondary',
            'destructive',
            'success',
            'ghost',
        ])
        ->and(array_column(array_slice(array_column($nodes, 'props'), 5, 3), 'size'))->toBe([
            'sm',
            'md',
            'lg',
        ])
        ->and($nodes[8]['props']['icon'])->toBeString()
        ->and($nodes[8]['props']['a11y_label'])->toBe('Share item')
        ->and($nodes[9]['props']['disabled'])->toBeTrue()
        ->and($nodes[10]['props']['loading'])->toBeTrue();

    foreach ($nodes as $node) {
        expect($node['on_press'] ?? null)->toBeInt();
    }

    $screen->assertAccessible();
});

it('dispatches an ordinary icon press and publishes the accepted PHP result', function () {
    $screen = Native::visit('/components/icon-button');
    $screen->tap('Record press');

    expect($screen->get('pressCount'))->toBe(1);
    $screen->assertSee('Recorded presses: 1');

    $screen->tap('Share item')->assertSet('pressCount', 2)
        ->assertSee('Last action: Share item');
    $screen->tap('Delete draft')->assertSet('pressCount', 3)
        ->assertSee('Last action: Delete draft');
});

/** @return array<string, mixed> */
function iconButtonShowcaseProps(array $tree, string $a11yLabel): array
{
    foreach (iconButtonShowcaseNodes($tree) as $node) {
        if (($node['props']['a11y_label'] ?? null) === $a11yLabel) {
            return $node['props'];
        }
    }

    throw new RuntimeException("No icon button named [{$a11yLabel}].");
}

it('publishes a PHP-owned press to loading to result transition', function () {
    $bridge = Native::fakeBridge()->respondTo('AsyncTask.Dispatch', ['success' => true]);
    $screen = Native::visit('/components/icon-button');

    expect(iconButtonShowcaseProps($screen->tree(), 'Sync inbox'))->toMatchArray([
        'loading' => false,
        'disabled' => false,
    ]);

    $screen->tap('Sync inbox')
        ->assertSet('syncing', true)
        ->assertSee('PHP accepted state: Syncing… · 0 synced · loading · enabled');
    expect(iconButtonShowcaseProps($screen->tree(), 'Sync inbox')['loading'])->toBeTrue();

    $screen->call('syncInbox');
    expect($bridge->callsTo('AsyncTask.Dispatch'))->toHaveCount(1);

    $id = $bridge->callsTo('AsyncTask.Dispatch')[0]['params']['id'];
    $screen->emitNative(AsyncTaskFinished::class, ['id' => $id])
        ->assertSet('syncing', false)
        ->assertSet('syncCount', 1)
        ->assertSee('PHP accepted state: Inbox synced · 1 synced · idle · enabled');
    expect(iconButtonShowcaseProps($screen->tree(), 'Sync inbox')['loading'])->toBeFalse()
        ->and($screen->get('pressCount'))->toBe(0);

    AsyncTaskTransport::forgetPayload($id);
});

it('toggles disabled on the same icon button from PHP', function () {
    $screen = Native::visit('/components/icon-button');

    $screen->tap('Lock syncing')->assertSet('syncLocked', true);
    expect(iconButtonShowcaseProps($screen->tree(), 'Sync inbox')['disabled'])->toBeTrue()
        ->and(iconButtonShowcaseProps($screen->tree(), 'Unlock syncing')['icon'])->toBe('lock');

    $screen->call('syncInbox')->assertSet('syncing', false)->assertSet('syncCount', 0);

    $screen->tap('Unlock syncing')->assertSet('syncLocked', false);
    expect(iconButtonShowcaseProps($screen->tree(), 'Sync inbox')['disabled'])->toBeFalse();

    $screen->assertAccessible();
});

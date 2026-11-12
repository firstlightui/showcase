<?php

use Native\Mobile\Events\Async\AsyncTaskFinished;
use Native\Mobile\Support\AsyncTaskTransport;
use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function buttonShowcaseNodes(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.button' ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...buttonShowcaseNodes($child)];
    }

    return $nodes;
}

it('publishes the complete Button gallery through the native wire tree', function () {
    $screen = Native::visit('/components/button');
    $nodes = buttonShowcaseNodes($screen->tree());

    expect($screen->tree()['props']['nav_title'] ?? null)->toBe('Firstlight Button')
        ->and(array_column(array_column($nodes, 'props'), 'label'))->toBe([
            'Primary action',
            'Secondary action',
            'Delete draft',
            'Confirm selection',
            'Ghost action',
            'Small',
            'Medium',
            'Large',
            'Add item',
            'Continue',
            'Unavailable',
            'Saving changes',
            'Send reminder',
            'Lock sending',
            'Pay',
            'Record press',
        ])
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
        ->and($nodes[8]['props']['leading_icon'])->toBe('plus')
        ->and($nodes[9]['props']['trailing_icon'])->toBe('chevron-right')
        ->and($nodes[10]['props']['disabled'])->toBeTrue()
        ->and($nodes[11]['props']['loading'])->toBeTrue();

    $screen->assertAccessible();
});

it('dispatches an ordinary press and publishes the accepted PHP result', function () {
    $screen = Native::visit('/components/button');
    $screen->tap('Record press');

    expect($screen->get('pressCount'))->toBe(1);
    $screen->assertSee('Recorded presses: 1');
});

it('does not register callbacks for disabled and loading examples', function () {
    $nodes = buttonShowcaseNodes(Native::visit('/components/button')->tree());

    expect($nodes[10]['props'])->not->toHaveKey('on_press')
        ->and($nodes[11]['props'])->not->toHaveKey('on_press');
});

/** @return array<string, mixed> */
function buttonShowcaseProps(array $tree, string $label): array
{
    foreach (buttonShowcaseNodes($tree) as $node) {
        if (($node['props']['label'] ?? null) === $label) {
            return $node['props'];
        }
    }

    throw new RuntimeException("No button labelled [{$label}].");
}

it('publishes a PHP-owned press to loading to result transition', function () {
    $bridge = Native::fakeBridge()->respondTo('AsyncTask.Dispatch', ['success' => true]);
    $screen = Native::visit('/components/button');

    expect(buttonShowcaseProps($screen->tree(), 'Send reminder'))->toMatchArray([
        'loading' => false,
        'disabled' => false,
        'leading_icon' => 'mail',
    ])->and(buttonShowcaseProps($screen->tree(), 'Send reminder')['on_press'] ?? null)->toBeInt();

    $screen->tap('Send reminder')
        ->assertSet('sending', true)
        ->assertSee('PHP accepted state: Sending… · 0 sent · loading · enabled');
    $loading = buttonShowcaseProps($screen->tree(), 'Send reminder');
    expect($loading['loading'])->toBeTrue();

    $screen->call('sendReminder');
    expect($bridge->callsTo('AsyncTask.Dispatch'))->toHaveCount(1);

    $id = $bridge->callsTo('AsyncTask.Dispatch')[0]['params']['id'];
    $screen->emitNative(AsyncTaskFinished::class, ['id' => $id])
        ->assertSet('sending', false)
        ->assertSet('sentCount', 1)
        ->assertSee('PHP accepted state: Reminder sent · 1 sent · idle · enabled');
    expect(buttonShowcaseProps($screen->tree(), 'Send reminder')['loading'])->toBeFalse();

    AsyncTaskTransport::forgetPayload($id);
});

it('toggles disabled on the same button from PHP', function () {
    $screen = Native::visit('/components/button');

    $screen->tap('Lock sending')->assertSet('sendingLocked', true);
    $locked = buttonShowcaseProps($screen->tree(), 'Send reminder');
    expect($locked['disabled'])->toBeTrue()
        ->and(buttonShowcaseProps($screen->tree(), 'Unlock sending')['on_press'] ?? null)->toBeInt();

    $screen->call('sendReminder')->assertSet('sending', false)->assertSet('sentCount', 0);

    $screen->tap('Unlock sending')->assertSet('sendingLocked', false);
    expect(buttonShowcaseProps($screen->tree(), 'Send reminder')['disabled'])->toBeFalse();
});

it('publishes an a11y-label that replaces the visible text', function () {
    $screen = Native::visit('/components/button');
    $pay = buttonShowcaseProps($screen->tree(), 'Pay');

    expect($pay['a11y_label'])->toBe('Pay invoice 1042, 45 dollars');

    $screen->tap('Pay')->assertSet('pressCount', 1);
    $screen->assertAccessible();
});

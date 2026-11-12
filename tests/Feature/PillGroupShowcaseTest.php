<?php

use Native\Mobile\Testing\Native;

/** @return array<string, array<string, mixed>> */
function firstlightPillGroupNodesByLabel(array $tree): array
{
    $nodes = [];

    if (($tree['type'] ?? null) === 'firstlight.pill-group') {
        $nodes[$tree['props']['label'] ?: $tree['props']['a11y_label']] = $tree;
    }

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...firstlightPillGroupNodesByLabel($child)];
    }

    return $nodes;
}

it('publishes the complete Pill Group catalogue through the native tree', function () {
    $screen = Native::visit('/components/pill-group');
    $nodes = firstlightPillGroupNodesByLabel($screen->tree());

    expect($screen->tree()['props']['nav_title'] ?? null)->toBe('Firstlight Pill Group')
        ->and(array_keys($nodes))->toBe([
            'Queue',
            'Large queue',
            'Mail filter',
            'Invoice status',
            'Queues',
            'Priority',
            'Locked queue',
            'Locked queues',
            'Required queues',
            'Archived queue',
            'No available queues',
            'Queue with error',
            'Disabled queue',
            'Workflow status',
        ])
        ->and($nodes['Queue']['props']['selected_values'])->toBe(['mine'])
        ->and($nodes['Queues']['props']['selected_values'])->toBe(['mine', 'urgent'])
        ->and($nodes['Queues']['props']['multiple'])->toBeTrue()
        ->and($nodes['Priority']['props']['value_type'])->toBe('integer')
        ->and($nodes['Priority']['props']['option_enabled'])->toBe(['1', '0', '1'])
        ->and($nodes['Queue with error']['props']['error'])->toBe('Choose at least one queue.')
        ->and($nodes['Disabled queue']['props']['disabled'])->toBeTrue()
        ->and($nodes['Mail filter']['props']['scrollable'])->toBeTrue()
        ->and($nodes['Mail filter']['props']['collapse_labels'])->toBeTrue()
        ->and($nodes['Mail filter']['props']['tone'])->toBe('success')
        ->and($nodes['Mail filter']['props']['option_icons'])->toHaveCount(6)
        ->and($nodes['Invoice status']['props']['collapse_labels'])->toBeFalse()
        ->and($nodes['Invoice status']['props']['tone'])->toBe('danger')
        ->and($nodes['Invoice status']['props']['selected_values'])->toBe(['overdue'])
        ->and($nodes['Queue']['props']['tone'])->toBe('primary');

    $screen->assertAccessible();
});

it('dispatches complete single and multiple proposals through PRESS callbacks', function () {
    $screen = Native::visit('/components/pill-group');

    $screen->press("__syncProperty('errorQueue', \"all\")")
        ->assertSet('errorQueue', 'all')
        ->assertSee('PHP accepted state: all')
        ->press("__syncProperty('workflowStatus', \"assignment\")")
        ->assertSet('workflowStatus', 'assignment')
        ->assertSee('PHP accepted state: assignment');

    $screen->press("__syncProperty('queue', \"all\")")
        ->assertSet('queue', 'all');

    $screen->press("__syncProperty('queues', [\"urgent\"])")
        ->assertSet('queues', ['urgent']);

    $screen->press("__syncProperty('mailFilter', \"starred\")")
        ->assertSet('mailFilter', 'starred')
        ->assertSee('PHP accepted state: starred');

    $nodes = firstlightPillGroupNodesByLabel($screen->tree());
    expect($nodes['Queue with error']['props']['selected_values'])->toBe(['all'])
        ->and($nodes['Workflow status']['props']['selected_values'])->toBe(['assignment']);
    $screen->press("__syncProperty('priority', null)")->assertSet('priority', null)
        ->tap('Reset selections')->assertSee('PHP accepted state: mine, urgent');
});

it('publishes the unchanged authoritative selection after rejection', function () {
    $screen = Native::visit('/components/pill-group');

    $screen->press('rejectQueue("all")')
        ->assertSet('rejectedQueue', 'mine')
        ->assertSet('rejectedAttempts', 1);

    $screen->assertSee('Rejected attempts: 1');

    expect(firstlightPillGroupNodesByLabel($screen->tree())['Locked queue']['props']['selected_values'])
        ->toBe(['mine']);
});

it('publishes the large size beside a large Button and clears it from PHP', function () {
    $screen = Native::visit('/components/pill-group');
    $nodes = firstlightPillGroupNodesByLabel($screen->tree());

    expect($nodes['Large queue']['props'])->toMatchArray([
        'size' => 'lg',
        'selected_values' => ['mine'],
        'multiple' => false,
    ])->and($nodes['Queue']['props']['size'])->toBe('md');

    $screen->assertElement('firstlight.button', fn (array $node): bool => ($node['props']['label'] ?? null) === 'Clear large queue'
        && ($node['props']['size'] ?? null) === 'lg'
    );

    $screen->press("__syncProperty('largeQueue', \"urgent\")")->assertSet('largeQueue', 'urgent')
        ->press('clearLargeQueue')->assertSet('largeQueue', null)
        ->assertSee('PHP accepted state: large (empty)');

    expect(firstlightPillGroupNodesByLabel($screen->tree())['Large queue']['props']['selected_values'])->toBe([]);
});

it('names the scrolling filter row only by a11y-label', function () {
    $filter = firstlightPillGroupNodesByLabel(Native::visit('/components/pill-group')->tree())['Mail filter'];

    expect($filter['props']['label'])->toBe('')
        ->and($filter['props']['a11y_label'])->toBe('Mail filter');
});

it('publishes the unchanged authoritative list after a multiple-mode rejection', function () {
    $screen = Native::visit('/components/pill-group');
    $locked = firstlightPillGroupNodesByLabel($screen->tree())['Locked queues'];

    expect($locked['props'])->toMatchArray([
        'multiple' => true,
        'selected_values' => ['mine'],
        'option_values' => ['mine', 'all', 'urgent'],
    ]);

    $screen->press('rejectQueues(["mine","all"])')
        ->assertSet('lockedQueues', ['mine'])
        ->assertSet('rejectedMultipleAttempts', 1)
        ->press('rejectQueues([])')
        ->assertSet('lockedQueues', ['mine'])
        ->assertSet('rejectedMultipleAttempts', 2)
        ->assertSee('Rejected multiple attempts: 2');

    expect(firstlightPillGroupNodesByLabel($screen->tree())['Locked queues']['props']['selected_values'])
        ->toBe(['mine']);
});

it('lets the final required pill clear and then binds the PHP validation error', function () {
    $screen = Native::visit('/components/pill-group');
    $required = firstlightPillGroupNodesByLabel($screen->tree())['Required queues'];

    expect($required['props'])->toMatchArray([
        'multiple' => true,
        'required' => true,
        'selected_values' => ['mine'],
        'error' => '',
    ]);

    $screen->press("__syncProperty('requiredQueues', [])")
        ->assertSet('requiredQueues', [])
        ->assertSee('PHP accepted state: required (empty)');

    expect(firstlightPillGroupNodesByLabel($screen->tree())['Required queues']['props'])->toMatchArray([
        'selected_values' => [],
        'error' => 'Choose at least one queue.',
    ]);

    $screen->press("__syncProperty('requiredQueues', [\"all\"])")->assertSet('requiredQueues', ['all']);

    expect(firstlightPillGroupNodesByLabel($screen->tree())['Required queues']['props']['error'])->toBe('');
});

it('leaves an absent value unselected and makes an empty option set inert', function () {
    $nodes = firstlightPillGroupNodesByLabel(Native::visit('/components/pill-group')->tree());

    expect($nodes['Archived queue']['props'])->toMatchArray([
        'value_type' => 'string',
        'selected_values' => ['archived'],
        'option_values' => ['mine', 'all', 'urgent'],
        'disabled' => false,
    ])->and($nodes['No available queues']['props'])->toMatchArray([
        'disabled' => true,
        'selected_values' => [],
        'option_values' => [],
        'option_callbacks' => [],
    ]);
});

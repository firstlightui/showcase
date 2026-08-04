<?php

use Native\Mobile\Testing\Native;

/** @return list<array<string, mixed>> */
function choiceGroupCaptureNodes(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.choice-group' ? [$tree] : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...choiceGroupCaptureNodes($child)];
    }

    return $nodes;
}

it('publishes the stable Choice Group documentation capture fixture', function () {
    $screen = Native::visit('/captures/choice-group');
    $nodes = choiceGroupCaptureNodes($screen->tree());

    expect($screen->tree()['props']['title'] ?? null)->toBe('Firstlight Choice Group')
        ->and($nodes)->toHaveCount(3)
        ->and(array_column(array_column($nodes, 'props'), 'label'))->toBe([
            'Priority',
            'Notifications',
            'Approval',
        ])
        ->and($nodes[0]['props']['selected_values'])->toBe(['urgent'])
        ->and($nodes[0]['props']['a11y_hint'])->toBe('Select one priority')
        ->and($nodes[0]['props']['required'])->toBeTrue()
        ->and($nodes[1]['props']['selected_values'])->toBe(['email', 'push'])
        ->and($nodes[1]['props']['multiple'])->toBeTrue()
        ->and($nodes[1]['props']['option_enabled'])->toBe(['1', '0', '1'])
        ->and($nodes[2]['props']['selected_values'])->toBe([])
        ->and($nodes[2]['props']['error'])->toBe('Choose an approval priority.');

    $screen->assertAccessible();
});

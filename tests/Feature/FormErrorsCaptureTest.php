<?php

use Native\Mobile\Testing\Native;

it('publishes the stable form-errors documentation capture fixture', function () {
    $screen = Native::visit('/captures/form-errors');
    $callouts = [];
    $walk = function (array $node) use (&$walk, &$callouts): void {
        if (($node['type'] ?? null) === 'firstlight.callout') {
            $callouts[] = $node['props'];
        }
        array_map($walk, $node['children'] ?? []);
    };
    $walk($screen->tree());

    expect($screen->tree()['props']['title'] ?? null)->toBe('Firstlight Form Errors')
        ->and($callouts)->toHaveCount(1)
        ->and($callouts[0])->toMatchArray([
            'tone' => 'danger',
            'message' => "There are 3 errors.\nThe name field is required.\nThe email must be a valid email address.\nThe terms must be accepted.",
        ]);

    $screen->assertAccessible();
});

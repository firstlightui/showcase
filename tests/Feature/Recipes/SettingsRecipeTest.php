<?php

use FirstlightUI\Testing\ScreenAssertions;
use Illuminate\Support\Facades\Cache;
use Native\Mobile\Testing\Native;

beforeEach(fn () => ScreenAssertions::register());

/** @return list<array<string, mixed>> */
function settingsRecipeSections(array $node): array
{
    $found = ($node['type'] ?? null) === 'list_section' ? [$node] : [];

    foreach ($node['children'] ?? [] as $child) {
        $found = [...$found, ...settingsRecipeSections($child)];
    }

    return $found;
}

it('renders grouped native sections with Firstlight controls inside', function () {
    $sections = settingsRecipeSections(Native::visit('/settings')->tree());

    expect(array_map(fn ($s) => $s['props']['header'] ?? null, $sections))
        ->toBe(['Notifications', 'Email', 'Account'])
        // Each row is a padded column so controls sit inside the grouped card's insets.
        ->and(array_map(fn ($c) => $c['type'], $sections[0]['children']))->toBe(['column', 'column'])
        ->and(array_map(fn ($c) => $c['children'][0]['type'], $sections[0]['children']))
        ->toBe(['firstlight.switch', 'firstlight.switch'])
        ->and($sections[1]['children'][0]['children'][0]['type'])->toBe('firstlight.select')
        ->and($sections[2]['children'][0]['children'][0]['type'])->toBe('firstlight.list-item');
});

it('saves each setting as soon as it changes and restores it on reopen', function () {
    Native::visit('/settings')
        ->set('notifications', false)
        ->set('digest', 'daily');

    expect(Cache::get('settings.notifications'))->toBeFalse()
        ->and(Cache::get('settings.digest'))->toBe('daily');

    Native::visit('/settings')
        ->assertSet('notifications', false)
        ->assertSet('marketing', false)
        ->assertSet('digest', 'daily');
});

it('opens the privacy screen from its row', function () {
    Native::visit('/settings')
        ->pressListItem('Privacy')
        ->assertNavigatedTo('/settings/privacy');
});

<?php

use App\Models\Contact;
use FirstlightUI\Testing\ScreenAssertions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Native\Mobile\Platform;
use Native\Mobile\Testing\Native;

uses(RefreshDatabase::class);

beforeEach(fn () => ScreenAssertions::register());


it('builds only the rows in the first window', function () {
    Native::visit('/contacts')
        ->assertListItemCount(80)
        ->assertListItem('Contact 0001')
        ->assertListItem('Contact 0080')
        ->assertMissingListItem('Contact 0081');
});

it('builds the new slice when the native window moves', function () {
    Native::visit('/contacts')
        ->call('setVirtualWindow', 4000, 4079)
        ->assertListItemCount(80)
        ->assertListItem('Contact 4001')
        ->assertListItem('Contact 4080')
        ->assertMissingListItem('Contact 0001');
});

it('clips the last window to the end of the collection', function () {
    Native::visit('/contacts')
        ->call('setVirtualWindow', 4960, 5039)
        ->assertListItemCount(40)
        ->assertListItem('Contact 5000');
});

it('opens the detail screen for the pressed row', function () {
    Native::visit('/contacts')
        ->call('setVirtualWindow', 2000, 2079)
        ->pressListItem('Contact 2042')
        ->assertNavigatedTo('/contacts/'.Contact::where('name', 'Contact 2042')->value('id'));
});

it('sizes placeholders to each platform\'s List Item row height', function (?string $platform, int $height) {
    $find = function (array $node) use (&$find): ?array {
        if (array_key_exists('estimated_row_height', $node['props'] ?? [])) {
            return $node;
        }
        foreach ($node['children'] ?? [] as $child) {
            if ($found = $find($child)) {
                return $found;
            }
        }

        return null;
    };

    expect($find(Native::visit('/contacts', platform: $platform)->tree())['props']['estimated_row_height'])->toEqual($height);
})->with([
    'iOS rows are 64 pt' => [Platform::IOS, 64],
    'Android rows are 72 dp' => [Platform::ANDROID, 72],
    'unknown platform falls back to Android' => [null, 72],
]);

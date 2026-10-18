<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Native\Mobile\Testing\Native;

/** @return array<string, array<string, mixed>> */
function mediaShowcaseFieldsByRef(array $tree): array
{
    $nodes = ($tree['type'] ?? null) === 'firstlight.media' && isset($tree['ref'])
        ? [$tree['ref'] => $tree]
        : [];

    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...mediaShowcaseFieldsByRef($child)];
    }

    return $nodes;
}

it('commits replaces and clears a picked document through its published callbacks', function () {
    Storage::fake('mobile_public');
    $first = UploadedFile::fake()->createWithContent('first.pdf', "%PDF-1.4\nFirst document");
    $second = UploadedFile::fake()->createWithContent('second.pdf', "%PDF-1.4\nReplacement document");
    $screen = Native::visit('/media');

    $screen->select('document', $first->getPathname());
    $path = $screen->get('document')->path;
    Storage::disk('mobile_public')->assertExists($path);
    $screen->assertSee('application/pdf');

    $screen->select('document', $second->getPathname());
    $replacement = $screen->get('document')->path;
    Storage::disk('mobile_public')->assertMissing($path);
    Storage::disk('mobile_public')->assertExists($replacement);

    // Native pickers can deliver the same source path again.
    $screen->select('document', $second->getPathname());
    $repeated = $screen->get('document')->path;
    Storage::disk('mobile_public')->assertMissing($replacement);
    Storage::disk('mobile_public')->assertExists($repeated);
    expect(Storage::disk('mobile_public')->get($repeated))->toBe("%PDF-1.4\nReplacement document");

    $screen->tap('Clear demo media');
    Storage::disk('mobile_public')->assertMissing($repeated);
    $screen->assertSet('document', null)->assertSee('No document selected');
});

it('validates actual selected media and resets the published errors', function () {
    Storage::fake('mobile_public');
    $image = UploadedFile::fake()->image('profile.png', 64, 48);
    $invalid = UploadedFile::fake()->createWithContent('notes.txt', 'Not a PDF document');
    $pdf = UploadedFile::fake()->createWithContent('contract.pdf', "%PDF-1.4\nSample document");
    $screen = Native::visit('/media')->tap('Validate media');
    expect(mediaShowcaseFieldsByRef($screen->tree())['avatar']['props']['error'])->not->toBe('');

    $screen->select('avatar', $image->getPathname())->select('document', $invalid->getPathname())
        ->tap('Validate media');
    $fields = mediaShowcaseFieldsByRef($screen->tree());
    expect($fields['avatar']['props']['error'])->toBe('')
        ->and($fields['document']['props']['error'])->not->toBe('')
        ->and($screen->get('avatar')->width)->toBe(64)
        ->and($screen->get('avatar')->height)->toBe(48);

    $screen->select('document', $pdf->getPathname())->tap('Validate media')
        ->assertSee('Media validation passed');
    expect(mediaShowcaseFieldsByRef($screen->tree())['document']['props']['error'])->toBe('');

    $screen->tap('Clear demo media')->assertSet('avatar', null)->assertSet('document', null);
    expect(mediaShowcaseFieldsByRef($screen->tree())['avatar']['props']['error'])->toBe('');
    expect(Storage::disk('mobile_public')->allFiles())->toBe([]);
});

it('applies the shared appearance when opening the Media screen', function () {
    Native::visit('/')->select('darkMode', true);

    Native::visit('/media')->assertSet('darkMode', true);
});

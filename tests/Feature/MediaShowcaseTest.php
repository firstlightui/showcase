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
    $screen = Native::visit('/components/media');

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
    $screen = Native::visit('/components/media')->tap('Validate media');
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

it('publishes a MediaValue from PHP and clears it to null without a pick', function () {
    Storage::fake('mobile_public');
    $screen = Native::visit('/components/media')->tap('Publish sample from PHP');

    $sample = $screen->get('sample');
    Storage::disk('mobile_public')->assertExists($sample->path);
    expect([$sample->disk, $sample->mime, $sample->width, $sample->height])->toBe(['mobile_public', 'image/png', 48, 48]);

    $fields = mediaShowcaseFieldsByRef($screen->tree());
    expect($fields['sample']['props'])->toMatchArray([
        'has_value' => true,
        'path' => $sample->path,
        'mime' => 'image/png',
        'width' => 48,
        'height' => 48,
    ])->and($fields['rejectedScan']['props'])->toMatchArray(['has_value' => true, 'path' => $sample->path]);
    $screen->assertSee('Sample: mobile_public · image/png');

    $screen->tap('Clear sample from PHP')->assertSet('sample', null);
    Storage::disk('mobile_public')->assertMissing($sample->path);
    expect(mediaShowcaseFieldsByRef($screen->tree())['sample']['props'])->toMatchArray(['has_value' => false, 'path' => '']);
});

it('keeps the previous value when PHP rejects a picked file', function () {
    Storage::fake('mobile_public');
    $pick = UploadedFile::fake()->image('replacement.png', 20, 20);
    $screen = Native::visit('/components/media')->tap('Publish sample from PHP');
    $approved = $screen->get('sample')->path;

    $screen->select('rejectedScan', $pick->getPathname())
        ->assertSet('rejectedScans', 1)
        ->assertSee('Rejected changes: 1');

    expect($screen->get('sample')->path)->toBe($approved)
        ->and(mediaShowcaseFieldsByRef($screen->tree())['rejectedScan']['props']['path'])->toBe($approved)
        ->and(Storage::disk('mobile_public')->allFiles())->toBe([$approved]);

    $screen->tap('Clear demo media');
});

it('commits a live-bound document to the non-default local disk', function () {
    Storage::fake('local');
    Storage::fake('mobile_public');
    $pdf = UploadedFile::fake()->createWithContent('scan.pdf', "%PDF-1.4\nArchive scan");
    $screen = Native::visit('/components/media');

    expect(mediaShowcaseFieldsByRef($screen->tree())['archiveScan']['props'])->toMatchArray([
        'mode' => 'document',
        'disk' => 'local',
        'directory' => 'showcase-archive',
    ]);

    $screen->select('archiveScan', $pdf->getPathname());
    $scan = $screen->get('archiveScan');
    expect($scan->disk)->toBe('local');
    Storage::disk('local')->assertExists($scan->path);
    expect(Storage::disk('mobile_public')->allFiles())->toBe([]);
    $screen->assertSee('Archive scan: local · application/pdf');

    $screen->tap('Clear demo media')->assertSet('archiveScan', null);
    Storage::disk('local')->assertMissing($scan->path);
});

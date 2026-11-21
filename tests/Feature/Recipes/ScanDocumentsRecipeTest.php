<?php

use App\NativeComponents\ScanScreen;
use FirstlightUI\LocalOcr\Events\CaptureCancelled;
use FirstlightUI\LocalOcr\Events\PagesCaptured;
use FirstlightUI\LocalOcr\Events\TextRecognized;
use Native\Mobile\Testing\Native;

function scanRecipeBridge()
{
    return Native::fakeBridge()
        ->respondTo('LocalOcr.GetCapabilities', ['contractVersion' => 1, 'capture' => ['available' => true], 'ocr' => ['available' => true]])
        ->respondTo('LocalOcr.Capture', ['status' => 'pending'])
        ->respondTo('LocalOcr.Recognize', ['status' => 'pending'])
        ->respondTo('LocalOcr.Discard', ['deleted' => 1]);
}

it('scans, reads the pages, shows their text and discards them', function () {
    $bridge = scanRecipeBridge();

    $screen = Native::visit('/scan')
        ->assertAccessible()
        ->assertNavTitle('Scan')
        ->assertSet('canScan', true)
        ->tap('Scan a document')
        ->assertSet('working', true);

    $bridge->assertCalled('LocalOcr.Capture');
    $capture = $screen->get('requestId');

    $screen->emitNative(PagesCaptured::class, ['id' => $capture, 'pages' => [['path' => '/tmp/local-ocr/a/page-1.jpg', 'width' => 10, 'height' => 10]]]);
    $bridge->assertCalled('LocalOcr.Recognize');
    $recognition = $screen->get('requestId');
    expect($recognition)->not->toBe($capture);

    $screen->emitNative(TextRecognized::class, ['id' => $recognition, 'result' => [
        'text' => 'MRN 1234567', 'pages' => [['path' => '/tmp/local-ocr/a/page-1.jpg']],
    ]])
        ->assertSet('text', 'MRN 1234567')
        ->assertSet('working', false)
        ->assertSee('MRN 1234567');

    $bridge->assertCalled('LocalOcr.Discard');
});

it('stops waiting when the scanner is closed', function () {
    scanRecipeBridge();

    $screen = Native::visit('/scan')->tap('Scan a document');

    $screen->emitNative(CaptureCancelled::class, ['id' => $screen->get('requestId')])
        ->assertSet('working', false)
        ->assertSet('requestId', null);
});

it('ignores a result for an earlier scan', function () {
    Native::test(ScanScreen::class)
        ->set('requestId', 'scan-2')
        ->emitNative(TextRecognized::class, ['id' => 'scan-1', 'result' => ['text' => 'Old page', 'pages' => []]])
        ->assertSet('text', '')
        ->assertDontSee('Old page');
});

it('keeps Scan disabled where the scanner is missing', function () {
    Native::visit('/scan')->assertSet('canScan', false);
});

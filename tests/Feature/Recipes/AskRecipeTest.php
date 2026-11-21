<?php

use App\NativeComponents\AskScreen;
use FirstlightUI\LocalAI\Events\GenerationCancelled;
use FirstlightUI\LocalAI\Events\GenerationCompleted;
use FirstlightUI\LocalAI\Events\TextDeltaGenerated;
use Native\Mobile\Testing\Native;

function askRecipeBridge(string $status = 'available')
{
    return Native::fakeBridge()
        ->respondTo('LocalAI.Availability', ['status' => $status, 'reason' => $status === 'available' ? '' : 'not_enabled', 'platform' => 'ios'])
        ->respondTo('LocalAI.Generate', ['status' => 'queued'])
        ->respondTo('LocalAI.Cancel', ['status' => 'cancelling']);
}

it('streams the answer, then shows the final text', function () {
    $bridge = askRecipeBridge();

    $screen = Native::visit('/ask')
        ->assertAccessible()
        ->assertNavTitle('Ask')
        ->assertSet('available', true)
        ->set('question', 'Name three moons of Jupiter.')
        ->call('ask');

    $bridge->assertCalled('LocalAI.Generate');
    $id = $screen->get('generationId');
    expect($id)->toBeString()->not->toBe('');

    $screen->assertSee('Stop')
        ->emitNative(TextDeltaGenerated::class, ['id' => $id, 'sequence' => 1, 'delta' => 'Io, '])
        ->assertSet('answer', 'Io, ')
        ->emitNative(GenerationCompleted::class, ['id' => $id, 'text' => 'Io, Europa and Ganymede.'])
        ->assertSet('answer', 'Io, Europa and Ganymede.')
        ->assertSet('generationId', null)
        ->assertSee('Io, Europa and Ganymede.');
});

it('keeps the partial answer when stopped', function () {
    $bridge = askRecipeBridge();

    $screen = Native::visit('/ask')->set('question', 'Tell a story.')->call('ask');
    $id = $screen->get('generationId');

    $screen->tap('Stop');
    $bridge->assertCalled('LocalAI.Cancel');

    $screen->emitNative(GenerationCancelled::class, ['id' => $id, 'text' => 'Once upon'])
        ->assertSet('answer', 'Once upon')
        ->assertSet('generationId', null);
});

it('ignores text from an earlier question', function () {
    Native::test(AskScreen::class)
        ->set('generationId', 'gen-2')
        ->emitNative(TextDeltaGenerated::class, ['id' => 'gen-1', 'sequence' => 1, 'delta' => 'Old'])
        ->assertSet('answer', '');
});

it('says why where the model cannot run', function () {
    askRecipeBridge('unavailable');

    Native::visit('/ask')->assertSet('available', false);
});

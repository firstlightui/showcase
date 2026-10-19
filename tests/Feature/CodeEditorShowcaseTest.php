<?php

use App\NativeComponents\CodeEditorShowcase;
use Native\Mobile\Testing\Native;

/** @return array<string, array<string, mixed>> */
function firstlightCodeEditorsByLabel(array $tree): array
{
    $nodes = [];
    if (($tree['type'] ?? null) === 'firstlight.code-editor') {
        $nodes[$tree['props']['label']] = $tree;
    }
    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...firstlightCodeEditorsByLabel($child)];
    }

    return $nodes;
}

it('publishes every documented Code Editor state through the native wire tree', function () {
    $screen = Native::visit('/code-editor');
    $tree = $screen->tree();
    $nodes = firstlightCodeEditorsByLabel($tree);

    expect($tree['props']['title'] ?? null)->toBe('Firstlight Code Editor')
        ->and(array_keys($nodes))->toBe([
            'Webhook payload',
            'Deploy workflow',
            'Environment',
            'No-wrap JSON',
            'Read-only environment',
            'Disabled editor',
            'Programmatic JSON',
            'Deployment log',
        ])
        ->and($nodes['Webhook payload']['props'])->toMatchArray([
            'language' => 'json', 'required' => true, 'error' => '', 'sync_mode' => 'live',
        ])
        ->and($nodes['Deploy workflow']['props'])->toMatchArray(['language' => 'yaml', 'sync_mode' => 'blur'])
        ->and($nodes['Environment']['props'])->toMatchArray([
            'language' => 'env', 'sync_mode' => 'debounce', 'debounce_ms' => 500, 'min_lines' => 4, 'max_lines' => 10,
        ])
        ->and($nodes['No-wrap JSON']['props'])->toMatchArray(['wrap' => false, 'indent' => 4])
        ->and($nodes['Read-only environment']['props'])->toMatchArray(['read_only' => true, 'line_numbers' => false])
        ->and($nodes['Disabled editor']['props']['disabled'])->toBeTrue()
        ->and($nodes['Deployment log']['props'])->toMatchArray([
            'read_only' => true, 'follow' => true, 'wrap' => false, 'value' => '',
        ]);

    $screen->assertAccessible();
});

it('binds edits validates JSON in PHP and applies programmatic replacements', function () {
    $screen = Native::visit('/code-editor')
        ->select('payload', '{"event": ')
        ->assertSet('payload', '{"event": ');

    expect(firstlightCodeEditorsByLabel($screen->tree())['Webhook payload']['props']['error'])
        ->toBe('Payload must be valid JSON.');

    $screen->select('payload', '{"event": "ok"}')
        ->select('workflow', "deploy:\n  branch: release")
        ->assertSet('workflow', "deploy:\n  branch: release")
        ->select('environment', 'APP_ENV=local')
        ->assertSet('environment', 'APP_ENV=local')
        ->tap('Replace JSON')
        ->assertSet('programmatic', "{\n  \"version\": 2,\n  \"replaced\": true\n}")
        ->tap('Reset JSON')
        ->assertSet('programmatic', "{\n  \"version\": 1\n}");

    $nodes = firstlightCodeEditorsByLabel($screen->tree());
    expect($nodes['Webhook payload']['props']['error'])->toBe('')
        ->and($nodes['Read-only environment']['props']['value'])->toBe('APP_ENV=local');
});

it('grows the live log and trims its head at the cap', function () {
    $screen = Native::visit('/code-editor')->tap('Append 25 lines');
    $log = firstlightCodeEditorsByLabel($screen->tree())['Deployment log']['props']['value'];
    expect(explode("\n", $log))->toHaveCount(25)
        ->and($log)->toStartWith('[00001] deploy: step 1 completed');

    $screen->tap('Fill 1,500 lines');
    $lines = explode("\n", firstlightCodeEditorsByLabel($screen->tree())['Deployment log']['props']['value']);
    expect($lines)->toHaveCount(CodeEditorShowcase::LOG_CAP)
        ->and($lines[0])->toBe('[00026] deploy: step 26 completed')
        ->and(end($lines))->toBe('[01525] deploy: step 1525 completed');

    $screen->tap('Clear log')->assertSet('logLines', 0);
});

<?php

namespace App\NativeComponents;

use Illuminate\View\View;
use Native\Mobile\Attributes\Poll;

class CodeEditorShowcase extends ShowcaseScreen
{
    /** NativePHP publishes at most 64 KB of props per element; 1,500 log lines stay well under it. */
    public const LOG_CAP = 1500;

    public string $payload = "{\n  \"event\": \"deployment.finished\",\n  \"site\": 42\n}";

    public string $workflow = "deploy:\n  branch: main\n  steps:\n    - composer install";

    public string $environment = "APP_ENV=production\nAPP_DEBUG=false";

    public string $wide = '{"a_very_long_key_that_does_not_wrap": "scroll horizontally to read the rest of this line"}';

    public string $programmatic = "{\n  \"version\": 1\n}";

    public string $log = '';

    public int $logLines = 0;

    public string $scratch = '';

    public string $releaseNotes = '';

    public string $focusedDraft = "{\n  \"status\": \"draft\"\n}";

    public ?int $focusedReplaceAt = null;

    public int $focusedReplacements = 0;

    public function scheduleFocusedReplacement(): void
    {
        $this->focusedReplaceAt = time() + 3;
    }

    #[Poll(1000)]
    public function applyFocusedReplacement(): void
    {
        if ($this->focusedReplaceAt === null || time() < $this->focusedReplaceAt) {
            return;
        }

        $this->focusedReplaceAt = null;
        $this->focusedReplacements++;
        $this->focusedDraft = "{\n  \"status\": \"published\",\n  \"revision\": {$this->focusedReplacements}\n}";
    }

    public function payloadError(): string
    {
        return json_validate($this->payload) ? '' : 'Payload must be valid JSON.';
    }

    public function replaceProgrammatic(): void
    {
        $this->programmatic = "{\n  \"version\": 2,\n  \"replaced\": true\n}";
    }

    public function resetProgrammatic(): void
    {
        $this->programmatic = "{\n  \"version\": 1\n}";
    }

    public function appendLogLines(int $count = 25): void
    {
        $lines = $this->log === '' ? [] : explode("\n", $this->log);
        for ($index = 0; $index < $count; $index++) {
            $this->logLines++;
            $lines[] = sprintf('[%05d] deploy: step %d completed', $this->logLines, $this->logLines);
        }

        $this->log = implode("\n", array_slice($lines, -self::LOG_CAP));
    }

    public function fillLog(): void
    {
        $this->appendLogLines(self::LOG_CAP);
    }

    public function clearLog(): void
    {
        $this->log = '';
        $this->logLines = 0;
    }

    public function navTitle(): string
    {
        return 'Firstlight Code Editor';
    }

    public function render(): View
    {
        return view('native.code-editor-showcase');
    }
}

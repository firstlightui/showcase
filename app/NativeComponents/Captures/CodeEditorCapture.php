<?php

namespace App\NativeComponents\Captures;

use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class CodeEditorCapture extends NativeComponent
{
    public string $payload = "{\n  \"event\": \"deployment.finished\",\n  \"site\": 42,\n  \"tags\": [\"production\", \"eu\"]\n}";

    public string $environment = "APP_ENV=production\nAPP_DEBUG=false\n# Mail\nMAIL_HOST=smtp.example.com";

    public string $workflow = "deploy:\n  branch: main\n  steps:\n    - composer install";

    public function navTitle(): string
    {
        return 'Firstlight Code Editor';
    }

    public function render(): View
    {
        return view('native.captures.code-editor');
    }
}

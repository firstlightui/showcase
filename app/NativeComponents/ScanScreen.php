<?php

namespace App\NativeComponents;

use FirstlightUI\LocalOcr\Events\CaptureCancelled;
use FirstlightUI\LocalOcr\Events\CaptureFailed;
use FirstlightUI\LocalOcr\Events\PagesCaptured;
use FirstlightUI\LocalOcr\Events\TextRecognitionFailed;
use FirstlightUI\LocalOcr\Events\TextRecognized;
use FirstlightUI\LocalOcr\Facades\LocalOcr;
use FirstlightUI\NativeComponent;
use Illuminate\View\View;
use Native\Mobile\Attributes\On;

class ScanScreen extends NativeComponent
{
    public bool $canScan = false;

    public bool $working = false;

    public string $text = '';

    public ?string $error = null;

    public ?string $requestId = null;

    public function mount(): void
    {
        // null off the device, so the button stays disabled in a browser.
        $this->canScan = LocalOcr::capabilities()['capture']['available'] ?? false;
    }

    public function navTitle(): string
    {
        return 'Scan';
    }

    public function scan(): void
    {
        $this->error = null;
        $this->working = true;
        $this->requestId = LocalOcr::capture()->getId();
    }

    #[On(PagesCaptured::class)]
    public function pagesCaptured(string $id, array $pages): void
    {
        if ($id !== $this->requestId) {
            return;
        }

        $this->requestId = LocalOcr::recognize($pages)->getId();
    }

    #[On(TextRecognized::class)]
    public function textRecognized(string $id, array $result): void
    {
        if ($id !== $this->requestId) {
            return;
        }

        $this->text = $result['text'];
        $this->finish();

        LocalOcr::discard(array_column($result['pages'], 'path'));
    }

    #[On(CaptureCancelled::class)]
    public function captureCancelled(string $id): void
    {
        if ($id === $this->requestId) {
            $this->finish();
        }
    }

    #[On(CaptureFailed::class)]
    #[On(TextRecognitionFailed::class)]
    public function failed(string $id, string $code, string $message): void
    {
        if ($id === $this->requestId) {
            $this->error = "Couldn't read the document. {$message}";
            $this->finish();
        }
    }

    public function render(): View
    {
        return view('native.scan');
    }

    private function finish(): void
    {
        $this->working = false;
        $this->requestId = null;
    }
}

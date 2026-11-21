<?php

namespace App\NativeComponents;

use FirstlightUI\LocalAI\Events\GenerationCancelled;
use FirstlightUI\LocalAI\Events\GenerationCompleted;
use FirstlightUI\LocalAI\Events\GenerationFailed;
use FirstlightUI\LocalAI\Events\TextDeltaGenerated;
use FirstlightUI\LocalAI\Facades\LocalAI;
use FirstlightUI\LocalAI\LocalAIException;
use FirstlightUI\NativeComponent;
use Illuminate\View\View;
use Native\Mobile\Attributes\On;

class AskScreen extends NativeComponent
{
    public bool $available = false;

    public string $question = '';

    public string $answer = '';

    public ?string $generationId = null;

    public ?string $error = null;

    public function mount(): void
    {
        try {
            $this->available = LocalAI::availability()->isAvailable();
        } catch (LocalAIException) {
            // Off the device, or the plugin isn't registered.
            $this->available = false;
        }
    }

    public function navTitle(): string
    {
        return 'Ask';
    }

    public function ask(): void
    {
        $this->answer = '';
        $this->error = null;

        try {
            $this->generationId = LocalAI::generate(
                $this->question,
                instructions: 'Answer in two or three sentences.',
                options: ['maxTokens' => 256],
            )->id;
        } catch (LocalAIException $exception) {
            $this->error = $exception->getMessage();
        }
    }

    public function cancel(): void
    {
        if ($this->generationId !== null) {
            LocalAI::cancel($this->generationId);
        }
    }

    #[On(TextDeltaGenerated::class)]
    public function delta(string $id, int $sequence, string $delta): void
    {
        if ($id === $this->generationId) {
            $this->answer .= $delta;
        }
    }

    #[On(GenerationCompleted::class)]
    public function completed(string $id, string $text): void
    {
        if ($id === $this->generationId) {
            // The authoritative text replaces the streamed deltas.
            $this->answer = $text;
            $this->generationId = null;
        }
    }

    #[On(GenerationFailed::class)]
    public function failed(string $id, string $code, string $message, string $text = ''): void
    {
        if ($id === $this->generationId) {
            $this->answer = $text;
            $this->error = $code === 'guardrail'
                ? "That question can't be answered here."
                : "Couldn't finish the answer. {$message}";
            $this->generationId = null;
        }
    }

    #[On(GenerationCancelled::class)]
    public function cancelled(string $id, string $text = ''): void
    {
        if ($id === $this->generationId) {
            $this->answer = $text;
            $this->generationId = null;
        }
    }

    public function render(): View
    {
        return view('native.ask');
    }
}

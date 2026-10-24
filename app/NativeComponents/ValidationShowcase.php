<?php

namespace App\NativeComponents;

use FirstlightUI\Concerns\ValidatesFields;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ValidationShowcase extends ShowcaseScreen
{
    use ValidatesFields;

    public string $email = '';

    public string $name = '';

    public bool $accepted = false;

    public ?string $saved = null;

    public int $saveCount = 0;

    /** @var array<string, string> */
    protected array $rules = [
        // Listed in screen order: the error summary follows rule order.
        'name' => 'required|min:2',
        'email' => 'required|email',
        'accepted' => 'accepted',
    ];

    public function updatedEmail(): void
    {
        $this->validateOnly('email');
    }

    public function save(): void
    {
        $this->saved = null;
        $this->submit(
            action: function (): void {
                // A server-side check that belongs to the form, not one field.
                if (strcasecmp($this->email, 'taken@example.com') === 0) {
                    throw ValidationException::withMessages([
                        'form' => 'An account already exists for this email. Sign in instead.',
                    ]);
                }

                $this->saveCount++;
                $this->saved = 'Thanks, '.$this->name.'.';
            },
            successMessage: 'Sample form accepted',
        );
    }

    public function resetForm(): void
    {
        $this->email = '';
        $this->name = '';
        $this->accepted = false;
        $this->saved = null;
        $this->saveCount = 0;
        $this->resetValidation();
    }

    public function navTitle(): string
    {
        return 'Firstlight Validation';
    }

    public function render(): View
    {
        return view('native.validation-showcase');
    }
}

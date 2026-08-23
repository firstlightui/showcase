<?php

namespace App\NativeComponents;

use FirstlightUI\Concerns\ValidatesFields;
use Illuminate\View\View;

class ValidationShowcase extends ShowcaseScreen
{
    use ValidatesFields;

    public string $email = '';

    public string $name = '';

    public bool $accepted = false;

    public ?string $saved = null;

    /** @var array<string, string> */
    protected array $rules = [
        'email' => 'required|email',
        'name' => 'required|min:2',
        'accepted' => 'accepted',
    ];

    public function updatedEmail(): void
    {
        $this->validateOnly('email');
    }

    public function save(): void
    {
        $this->validate();
        $this->saved = 'Thanks, '.$this->name.'.';
    }

    public function resetForm(): void
    {
        $this->email = '';
        $this->name = '';
        $this->accepted = false;
        $this->saved = null;
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

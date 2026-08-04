<?php

namespace App\NativeComponents;

use Illuminate\View\View;

class SearchFieldShowcase extends ShowcaseScreen
{
    public string $query = 'Referral';

    public string $debouncedQuery = '';

    public string $blurQuery = 'Cardiology';

    public string $lastSubmitted = '';

    public int $submitCount = 0;

    public function submitQuery(string $query = ''): void
    {
        $this->lastSubmitted = $query;
        $this->submitCount++;
    }

    public function resetQuery(): void
    {
        $this->query = 'Referral';
    }

    public function navTitle(): string
    {
        return 'Firstlight Search Field';
    }

    public function render(): View
    {
        return view('native.search-field-showcase');
    }
}

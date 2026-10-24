<?php

namespace App\NativeComponents;

use App\Support\DocumentationLibrary;
use Illuminate\View\View;

class DocumentationScreen extends ShowcaseScreen
{
    public string $query = '';

    public function openArticle(string $id): void
    {
        $library = app(DocumentationLibrary::class);
        if ($library->find($id)) {
            $this->navigate($library->url($id));
        }
    }

    public function clearSearch(): void
    {
        $this->query = '';
    }

    public function showsBackButton(): bool
    {
        return false;
    }

    public function navTitle(): string
    {
        return 'Documentation';
    }

    public function render(): View
    {
        $library = app(DocumentationLibrary::class);
        $articles = $library->articles($this->query);

        return view('native.documentation', [
            'groups' => trim($this->query) === '' || $articles === []
                ? collect($articles)->groupBy('group')
                : collect(['Search results' => $articles]),
            'count' => count($articles),
            'version' => $library->manifest()['version'],
        ]);
    }
}

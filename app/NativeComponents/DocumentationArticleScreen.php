<?php

namespace App\NativeComponents;

use App\Support\DocumentationLibrary;
use Illuminate\View\View;
use Native\Mobile\Facades\Browser;

class DocumentationArticleScreen extends ShowcaseScreen
{
    public string $articleId = '';

    public string $chapter = 'overview';

    public function mount(string $group, string $slug): void
    {
        $this->articleId = $group === 'overview' && $slug === 'index' ? 'index' : $group.'/'.$slug;
        $article = app(DocumentationLibrary::class)->read($this->articleId);
        parse_str(parse_url($this->nativeRouter?->currentUri() ?? '', PHP_URL_QUERY) ?? '', $query);
        $requested = $query['heading'] ?? request()->query('heading', 'overview');
        $requested = $article['anchors'][$requested] ?? $requested;
        $this->chapter = isset($article['chapters'][$requested]) ? $requested : array_key_first($article['chapters']);
    }

    public function tryExample(): void
    {
        if ($path = app(DocumentationLibrary::class)->example($this->articleId)) {
            $this->navigate($path);
        }
    }

    public function openLink(int $index): void
    {
        $library = app(DocumentationLibrary::class);
        $article = $library->read($this->articleId);
        $link = $article['chapters'][$this->chapter]['links'][$index] ?? null;
        if ($link === null) {
            return;
        }
        if ($url = $library->resolve($this->articleId, $link['url'])) {
            $this->navigate($url);
        } elseif (filter_var($link['url'], FILTER_VALIDATE_URL) && parse_url($link['url'], PHP_URL_SCHEME) === 'https') {
            Browser::open($link['url']);
        }
    }

    public function navTitle(): string
    {
        return app(DocumentationLibrary::class)->find($this->articleId)['title'] ?? 'Documentation';
    }

    public function render(): View
    {
        $library = app(DocumentationLibrary::class);
        $article = $library->read($this->articleId);
        $chapter = $article['chapters'][$this->chapter] ?? reset($article['chapters']);

        return view('native.documentation-article', [
            'article' => $article,
            'chapterContent' => $chapter,
            'chapters' => array_map(fn (array $section): string => $section['title'], $article['chapters']),
            'example' => $library->example($this->articleId),
        ]);
    }
}

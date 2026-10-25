<?php

namespace App\NativeComponents;

use App\Support\DocumentationLibrary;
use Illuminate\View\View;
use Native\Mobile\Facades\Browser;

class DocumentationArticleScreen extends ShowcaseScreen
{
    /**
     * Rough reading weight of one page. Consecutive short sections share a
     * page so a reader is not paging through one-paragraph screens.
     */
    private const PAGE_WEIGHT = 700;

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

    public function showSection(string $chapter): void
    {
        $this->chapter = $chapter;
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
        $link = $library->read($this->articleId)['links'][$index] ?? null;
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
        return app(DocumentationLibrary::class)->find($this->articleId)['title'] ?? 'Docs';
    }

    public function render(): View
    {
        $library = app(DocumentationLibrary::class);
        $article = $library->read($this->articleId);
        $pages = $this->pages($article['chapters']);
        $current = 0;
        foreach ($pages as $index => $keys) {
            if (in_array($this->chapter, $keys, true)) {
                $current = $index;
            }
        }

        return view('native.documentation-article', [
            'article' => $article,
            'sections' => array_map(fn (string $key): array => [
                'title' => $article['chapters'][$key]['title'],
                'blocks' => $this->galleries($article['chapters'][$key]['blocks']),
            ], $pages[$current]),
            'page' => $current,
            'previous' => $pages[$current - 1][0] ?? null,
            'next' => $pages[$current + 1][0] ?? null,
            'chapters' => array_map(fn (array $section): string => $section['title'], $article['chapters']),
            'example' => $current === 0 ? $library->example($this->articleId) : null,
        ]);
    }

    /** @return list<list<string>> chapter keys grouped into pages */
    private function pages(array $chapters): array
    {
        $pages = [];
        $weight = PHP_INT_MAX;
        foreach ($chapters as $key => $chapter) {
            if ($weight >= self::PAGE_WEIGHT) {
                $pages[] = [];
                $weight = 0;
            }
            $pages[array_key_last($pages)][] = $key;
            foreach ($chapter['blocks'] as $block) {
                $weight += match ($block['type']) {
                    'code' => 400,
                    'image' => 600,
                    'definition' => 120,
                    default => mb_strlen($block['text'] ?? implode('', array_column($block['runs'] ?? [], 'text'))),
                };
            }
        }

        return $pages;
    }

    /** Pair consecutive screenshots so light and dark sit side by side. */
    private function galleries(array $blocks): array
    {
        $result = [];
        foreach ($blocks as $block) {
            $last = array_key_last($result);
            if ($block['type'] === 'image' && $last !== null && $result[$last]['type'] === 'gallery' && count(end($result[$last]['rows'])) < 2) {
                $result[$last]['rows'][array_key_last($result[$last]['rows'])][] = $block;
            } elseif ($block['type'] === 'image' && $last !== null && $result[$last]['type'] === 'gallery') {
                $result[$last]['rows'][] = [$block];
            } elseif ($block['type'] === 'image') {
                $result[] = ['type' => 'gallery', 'rows' => [[$block]]];
            } else {
                $result[] = $block;
            }
        }

        return $result;
    }
}

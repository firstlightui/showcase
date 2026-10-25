<?php

namespace App\Support;

use Illuminate\Support\Str;

class DocumentationLibrary
{
    public const GROUPS = [
        'index' => 'Overview',
        'getting-started' => 'Getting started',
        'components' => 'Components',
        'how-to' => 'Guides',
        'concepts' => 'Concepts',
        'reference' => 'Reference',
    ];

    private ?array $manifest = null;

    public function manifest(): array
    {
        return $this->manifest ??= json_decode(
            file_get_contents(resource_path('documentation/catalog.json')),
            true, flags: JSON_THROW_ON_ERROR,
        );
    }

    public function articles(string $query = ''): array
    {
        $terms = preg_split('/\s+/u', mb_strtolower(trim($query)), flags: PREG_SPLIT_NO_EMPTY);

        $articles = array_values(array_filter($this->manifest()['articles'], function (array $article) use ($terms): bool {
            foreach ($terms as $term) {
                if (! str_contains($article['search'], $term)) {
                    return false;
                }
            }

            return true;
        }));

        if ($terms !== []) {
            $phrase = implode(' ', $terms);
            $rank = function (array $article) use ($phrase): int {
                $title = mb_strtolower($article['title']);

                return match (true) {
                    $title === $phrase => 0,
                    str_starts_with($title, $phrase) => 1,
                    str_contains($title, $phrase) => 2,
                    str_contains(mb_strtolower($article['description']), $phrase) => 3,
                    default => 4,
                };
            };
            usort($articles, fn (array $a, array $b): int => ($rank($a) <=> $rank($b)) ?: strnatcasecmp($a['title'], $b['title']));
        }

        return $articles;
    }

    public function find(string $id): ?array
    {
        foreach ($this->manifest()['articles'] as $article) {
            if ($article['id'] === $id) {
                return $article;
            }
        }

        return null;
    }

    public function read(string $id): array
    {
        abort_unless($this->find($id), 404);

        return json_decode(file_get_contents(resource_path('documentation/articles/'.$id.'.json')), true, flags: JSON_THROW_ON_ERROR);
    }

    public function url(string $id, string $heading = ''): string
    {
        return '/docs/'.($id === 'index' ? 'overview/index' : $id)
            .($heading === '' ? '' : '?heading='.rawurlencode($heading));
    }

    /** Resolve only known bundled documents; arbitrary local files are never read. */
    public function resolve(string $current, string $href): ?string
    {
        if (parse_url($href, PHP_URL_SCHEME) !== null || str_starts_with($href, '//')) {
            return null;
        }

        [$path, $anchor] = array_pad(explode('#', $href, 2), 2, '');
        $parts = [];
        foreach (explode('/', dirname($current).'/'.$path) as $part) {
            if ($part === '.' || $part === '') {
                continue;
            }
            if ($part === '..') {
                if ($parts === []) {
                    return null;
                }
                array_pop($parts);
            } else {
                $parts[] = $part;
            }
        }
        $id = $path === '' ? $current : preg_replace('/\.md$/', '', implode('/', $parts));

        return $this->find($id) ? $this->url($id, rawurldecode($anchor)) : null;
    }

    public function example(string $id): ?string
    {
        if (str_starts_with($id, 'components/')) {
            $slug = Str::after($id, 'components/');

            return '/components/'.($slug === 'list-section' ? 'list' : $slug);
        }

        return match ($id) {
            'getting-started/first-component' => '/components/segmented',
            'how-to/build-a-settings-screen' => '/settings',
            'how-to/build-a-form-screen' => '/posts/create',
            'how-to/build-a-list-detail-flow', 'how-to/build-a-searchable-list-screen' => '/posts',
            'how-to/build-a-large-list-screen' => '/contacts',
            'how-to/validate-fields', 'how-to/submit-forms', 'reference/validates-fields' => '/components/validation',
            'how-to/paginate-lists', 'how-to/destroy-list-items' => '/components/list',
            default => null,
        };
    }
}

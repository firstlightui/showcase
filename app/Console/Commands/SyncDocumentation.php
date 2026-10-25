<?php

namespace App\Console\Commands;

use App\Support\DocumentationLibrary;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\CommonMark\Node\Block\Heading;
use League\CommonMark\Extension\CommonMark\Node\Block\IndentedCode;
use League\CommonMark\Extension\CommonMark\Node\Block\ListBlock;
use League\CommonMark\Extension\CommonMark\Node\Inline\Code;
use League\CommonMark\Extension\CommonMark\Node\Inline\Emphasis;
use League\CommonMark\Extension\CommonMark\Node\Inline\HtmlInline;
use League\CommonMark\Extension\CommonMark\Node\Inline\Image;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Extension\CommonMark\Node\Inline\Strong;
use League\CommonMark\Extension\Table\Table;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\Node\Block\Paragraph;
use League\CommonMark\Node\Inline\Newline;
use League\CommonMark\Node\Node;
use League\CommonMark\Node\StringContainerInterface;
use League\CommonMark\Parser\MarkdownParser;
use RuntimeException;
use Symfony\Component\Process\Process;

class SyncDocumentation extends Command
{
    protected $signature = 'documentation:sync {--source= : Local Firstlight repository; defaults to ../nativephp}';

    protected $description = 'Bundle offline documentation from the exact Firstlight revision in composer.lock';

    private string $source;

    private string $revision;

    private array $paths = [];

    /** Links of the article being bundled; inline runs refer to them by index. */
    private array $links = [];

    public function handle(): int
    {
        $this->source = $this->option('source') ?: base_path('../nativephp');
        $lock = json_decode(file_get_contents(base_path('composer.lock')), true, flags: JSON_THROW_ON_ERROR);
        $package = collect($lock['packages'])->firstWhere('name', 'firstlightui/nativephp');
        $this->revision = $package['dist']['reference'];
        $paths = $this->paths = explode("\0", rtrim($this->git(['ls-tree', '-r', '-z', '--name-only', $this->revision, 'docs']), "\0"));
        $environment = new Environment(['html_input' => 'strip', 'allow_unsafe_links' => false]);
        $environment->addExtension(new CommonMarkCoreExtension);
        $environment->addExtension(new TableExtension);
        $parser = new MarkdownParser($environment);
        $articles = [];

        foreach ($paths as $path) {
            if (! preg_match('#^docs/(?:[a-z0-9-]+/)*[a-z0-9-]+\.md$#', $path)) {
                continue;
            }
            $raw = $this->git(['show', $this->revision.':'.$path]);
            preg_match('/\A---\R(.*?)\R---\R/s', $raw, $front);
            $body = isset($front[0]) ? substr($raw, strlen($front[0])) : $raw;
            preg_match('/^title: (.+)$/m', $front[1] ?? '', $title);
            preg_match('/^description: (.+)$/m', $front[1] ?? '', $description);
            if (empty($title[1]) || empty($description[1]) || in_array($description[1], ['|', '>'], true)) {
                throw new RuntimeException('Expected a scalar title and description in '.$path);
            }
            $id = substr($path, 5, -3);
            $group = DocumentationLibrary::GROUPS[explode('/', $id)[0]] ?? throw new RuntimeException('Unknown documentation group: '.$id);
            $article = [
                'id' => $id,
                'title' => trim($title[1], "\"'"),
                'description' => trim($description[1], "\"'"),
                'group' => $group,
                'anchors' => [],
                'chapters' => ['overview' => ['title' => 'Overview', 'blocks' => []]],
                'links' => [],
            ];
            $this->links = [];
            $chapter = 'overview';
            foreach ($parser->parse($body)->children() as $node) {
                if ($node instanceof Heading && $node->getLevel() === 1) {
                    $article['anchors'][Str::slug($this->text($node))] = 'overview';

                    continue;
                }
                if ($node instanceof Heading && $node->getLevel() === 2) {
                    $label = $this->text($node);
                    $chapter = Str::slug($label);
                    $suffix = 2;
                    $base = $chapter;
                    while (isset($article['chapters'][$chapter])) {
                        $chapter = $base.'-'.$suffix++;
                    }
                    $article['chapters'][$chapter] = ['title' => $label, 'blocks' => []];
                } else {
                    $article['chapters'][$chapter]['blocks'] = [
                        ...$article['chapters'][$chapter]['blocks'], ...$this->blocks($node, $id),
                    ];
                }
                if ($node instanceof Heading) {
                    $article['anchors'][Str::slug($this->text($node))] = $chapter;
                }
            }
            $article['links'] = $this->links;
            $destination = resource_path('documentation/articles/'.$id.'.json');
            File::ensureDirectoryExists(dirname($destination));
            File::put($destination, json_encode($article, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR)."\n");
            unset($article['chapters'], $article['anchors'], $article['links']);
            $article['search'] = mb_strtolower($article['title'].' '.$article['description'].' '.$body);
            $articles[] = $article;
        }

        foreach ($paths as $path) {
            if (preg_match('#^docs/screenshots/[a-z0-9-]+/[a-z0-9-]+\.png$#', $path)) {
                $target = public_path('documentation/'.substr($path, 5));
                File::ensureDirectoryExists(dirname($target));
                $png = $this->git(['show', $this->revision.':'.$path]);
                $origin = 'impeccable:prompt'."\0".'Pre-existing native simulator screenshot. Source: https://github.com/firstlightui/nativephp/blob/'.$this->revision.'/'.$path.' (MIT). Pixels preserved.';
                $chunk = 'tEXt'.$origin;
                // Add source provenance before IEND without decoding or changing image pixels.
                $metadata = pack('N', strlen($origin)).$chunk.pack('N', crc32($chunk));
                File::put($target, substr($png, 0, -12).$metadata.substr($png, -12));
            }
        }
        $groups = array_values(DocumentationLibrary::GROUPS);
        usort($articles, fn (array $a, array $b): int => (array_search($a['group'], $groups) <=> array_search($b['group'], $groups)) ?: strnatcasecmp($a['title'], $b['title']));
        $version = trim($this->git(['describe', '--tags', '--always', $this->revision]));
        File::put(resource_path('documentation/catalog.json'), json_encode([
            'version' => $version,
            'revision' => $this->revision,
            'source' => 'https://github.com/firstlightui/nativephp/tree/'.$this->revision.'/docs',
            'license' => 'MIT',
            'articles' => $articles,
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR)."\n");
        $this->info('Bundled '.count($articles).' articles and their screenshots from '.$version.' ('.$this->revision.').');

        return self::SUCCESS;
    }

    private function git(array $arguments): string
    {
        return (new Process(['git', ...$arguments], $this->source))->mustRun()->getOutput();
    }

    private function text(Node $node): string
    {
        if ($node instanceof Image) {
            return '';
        }
        if ($node instanceof Newline) {
            // Source Markdown is hard-wrapped; only an explicit hard break ends a line.
            return $node->getType() === Newline::SOFTBREAK ? ' ' : "\n";
        }
        if ($node instanceof StringContainerInterface) {
            return $node->getLiteral();
        }

        return implode('', array_map(fn (Node $child): string => $this->text($child), iterator_to_array($node->children())));
    }

    /**
     * Plain text plus, when the text carries inline code, emphasis or links,
     * ordered runs the reader composes into one wrapping native text.
     */
    private function inline(Node $node): array
    {
        $runs = [];
        $this->runs($node, [], $runs);
        $merged = [];
        foreach ($runs as $run) {
            $last = array_key_last($merged);
            if ($last !== null && array_diff_key($merged[$last], ['text' => 0]) == array_diff_key($run, ['text' => 0])) {
                $merged[$last]['text'] .= $run['text'];
            } else {
                $merged[] = $run;
            }
        }
        if ($merged !== []) {
            $merged[0]['text'] = ltrim($merged[0]['text']);
            $merged[array_key_last($merged)]['text'] = rtrim($merged[array_key_last($merged)]['text']);
            $merged = array_values(array_filter($merged, fn (array $run): bool => $run['text'] !== ''));
        }

        // Styled text ships only as runs; plain text stays a single string.
        return array_filter($merged, fn (array $run): bool => count($run) > 1) === []
            ? ['text' => implode('', array_column($merged, 'text'))]
            : ['runs' => $merged];
    }

    private function runs(Node $node, array $style, array &$runs): void
    {
        foreach ($node->children() as $child) {
            if ($child instanceof Image || $child instanceof HtmlInline) {
                continue;
            }
            if ($child instanceof Code) {
                $runs[] = ['text' => $child->getLiteral(), ...$style, 'code' => true];
            } elseif ($child instanceof Newline || $child instanceof StringContainerInterface) {
                $runs[] = ['text' => $this->text($child), ...$style];
            } elseif ($child instanceof Link) {
                $url = $child->getUrl();
                $scheme = parse_url($url, PHP_URL_SCHEME);
                $linkStyle = $style;
                if ($scheme === null || $scheme === 'https') {
                    $link = ['label' => trim($this->text($child)), 'url' => $url];
                    $index = array_search($link, $this->links, true);
                    if ($index === false) {
                        $index = count($this->links);
                        $this->links[] = $link;
                    }
                    $linkStyle['link'] = $index;
                }
                $this->runs($child, $linkStyle, $runs);
            } elseif ($child instanceof Strong) {
                $this->runs($child, [...$style, 'strong' => true], $runs);
            } elseif ($child instanceof Emphasis) {
                $this->runs($child, [...$style, 'emphasis' => true], $runs);
            } else {
                $this->runs($child, $style, $runs);
            }
        }
    }

    private function images(Node $node, string $id): array
    {
        if ($node instanceof Image) {
            $parts = [];
            foreach (explode('/', 'docs/'.dirname($id).'/'.$node->getUrl()) as $part) {
                if ($part === '.' || $part === '') {
                    continue;
                }
                if ($part === '..') {
                    array_pop($parts);
                } else {
                    $parts[] = $part;
                }
            }
            $path = implode('/', $parts);
            if (! str_starts_with($path, 'docs/screenshots/') || ! in_array($path, $this->paths, true)) {
                throw new RuntimeException('Unbundled image: '.$node->getUrl());
            }
            $relative = substr($path, strlen('docs/screenshots/'));
            $alt = implode('', array_map(fn (Node $child): string => $this->text($child), iterator_to_array($node->children())));

            return [['type' => 'image', 'src' => 'documentation/screenshots/'.$relative, 'text' => $alt]];
        }
        $images = [];
        foreach ($node->children() as $child) {
            $images = [...$images, ...$this->images($child, $id)];
        }

        return $images;
    }

    private function blocks(Node $node, string $id, int $depth = 0): array
    {
        if ($node instanceof FencedCode || $node instanceof IndentedCode) {
            return [['type' => 'code', 'language' => $node instanceof FencedCode ? $node->getInfo() : '', 'text' => rtrim($node->getLiteral(), "\n")]];
        }
        if ($node instanceof Table) {
            $rows = [];
            foreach ($node->children() as $section) {
                foreach ($section->children() as $row) {
                    $rows[] = iterator_to_array($row->children());
                }
            }
            $headers = array_map(fn (Node $cell): string => trim($this->text($cell)), array_shift($rows) ?? []);
            $blocks = [];
            foreach ($rows as $row) {
                $term = isset($row[0]) ? $this->inline($row[0]) : ['text' => ''];
                $hasTerm = ($term['text'] ?? '') !== '' || isset($term['runs']);
                $items = [];
                foreach (array_slice($row, 1, preserve_keys: true) as $index => $cell) {
                    $value = $this->inline($cell);
                    if (($value['text'] ?? '') !== '' || isset($value['runs'])) {
                        $items[] = ['label' => $headers[$index] ?? '', ...$value];
                    }
                }
                if ($hasTerm && $items !== []) {
                    $blocks[] = ['type' => 'definition', 'term' => $term, 'items' => $items];
                }
            }

            return [...$blocks, ...$this->images($node, $id)];
        }
        if ($node instanceof ListBlock) {
            $blocks = [];
            $number = $node->getListData()->start ?? 1;
            $ordered = $node->getListData()->type === ListBlock::TYPE_ORDERED;
            foreach ($node->children() as $item) {
                $first = true;
                foreach ($item->children() as $child) {
                    if ($first && $child instanceof Paragraph) {
                        $blocks[] = ['type' => 'item', 'marker' => $ordered ? $number.'.' : '•', 'depth' => $depth, ...$this->inline($child)];
                        $blocks = [...$blocks, ...$this->images($child, $id)];
                    } else {
                        $blocks = [...$blocks, ...$this->blocks($child, $id, $depth + 1)];
                    }
                    $first = false;
                }
                $number++;
            }

            return $blocks;
        }
        if ($node instanceof Heading || $node instanceof Paragraph) {
            $inline = $this->inline($node);

            return [
                ...(($inline['text'] ?? '') === '' && ! isset($inline['runs']) ? [] : [['type' => $node instanceof Heading ? 'heading' : 'text', ...$inline]]),
                ...$this->images($node, $id),
            ];
        }
        $blocks = [];
        foreach ($node->children() as $child) {
            $blocks = [...$blocks, ...$this->blocks($child, $id, $depth)];
        }
        if ($blocks === [] && $node instanceof StringContainerInterface && trim(strip_tags($node->getLiteral())) !== '') {
            $blocks[] = ['type' => 'text', 'text' => trim(strip_tags($node->getLiteral()))];
        }

        return $blocks;
    }
}

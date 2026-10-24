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
use League\CommonMark\Extension\CommonMark\Node\Inline\Image;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
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
                'chapters' => ['overview' => ['title' => 'Overview', 'blocks' => [], 'links' => []]],
            ];
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
                    $article['chapters'][$chapter] = ['title' => $label, 'blocks' => [], 'links' => []];
                } else {
                    $article['chapters'][$chapter]['blocks'] = [
                        ...$article['chapters'][$chapter]['blocks'], ...$this->blocks($node, $id),
                    ];
                }
                if ($node instanceof Heading) {
                    $article['anchors'][Str::slug($this->text($node))] = $chapter;
                }
                $this->links($node, $article['chapters'][$chapter]['links']);
            }
            foreach ($article['chapters'] as &$section) {
                $section['links'] = array_values(array_unique($section['links'], SORT_REGULAR));
            }
            unset($section);
            $destination = resource_path('documentation/articles/'.$id.'.json');
            File::ensureDirectoryExists(dirname($destination));
            File::put($destination, json_encode($article, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR)."\n");
            unset($article['chapters'], $article['anchors']);
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
            return "\n";
        }
        if ($node instanceof StringContainerInterface) {
            return $node->getLiteral();
        }

        return implode('', array_map(fn (Node $child): string => $this->text($child), iterator_to_array($node->children())));
    }

    private function links(Node $node, array &$links): void
    {
        if ($node instanceof Link && ! $node instanceof Image) {
            $url = $node->getUrl();
            if (parse_url($url, PHP_URL_SCHEME) === null || parse_url($url, PHP_URL_SCHEME) === 'https') {
                $links[] = ['label' => $this->text($node), 'url' => $url];
            }
        }
        foreach ($node->children() as $child) {
            $this->links($child, $links);
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

    private function blocks(Node $node, string $id): array
    {
        if ($node instanceof FencedCode || $node instanceof IndentedCode) {
            return [['type' => 'code', 'language' => $node instanceof FencedCode ? $node->getInfo() : '', 'text' => rtrim($node->getLiteral(), "\n")]];
        }
        if ($node instanceof Table) {
            $rows = [];
            foreach ($node->children() as $section) {
                foreach ($section->children() as $row) {
                    $rows[] = array_map(fn (Node $cell): string => trim($this->text($cell)), iterator_to_array($row->children()));
                }
            }
            $headers = array_shift($rows) ?? [];
            $blocks = [];
            foreach ($rows as $row) {
                $lines = [];
                foreach (array_slice($row, 1, preserve_keys: true) as $index => $value) {
                    if ($value !== '') {
                        $lines[] = ($headers[$index] ?? '').': '.$value;
                    }
                }
                if (($row[0] ?? '') !== '' && $lines !== []) {
                    $blocks[] = ['type' => 'definition', 'label' => $row[0], 'text' => implode("\n", $lines)];
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
                    $children = $this->blocks($child, $id);
                    if ($first && isset($children[0]['text']) && $children[0]['type'] === 'text') {
                        $children[0]['text'] = ($ordered ? $number++.'. ' : '• ').$children[0]['text'];
                    }
                    $blocks = [...$blocks, ...$children];
                    $first = false;
                }
            }

            return $blocks;
        }
        if ($node instanceof Heading || $node instanceof Paragraph) {
            $text = trim($this->text($node));

            return [
                ...($text === '' ? [] : [['type' => $node instanceof Heading ? 'heading' : 'text', 'text' => $text]]),
                ...$this->images($node, $id),
            ];
        }
        $blocks = [];
        foreach ($node->children() as $child) {
            $blocks = [...$blocks, ...$this->blocks($child, $id)];
        }
        if ($blocks === [] && $node instanceof StringContainerInterface && trim(strip_tags($node->getLiteral())) !== '') {
            $blocks[] = ['type' => 'text', 'text' => trim(strip_tags($node->getLiteral()))];
        }

        return $blocks;
    }
}

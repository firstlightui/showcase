<?php

use App\Support\DocumentationLibrary;
use Native\Mobile\Testing\Native;

function documentationNodes(array $tree, string $type): array
{
    $nodes = ($tree['type'] ?? '') === $type ? [$tree] : [];
    foreach ($tree['children'] ?? [] as $child) {
        $nodes = [...$nodes, ...documentationNodes($child, $type)];
    }

    return $nodes;
}

it('bundles the entire reference for the installed Firstlight revision', function () {
    $library = app(DocumentationLibrary::class);
    $packages = json_decode(file_get_contents(base_path('composer.lock')), true)['packages'];
    $package = collect($packages)->firstWhere('name', 'firstlightui/nativephp');

    expect($library->manifest()['revision'])->toBe($package['dist']['reference'])
        ->and($library->articles())->toHaveCount(55)
        ->and(array_unique(array_column($library->articles(), 'group')))->toHaveCount(6);

    foreach ($library->articles() as $summary) {
        $article = $library->read($summary['id']);
        expect($article['title'])->toBe($summary['title'])->and($article['chapters'])->not->toBeEmpty();
        foreach ($article['chapters'] as $chapter) {
            foreach ($chapter['blocks'] as $block) {
                if ($block['type'] === 'image') {
                    expect(is_file(public_path($block['src'])))->toBeTrue();
                }
            }
        }
        foreach ($article['links'] as $link) {
            if (str_ends_with(explode('#', $link['url'])[0], '.md')) {
                expect($library->resolve($article['id'], $link['url']))->not->toBeNull();
            }
        }
    }
});

it('searches article contents without a connection and clears an empty result', function () {
    expect(app(DocumentationLibrary::class)->articles(' button ')[0]['id'])->toBe('components/button');
    $screen = Native::visit('/docs')->assertTabActive('Docs')->assertAccessible();
    $screen->select('query', '  PROGRAMMATIC PUBLICATIONS ')->assertSee('Button');
    $screen->select('query', 'no article has this sentence');
    expect(documentationNodes($screen->tree(), 'firstlight.callout')[0]['props']['message'])->toStartWith('No articles match your search');
    $screen->press('clearSearch')->assertSet('query', '')->assertSee('Install Firstlight UI');
    $screen->tap('Button')->assertNavigatedTo('/docs/components/button');
});

it('shows selectable code and readable property tables in the native article reader', function () {
    $screen = Native::visit('/docs/components/button')->assertNavTitle('Button')->assertTabActive('Docs')->assertAccessible();
    $screen->press('__syncProperty(\'chapter\', "complete-example")')->assertSet('chapter', 'complete-example')->assertSee('<firstlight:button');
    $code = collect(documentationNodes($screen->tree(), 'text'))->first(fn (array $node): bool => str_contains($node['props']['text'] ?? '', '<firstlight:button'));
    expect($code['props']['selectable'] ?? null)->toBe(1)->and($code['props']['font_family'] ?? null)->toBe(2);
    expect($code['props']['text'])->toBe(app(DocumentationLibrary::class)->read('components/button')['chapters']['complete-example']['blocks'][0]['text']);
    $screen->press('__syncProperty(\'chapter\', "props")')->assertSet('chapter', 'props')->assertSee('variant')->assertSee('Semantic action intent');
    $screen->press('tryExample')->assertNavigatedTo('/components/button');
});

it('follows bundled article links and preserves section anchors', function () {
    $library = app(DocumentationLibrary::class);
    expect($library->resolve('components/button', '../reference/compatibility.md#runtime-response-contract'))
        ->toBe('/docs/reference/compatibility?heading=runtime-response-contract')
        ->and($library->resolve('components/button', '#props'))->toBe('/docs/components/button?heading=props')
        ->and($library->resolve('components/button', '../../../../.env'))->toBeNull()
        ->and($library->resolve('components/button', 'javascript:alert(1)'))->toBeNull()
        ->and($library->resolve('components/button', '//example.com/secrets'))->toBeNull();

    Native::visit('/docs/components/button?heading=props')->assertSet('chapter', 'props')->assertSee('variant');
    Native::visit('/docs/reference/compatibility?heading=supported-response-revision-contract')
        ->assertSet('chapter', 'runtime-response-contract')->assertSee('response');
    $screen = Native::visit('/docs/components/button')->press('__syncProperty(\'chapter\', "compatibility")');
    $screen->press('openLink(0)')->assertNavigatedTo('/docs/reference/compatibility');
});

it('renders every bundled article section through the native wire tree', function () {
    $library = app(DocumentationLibrary::class);
    foreach ($library->articles() as $summary) {
        $screen = Native::visit($library->url($summary['id']));
        foreach ($library->read($summary['id'])['chapters'] as $id => $chapter) {
            $screen->call('__syncProperty', 'chapter', $id)->assertSet('chapter', $id)->assertAccessible();
            if ($id !== 'overview') {
                $screen->assertSee($chapter['title']);
            }
        }
    }
});

it('keeps document navigation restricted to the bundled catalogue', function () {
    Native::visit('/docs')->call('openArticle', '../../.env')->assertNoNavigation();
    Native::visit('/docs/components/button')->call('openLink', 999)->assertNoNavigation();
    expect(app(DocumentationLibrary::class)->find('../../.env'))->toBeNull();
});

it('reads as continuous prose with inline links and next-section paging', function () {
    $article = app(DocumentationLibrary::class)->read('how-to/build-a-form-screen');
    $intro = $article['chapters']['overview']['blocks'][1];
    // Hard-wrapped Markdown must not break sentences on the device.
    expect(implode('', array_column($intro['runs'], 'text')))->toBe('This recipe runs unchanged in the showcase app, where FormRecipeTest exercises it.')
        ->and($intro['runs'][1])->toMatchArray(['text' => 'showcase app', 'link' => 0]);

    $screen = Native::visit('/docs/how-to/build-a-form-screen')->assertSee('showcase app')->assertSee('1. Register the route')->assertSee('Next: 2. Write the Form Request');
    $screen->press('showSection(\'2-write-the-form-request\')')->assertSet('chapter', '2-write-the-form-request')->assertSee('2. Write the Form Request')->assertAccessible();
    Native::visit('/docs/how-to/build-a-form-screen')->press('openLink(2)')->assertNavigatedTo('/docs/how-to/build-a-settings-screen');
});

<?php

namespace App\NativeComponents\Layouts;

use App\NativeComponents\DocumentationArticleScreen;
use App\NativeComponents\ShowcaseScreen;
use App\Support\DocumentationLibrary;
use Illuminate\Support\Str;
use Native\Mobile\Edge\Layouts\Builders\NavAction;
use Native\Mobile\Edge\Layouts\Builders\NavBar;
use Native\Mobile\Edge\Layouts\Builders\Tab;
use Native\Mobile\Edge\Layouts\Builders\TabBar;
use Native\Mobile\Edge\Layouts\NativeLayout;
use Native\Mobile\Edge\NativeComponent;

/**
 * Native tab shell for the interactive showcase: SwiftUI TabView (Liquid
 * Glass on iOS 26+) and a Material 3 NavigationBar. Bars carry no colours,
 * so both platforms follow the system appearance.
 *
 * Tabs own screens by URL prefix: a screen pushed from Components lives at
 * /components/<slug>. Gallery is the root URL, so it must stay the first
 * tab; native chrome assigns any URI no tab prefixes to the first tab.
 *
 * Capture fixtures (not ShowcaseScreen) keep a plain inline bar and no tabs.
 */
class ShowcaseLayout extends NativeLayout
{
    public function usesNativeChrome(): bool
    {
        return true;
    }

    public function navBar(NativeComponent $screen): ?NavBar
    {
        $bar = NavBar::make()->title($screen->navTitle());

        if (! $screen instanceof ShowcaseScreen) {
            return $bar->displayMode('inline');
        }

        if ($screen instanceof DocumentationArticleScreen) {
            // Reader controls live in the bar so the first viewport is the article.
            $bar->action(NavAction::make('contents')
                ->label('Contents')
                ->a11yLabel('Contents')
                ->icon(ios: 'list.bullet', android: 'toc')
                ->items(array_map(function (string $key, string $title) use ($screen): NavAction {
                    $item = NavAction::make('section-'.$key)->label($title)->press("showSection('{$key}')");

                    return $key === $screen->chapter ? $item->icon(ios: 'checkmark', android: 'check') : $item;
                }, array_keys($screen->contents()), $screen->contents())));
            if ($example = app(DocumentationLibrary::class)->example($screen->articleId)) {
                $bar->action(NavAction::make('example')
                    ->label('Try the live example')
                    ->a11yLabel('Try the live example')
                    ->icon(ios: 'play.circle', android: 'play_circle')
                    ->url($example));
            }
        }

        $class = class_basename($screen);
        if (str_ends_with($class, 'Showcase')) {
            $slug = Str::kebab(Str::beforeLast($class, 'Showcase'));
            $id = $slug === 'validation' ? 'how-to/validate-fields' : 'components/'.$slug;
            $library = app(DocumentationLibrary::class);
            if ($library->find($id)) {
                $bar->action(NavAction::make('documentation')
                    ->label('Documentation')
                    ->a11yLabel('Documentation')
                    ->icon(ios: 'book', android: 'menu_book')
                    ->url($library->url($id)));
            }
        }

        return $bar
            ->displayMode($screen->showsBackButton() ? 'inline' : 'large')
            ->back($screen->showsBackButton());
    }

    public function tabBar(NativeComponent $screen): ?TabBar
    {
        if (! $screen instanceof ShowcaseScreen) {
            return null;
        }

        return TabBar::make()
            ->add(Tab::link('Gallery', '/', icon: 'star', ios: 'sparkles', android: 'auto_awesome'))
            ->add(Tab::link('Recipes', '/recipes', icon: 'apps', ios: 'square.stack.3d.up', android: 'layers'))
            ->add(Tab::link('Components', '/components', icon: 'widgets', ios: 'square.grid.2x2', android: 'widgets'))
            ->add(Tab::link('Docs', '/docs', icon: 'menu_book', ios: 'book.closed', android: 'menu_book'));
    }
}

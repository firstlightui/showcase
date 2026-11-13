<?php

namespace App\NativeComponents;

use App\Models\Post;
use FirstlightUI\AppShortcuts\Facades\AppShortcuts;
use FirstlightUI\AppShortcuts\Shortcut;
use FirstlightUI\Facades\Feedback;
use FirstlightUI\NativeComponent;
use Illuminate\View\View;

/**
 * firstlightui/app-shortcuts: the shortcuts built into this app from config/app-shortcuts.php,
 * and one the app adds to the icon's long-press menu while it runs.
 */
class AppShortcutsScreen extends NativeComponent
{
    public const string RUNTIME_ID = 'newest-post';

    public function open(string $id): void
    {
        foreach (AppShortcuts::configured() as $shortcut) {
            if ($shortcut->id === $id) {
                $this->navigate($shortcut->path);
            }
        }
    }

    public function addNewestPost(): void
    {
        $post = Post::query()->latest()->orderByDesc('id')->first();

        if ($post === null) {
            Feedback::warning('There are no posts to add')->send();

            return;
        }

        $this->report(
            AppShortcuts::push(self::RUNTIME_ID, $post->title, 'recipes.post', ['post' => $post], 'doc.text', 'Newest post'),
            "Added \"{$post->title}\". Long-press the app icon to see it",
        );
    }

    public function removeNewestPost(): void
    {
        $this->report(AppShortcuts::remove(self::RUNTIME_ID), 'Removed the newest post shortcut');
    }

    private function report(bool $applied, string $message): void
    {
        if ($applied) {
            Feedback::success($message)->send();

            return;
        }

        Feedback::warning('Shortcuts need a native build of the app')->send();
    }

    /**
     * Where each built-in shortcut appears, such as "Siri: “New post in Firstlight Showcase” · App icon".
     *
     * @return list<array{id: string, title: string, places: string}>
     */
    public function shortcutRows(): array
    {
        return array_map(function (Shortcut $shortcut): array {
            $places = [];

            if ($shortcut->phrases !== []) {
                $places[] = 'Siri: “'.str_replace(Shortcut::APPLICATION_NAME, config('app.name'), $shortcut->phrases[0]).'”';
            }

            $places[] = 'Spotlight';

            if ($shortcut->launcher) {
                $places[] = 'App icon';
            }

            if ($shortcut->requiresUnlock) {
                $places[] = 'Unlock first';
            }

            return ['id' => $shortcut->id, 'title' => $shortcut->title, 'places' => implode(' · ', $places)];
        }, AppShortcuts::configured());
    }

    public function navTitle(): string
    {
        return 'App shortcuts';
    }

    public function render(): View
    {
        return view('native.app-shortcuts', ['shortcuts' => $this->shortcutRows()]);
    }
}

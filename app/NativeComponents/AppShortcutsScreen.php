<?php

namespace App\NativeComponents;

use App\Actions\AddQuickPost;
use App\Models\Post;
use Carbon\CarbonImmutable;
use FirstlightUI\AppShortcuts\ActionInvocation;
use FirstlightUI\AppShortcuts\Events\ActionRan;
use FirstlightUI\AppShortcuts\Facades\AppShortcuts;
use FirstlightUI\AppShortcuts\Shortcut;
use FirstlightUI\Facades\Feedback;
use FirstlightUI\NativeComponent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Native\Mobile\Attributes\On;

/**
 * firstlightui/app-shortcuts: the shortcuts built into this app from config/app-shortcuts.php,
 * the quick-post action that runs PHP instead of opening a screen, and one shortcut the app adds to
 * the icon's long-press menu while it runs.
 */
class AppShortcutsScreen extends NativeComponent
{
    public const string RUNTIME_ID = 'newest-post';

    public function open(string $id): void
    {
        foreach (AppShortcuts::configured() as $shortcut) {
            if ($shortcut->id === $id && $shortcut->path !== null) {
                $this->navigate($shortcut->path);
            }
        }
    }

    /** Runs an action's class here, with an invocation like the one a shortcut queues. */
    public function runAction(string $id): void
    {
        foreach (AppShortcuts::configured() as $shortcut) {
            if ($shortcut->id === $id && $shortcut->action !== null) {
                app($shortcut->action)(new ActionInvocation((string) Str::uuid(), $id, CarbonImmutable::now()));
                Feedback::success($shortcut->reply ?? "Ran {$shortcut->title}")->send();
            }
        }
    }

    /** Redraws the quick-post count when the action runs while this screen is open. */
    #[On(ActionRan::class)]
    public function actionRan(string $shortcutId): void
    {
        if ($shortcutId === 'quick-post') {
            Feedback::success('A shortcut added a quick post')->send();
        }
    }

    public function openNewestQuickPost(): void
    {
        $post = $this->quickPosts()->first();

        if ($post === null) {
            Feedback::warning('No quick posts yet. Run the shortcut from Siri, Spotlight or the app icon')->send();

            return;
        }

        $this->navigate(route('recipes.post', ['post' => $post], false));
    }

    /**
     * @return Builder<Post>
     */
    private function quickPosts(): Builder
    {
        return Post::query()->where('title', 'like', AddQuickPost::TITLE_PREFIX.' %')->latest()->orderByDesc('id');
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
    public function shortcutRows(bool $actions = false): array
    {
        $shortcuts = array_filter(AppShortcuts::configured(), fn (Shortcut $s) => ($s->action !== null) === $actions);

        return array_values(array_map(function (Shortcut $shortcut): array {
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
        }, $shortcuts));
    }

    public function navTitle(): string
    {
        return 'App shortcuts';
    }

    public function render(): View
    {
        $quickPosts = $this->quickPosts()->count();

        return view('native.app-shortcuts', [
            'shortcuts' => $this->shortcutRows(),
            'actions' => $this->shortcutRows(actions: true),
            'quickPosts' => $quickPosts === 1 ? '1 quick post so far' : "{$quickPosts} quick posts so far",
        ]);
    }
}

<?php

namespace App\Actions;

use App\Models\Post;
use FirstlightUI\AppShortcuts\ActionInvocation;

/**
 * The "Add a quick post" shortcut action: writes a post without opening a screen.
 *
 * The post is dated when the shortcut was used, which can be before this runs: an action that PHP
 * could not run straight away runs the next time the app opens.
 */
class AddQuickPost
{
    public const string TITLE_PREFIX = 'Quick post from';

    /**
     * Returns what Siri says, or Android shows, such as "Added a quick post. That's 3 so far."
     */
    public function __invoke(ActionInvocation $invocation): string
    {
        $post = new Post([
            'title' => self::TITLE_PREFIX.' '.$invocation->invokedAt->format('j M, H:i'),
            'excerpt' => 'Added by an app shortcut',
            'body' => 'Added by the "Add a quick post" shortcut, without opening a screen.',
        ]);

        $post->created_at = $invocation->invokedAt;
        $post->save();

        $count = Post::query()->where('title', 'like', self::TITLE_PREFIX.' %')->count();

        return $count === 1 ? 'Added your first quick post.' : "Added a quick post. That's {$count} so far.";
    }
}

<?php

namespace App\Actions;

use App\Models\Post;
use FirstlightUI\AppShortcuts\ActionInvocation;

/**
 * The "Add a quick post" shortcut action: writes a post without opening a screen.
 *
 * The post is dated when the shortcut was used, which can be well before this runs: on iOS, an
 * action used while the app is closed runs the next time the app opens.
 */
class AddQuickPost
{
    public const string TITLE_PREFIX = 'Quick post from';

    public function __invoke(ActionInvocation $invocation): void
    {
        $post = new Post([
            'title' => self::TITLE_PREFIX.' '.$invocation->invokedAt->format('j M, H:i'),
            'excerpt' => 'Added by an app shortcut',
            'body' => 'Added by the "Add a quick post" shortcut, without opening a screen.',
        ]);

        $post->created_at = $invocation->invokedAt;
        $post->save();
    }
}

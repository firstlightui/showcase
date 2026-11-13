<?php

use App\Actions\AddQuickPost;

/*
| Shortcuts built into the app; the Recipes tab's App shortcuts screen adds one at runtime.
| quick-post runs App\Actions\AddQuickPost instead of opening a screen.
| See firstlightui/app-shortcuts for every option.
*/

return [
    'shortcuts' => [
        'new-post' => [
            'title' => 'New post',
            'route' => 'recipes.posts.create',
            'icon' => 'square.and.pencil',
            'phrases' => ['New post in ${applicationName}', 'Write a post in ${applicationName}'],
        ],
        // Behind App Guard's app-lock middleware, so a locked app shows the lock screen first.
        'vault' => [
            'title' => 'Open the vault',
            'short_title' => 'Vault',
            'route' => 'vault',
            'icon' => 'lock.fill',
            'phrases' => ['Open the vault in ${applicationName}'],
        ],
        'quick-post' => [
            'title' => 'Add a quick post',
            'short_title' => 'Quick post',
            'action' => AddQuickPost::class,
            'reply' => 'Added a quick post',
            'icon' => 'text.badge.plus',
            'phrases' => ['Add a quick post in ${applicationName}'],
        ],
        'haptics' => [
            'title' => 'Try haptics',
            'short_title' => 'Haptics',
            'route' => 'haptics',
            'icon' => 'hand.tap',
            'requires_unlock' => false,
        ],
    ],
];

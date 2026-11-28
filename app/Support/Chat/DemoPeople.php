<?php

namespace App\Support\Chat;

use FirstlightUI\ChatServer\Contracts\UserDirectory;

/** Who is in the Chat recipe. You are `me`; the others answer from {@see DemoReplies}. */
class DemoPeople implements UserDirectory
{
    public const ME = 'me';

    public const PEOPLE = [
        'me' => 'You',
        'sam' => 'Sam Lee',
        'priya' => 'Priya Shah',
        'jo' => 'Jo Park',
    ];

    public function profiles(array $users): array
    {
        return collect($users)
            ->filter(fn ($user) => isset(self::PEOPLE[$user]))
            ->mapWithKeys(fn ($user) => [$user => ['name' => self::PEOPLE[$user], 'avatar_url' => null]])
            ->all();
    }
}

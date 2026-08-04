<?php

namespace App\Support;

use Illuminate\Contracts\Cache\Repository;
use Native\Mobile\UI\Theme;

class ShowcaseAppearance
{
    private const CACHE_KEY = 'firstlight-showcase.appearance.dark';

    /** @param array<string, mixed> $theme */
    public function __construct(
        private readonly Repository $cache,
        private readonly array $theme,
    ) {}

    public function prefersDark(): bool
    {
        return (bool) $this->cache->get(self::CACHE_KEY, false);
    }

    public function remember(bool $dark): void
    {
        $this->cache->forever(self::CACHE_KEY, $dark);
        $this->apply($dark);
    }

    public function apply(bool $dark): void
    {
        $palette = $this->theme[$dark ? 'dark' : 'light'] ?? [];

        Theme::load(array_replace($this->theme, [
            'light' => $palette,
            'dark' => $palette,
        ]));
    }
}

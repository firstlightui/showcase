<?php

namespace App\NativeComponents;

use Carbon\CarbonImmutable;
use FirstlightUI\Concerns\TracksFreshness;
use FirstlightUI\NativeComponent;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Native\Mobile\Network;

/**
 * A server list cached on the phone, with TracksFreshness saying how old it
 * is. The fetch is pretend: it fails while the phone is offline or while
 * "Fail refreshes" is on, the way a real API call would.
 */
class ServerStatusScreen extends NativeComponent
{
    use TracksFreshness;

    private const CACHE_KEY = 'recipes.server-status';

    /** @var list<array{name: string, healthy: bool, load: int}> */
    public array $servers = [];

    public bool $failRefreshes = false;

    public function mount(): void
    {
        $cached = Cache::get(self::CACHE_KEY);
        $this->servers = $cached['servers'] ?? [];

        $this->freshness('servers', freshFor: 60)
            ->showing($cached === null ? null : CarbonImmutable::parse($cached['fetched_at']));

        if ($this->freshness('servers')->isStale()) {
            $this->reload();
        }
    }

    public function reload(): void
    {
        $this->freshness('servers')->refreshing();

        if ($this->failRefreshes || app(Network::class)->status()?->connected === false) {
            $this->freshness('servers')->failed(retry: 'reload');

            return;
        }

        $fetchedAt = CarbonImmutable::now()->startOfSecond();
        $this->store($fetchedAt, [
            ['name' => 'web-1', 'healthy' => true, 'load' => random_int(10, 60)],
            ['name' => 'web-2', 'healthy' => true, 'load' => random_int(10, 60)],
            ['name' => 'worker-1', 'healthy' => random_int(0, 3) > 0, 'load' => random_int(40, 95)],
            ['name' => 'db-1', 'healthy' => true, 'load' => random_int(20, 70)],
        ]);
        $this->freshness('servers')->refreshed($fetchedAt);
    }

    /** Pretends the last fetch was this many minutes earlier, so the age notices show without waiting. */
    public function age(int $minutes): void
    {
        $fetchedAt = $this->freshness('servers')->fetchedAt();

        if ($fetchedAt === null) {
            return;
        }

        $this->store($fetchedAt->subMinutes($minutes), $this->servers);
        $this->freshness('servers')->showing($fetchedAt->subMinutes($minutes));
    }

    public function forget(): void
    {
        Cache::forget(self::CACHE_KEY);
        $this->servers = [];
        $this->freshness('servers')->showing(null);
    }

    public function navTitle(): string
    {
        return 'Server status';
    }

    public function render(): View
    {
        return view('native.server-status');
    }

    /** @param list<array{name: string, healthy: bool, load: int}> $servers */
    private function store(CarbonImmutable $fetchedAt, array $servers): void
    {
        Cache::forever(self::CACHE_KEY, ['servers' => $servers, 'fetched_at' => $fetchedAt->toIso8601String()]);
        $this->servers = $servers;
    }
}

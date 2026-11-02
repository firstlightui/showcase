<?php

namespace App\Providers;

use App\Security\AppLock;
use FirstlightUI\AppGuard\Events\Foregrounded;
use Illuminate\Auth\GenericUser;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AppLock::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(fn (Foregrounded $event) => app(AppLock::class)->foregrounded($event->shielded));

        // Only the List demo uses this disposable sample identity; no account or database writes.
        Gate::define('showcase-delete', fn (GenericUser $user, array $item): bool => (bool) $user->may_delete);
    }
}

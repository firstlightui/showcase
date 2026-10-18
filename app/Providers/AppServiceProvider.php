<?php

namespace App\Providers;

use App\Support\ShowcaseAppearance;
use Illuminate\Auth\GenericUser;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            ShowcaseAppearance::class,
            fn ($app) => new ShowcaseAppearance(
                $app['cache']->store(),
                $app['config']->get('native-ui.theme', []),
            ),
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Only the List demo uses this disposable sample identity; no account or database writes.
        Gate::define('showcase-delete', fn (GenericUser $user, array $item): bool => (bool) $user->may_delete);
    }
}

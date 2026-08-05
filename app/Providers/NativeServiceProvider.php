<?php

namespace App\Providers;

use App\Support\ShowcaseFeedbackLog;
use FirstlightUI\Events\FeedbackActionPressed;
use FirstlightUI\Events\FeedbackDismissed;
use FirstlightUI\FirstlightServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Native\Mobile\UI\NativeUIServiceProvider;

class NativeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ShowcaseFeedbackLog::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Event::listen(function (FeedbackActionPressed $event): void {
            app(ShowcaseFeedbackLog::class)->recordAction($event);
        });

        Event::listen(function (FeedbackDismissed $event): void {
            app(ShowcaseFeedbackLog::class)->recordDismissal($event);
        });
    }

    /**
     * The NativePHP plugins to enable.
     *
     * Only plugins listed here will be compiled into your native builds.
     * This is a security measure to prevent transitive dependencies from
     * automatically registering plugins without your explicit consent.
     *
     * @return array<int, class-string<ServiceProvider>>
     */
    public function plugins(): array
    {
        return [
            NativeUIServiceProvider::class,
            FirstlightServiceProvider::class,
        ];
    }
}

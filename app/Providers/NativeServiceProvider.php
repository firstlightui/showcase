<?php

namespace App\Providers;

use App\Support\Chat\DemoPeople;
use App\Support\Chat\DemoReplies;
use App\Support\Chat\InAppChatServer;
use App\Support\DemoOutbox;
use App\Support\ShowcaseFeedbackLog;
use FirstlightUI\AppGuard\AppGuardServiceProvider;
use FirstlightUI\AppShortcuts\AppShortcutsServiceProvider;
use FirstlightUI\Chat\Facades\Chat;
use FirstlightUI\ChatServer\Contracts\ChatNotifier;
use FirstlightUI\ChatServer\Contracts\UserDirectory;
use FirstlightUI\Events\FeedbackActionPressed;
use FirstlightUI\Events\FeedbackDismissed;
use FirstlightUI\Facades\Connectivity;
use FirstlightUI\FirstlightServiceProvider;
use FirstlightUI\LocalAI\LocalAIServiceProvider;
use FirstlightUI\LocalOcr\LocalOcrServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Native\Mobile\Providers\BiometricsServiceProvider;
use Native\Mobile\Providers\BrowserServiceProvider;
use Native\Mobile\Providers\NetworkServiceProvider;
use Native\Mobile\UI\NativeUIServiceProvider;

class NativeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ShowcaseFeedbackLog::class);

        // The Chat recipe's people and replies, for the chat server inside the app.
        $this->app->bind(UserDirectory::class, DemoPeople::class);
        $this->app->bind(ChatNotifier::class, DemoReplies::class);
        InAppChatServer::configure();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // The Offline changes recipe's queue, shown in the nav bar subtitle.
        Connectivity::pendingUsing(DemoOutbox::class);

        // The Chat recipe (firstlightui/chat) talks to a chat server inside the app, as you.
        InAppChatServer::install();
        Chat::identifyUsing(fn () => DemoPeople::ME);
        Chat::authenticateUsing(fn () => DemoPeople::ME);

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
            BiometricsServiceProvider::class,
            BrowserServiceProvider::class,
            NetworkServiceProvider::class,
            FirstlightServiceProvider::class,
            AppGuardServiceProvider::class,
            AppShortcutsServiceProvider::class,
            LocalOcrServiceProvider::class,
            LocalAIServiceProvider::class,
        ];
    }
}

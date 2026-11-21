<?php

namespace App\Providers;

use FirstlightUI\CustomTabs\Facades\CustomTabs;
use FirstlightUI\CustomTabs\TabSet;
use Illuminate\Auth\GenericUser;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Native\Mobile\Edge\Layouts\Builders\Tab;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Only the List demo uses this disposable sample identity; no account or database writes.
        Gate::define('showcase-delete', fn (GenericUser $user, array $item): bool => (bool) $user->may_delete);

        // The Customise tabs recipe (firstlightui/custom-tabs).
        CustomTabs::define('tabs-demo', fn (TabSet $tabs) => $tabs
            ->tab('home', Tab::link('Home', '/tabs/home', icon: 'home', ios: 'house', android: 'home'))->locked()
            ->tab('inbox', Tab::link('Inbox', '/tabs/inbox', icon: 'inbox', ios: 'tray', android: 'inbox')->badge('3'))
            ->tab('calendar', Tab::link('Calendar', '/tabs/calendar', icon: 'calendar_month', ios: 'calendar', android: 'calendar_month'))
            ->tab('reports', Tab::link('Reports', '/tabs/reports', icon: 'bar_chart', ios: 'chart.bar', android: 'bar_chart'))
            ->tab('people', Tab::link('People', '/tabs/people', icon: 'group', ios: 'person.2', android: 'group'))->inMoreByDefault()
            ->tab('files', Tab::link('Files', '/tabs/files', icon: 'folder', ios: 'folder', android: 'folder'))->inMoreByDefault()
            ->more(Tab::link('More', '/tabs/more', icon: 'more_horiz', ios: 'ellipsis', android: 'more_horiz')));
    }
}

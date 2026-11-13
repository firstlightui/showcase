<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-4">
        @include('firstlight::native.freshness', ['notice' => $this->freshnessNotice('servers')])

        @foreach ($servers as $server)
            <native:row class="w-full items-center gap-3 p-3 rounded-lg bg-theme-surface">
                <native:column class="flex-1 gap-1">
                    <native:text class="text-base font-semibold text-theme-on-surface" :text="$server['name']" />
                    <native:text class="text-sm text-theme-on-surface-variant" :text="'Load '.$server['load'].'%'" />
                </native:column>
                @if ($server['healthy'])
                    <firstlight:status-label label="Healthy" tone="success" icon="check_circle" icon-ios="checkmark.circle" icon-android="check_circle" />
                @else
                    <firstlight:status-label label="Degraded" tone="warning" icon="warning" icon-ios="exclamationmark.triangle" icon-android="warning" />
                @endif
            </native:row>
        @endforeach

        <native:column class="w-full gap-3 pt-4">
            <native:text class="text-sm font-semibold text-theme-on-surface">Try the states</native:text>
            <native:text class="text-sm text-theme-on-surface-variant">The fetch is pretend. It fails while the phone is offline, so turn on airplane mode to see the offline notices.</native:text>
            <firstlight:switch label="Fail refreshes" native:model="failRefreshes" />
            <firstlight:button label="Refresh" @press="reload" />
            <firstlight:button label="Make the data 15 minutes old" variant="secondary" @press="age(15)" />
            <firstlight:button label="Make the data 2 hours old" variant="secondary" @press="age(120)" />
            <firstlight:button label="Make the data a day old" variant="secondary" @press="age(1440)" />
            <firstlight:button label="Forget cached data" variant="secondary" @press="forget" />
        </native:column>
    </native:column>
</native:scroll-view>

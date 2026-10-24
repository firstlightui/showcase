<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full gap-5 pb-8">
        <firstlight:tabs
            native:model="tab"
            :tabs="$serverSections"
            label="Server sections"
            a11y-hint="Shows one section of this server"
        />

        <native:column class="w-full px-4 gap-3">
            <native:text class="text-sm text-theme-on-surface">On-device checks: switch sections and confirm only the selected pane renders below. Manage is unavailable. Show Deployments selects it from PHP without a change event. Add or clear failures to update the badge, and check VoiceOver or TalkBack reads "Deployments, 2 failed", the tab role, selected state, and position. The guarded group ignores Manage while attempts increase. Null starts unselected; empty and disabled groups stay inert. Five long labels scroll on Android and size to content on iOS.</native:text>
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: tab {{ $tab }}; failed deployments {{ $failedDeployments }}; guarded {{ $guardedTab }}; guarded attempts {{ $guardedAttempts }}; initially empty {{ $unselected ?? '(none)' }}; long {{ $longTab }}</native:text>

            @if ($tab === 'overview')
                <native:text class="text-base font-semibold text-theme-on-surface">Overview pane</native:text>
            @elseif ($tab === 'sites')
                <native:text class="text-base font-semibold text-theme-on-surface">Sites pane</native:text>
            @elseif ($tab === 'deploys')
                <native:text class="text-base font-semibold text-theme-on-surface">Deployments pane</native:text>
            @endif

            <native:button label="Show Deployments" variant="primary" @press="showDeployments" />
            <native:button label="Fail another deployment" @press="failAnotherDeployment" />
            <native:button label="Clear failures" @press="clearFailures" />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="px-4 text-sm font-semibold text-theme-on-surface">Rejected change</native:text>
            <firstlight:tabs
                :value="$guardedTab"
                @change="selectGuardedTab"
                :tabs="$simpleTabs"
                label="Guarded sections"
                a11y-hint="The server ignores Manage in this example"
            />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="px-4 text-sm font-semibold text-theme-on-surface">Null and empty states</native:text>
            <firstlight:tabs native:model="unselected" :tabs="$simpleTabs" label="No initial section" />
            <firstlight:tabs :value="null" :tabs="$emptyTabs" label="No available sections" />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="px-4 text-sm font-semibold text-theme-on-surface">Disabled group</native:text>
            <firstlight:tabs native:model="disabledTab" :tabs="$simpleTabs" label="Disabled sections" disabled />
        </native:column>

        <native:column class="w-full gap-2 pb-6">
            <native:text class="px-4 text-sm font-semibold text-theme-on-surface">Five long labels</native:text>
            <firstlight:tabs native:model="longTab" :tabs="$longTabs" label="Site sections" />
        </native:column>
    </native:column>
</native:scroll-view>

<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 pb-8 gap-5">
        <native:text class="text-sm font-semibold text-theme-on-surface">On-device checks</native:text>
        <native:text class="text-sm text-theme-on-surface-variant">Enabled icons record a press and name the action below; they do not share, delete or navigate. Disabled and loading icons should leave the result unchanged.</native:text>
        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Semantic variants</native:text>
            <native:row class="w-full gap-3 flex-wrap">
                <firstlight:icon-button icon="plus" a11y-label="Primary add" @press="recordPress('Primary add')" />
                <firstlight:icon-button icon="info" variant="secondary" a11y-label="Secondary information" @press="recordPress('Secondary information')" />
                <firstlight:icon-button icon="trash" variant="destructive" a11y-label="Delete draft" @press="recordPress('Delete draft')" />
                <firstlight:icon-button icon="check" variant="success" a11y-label="Confirm selection" @press="recordPress('Confirm selection')" />
                <firstlight:icon-button icon="ellipsis" variant="ghost" a11y-label="More actions" @press="recordPress('More actions')" />
            </native:row>
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Native sizes</native:text>
            <native:row class="w-full gap-3">
                <firstlight:icon-button icon="plus" size="sm" a11y-label="Add small item" @press="recordPress('Add small item')" />
                <firstlight:icon-button icon="plus" size="md" a11y-label="Add medium item" @press="recordPress('Add medium item')" />
                <firstlight:icon-button icon="plus" size="lg" a11y-label="Add large item" @press="recordPress('Add large item')" />
            </native:row>
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Platform overrides</native:text>
            <firstlight:icon-button
                icon="share"
                icon-ios="square.and.arrow.up"
                icon-android="share"
                a11y-label="Share item"
                a11y-hint="Records a Share item press; does not open sharing destinations"
                @press="recordPress('Share item')"
            />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">States</native:text>
            <native:row class="w-full gap-3">
                <firstlight:icon-button icon="lock" a11y-label="Unavailable action" disabled @press="recordPress" />
                <firstlight:icon-button icon="arrow-clockwise" a11y-label="Refresh items" loading @press="recordPress" />
            </native:row>
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">PHP-owned transition</native:text>
            <native:text class="text-sm text-theme-on-surface-variant">Sync inbox publishes loading at once; PHP publishes the result about 2 seconds later. The lock button publishes disabled on the same icon button. Neither state may emit a press.</native:text>
            <native:row class="w-full gap-3">
                <firstlight:icon-button
                    icon="inbox"
                    a11y-label="Sync inbox"
                    :loading="$syncing"
                    :disabled="$syncLocked"
                    @press="syncInbox"
                />
                <firstlight:icon-button
                    icon="lock"
                    variant="secondary"
                    :a11y-label="$syncLocked ? 'Unlock syncing' : 'Lock syncing'"
                    @press="toggleSyncLock"
                />
            </native:row>
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $syncOutcome }} · {{ $syncCount }} synced · {{ $syncing ? 'loading' : 'idle' }} · {{ $syncLocked ? 'disabled' : 'enabled' }}</native:text>
        </native:column>

        <native:column class="w-full gap-3 pb-6">
            <native:text class="text-sm font-semibold text-theme-on-surface">Interaction</native:text>
            <firstlight:icon-button icon="plus" variant="primary" a11y-label="Record press" @press="recordPress" />
            <native:text class="text-sm text-theme-on-surface">Recorded presses: {{ $pressCount }}</native:text>
            <native:text class="text-sm text-theme-on-surface">Last action: {{ $lastAction }}</native:text>
        </native:column>
    </native:column>
</native:scroll-view>

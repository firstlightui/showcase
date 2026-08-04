<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-5">
        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Semantic variants</native:text>
            <native:row class="w-full gap-3 flex-wrap">
                <firstlight:icon-button icon="plus" a11y-label="Primary add" @press="recordPress" />
                <firstlight:icon-button icon="info" variant="secondary" a11y-label="Secondary information" @press="recordPress" />
                <firstlight:icon-button icon="trash" variant="destructive" a11y-label="Delete draft" @press="recordPress" />
                <firstlight:icon-button icon="check" variant="success" a11y-label="Confirm selection" @press="recordPress" />
                <firstlight:icon-button icon="ellipsis" variant="ghost" a11y-label="More actions" @press="recordPress" />
            </native:row>
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Native sizes</native:text>
            <native:row class="w-full gap-3">
                <firstlight:icon-button icon="plus" size="sm" a11y-label="Add small item" @press="recordPress" />
                <firstlight:icon-button icon="plus" size="md" a11y-label="Add medium item" @press="recordPress" />
                <firstlight:icon-button icon="plus" size="lg" a11y-label="Add large item" @press="recordPress" />
            </native:row>
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Platform overrides</native:text>
            <firstlight:icon-button
                icon="share"
                icon-ios="square.and.arrow.up"
                icon-android="share"
                a11y-label="Share item"
                a11y-hint="Opens available sharing destinations"
                @press="recordPress"
            />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">States</native:text>
            <native:row class="w-full gap-3">
                <firstlight:icon-button icon="lock" a11y-label="Unavailable action" disabled @press="recordPress" />
                <firstlight:icon-button icon="arrow-clockwise" a11y-label="Refresh items" loading @press="recordPress" />
            </native:row>
        </native:column>

        <native:column class="w-full gap-3 pb-6">
            <native:text class="text-sm font-semibold text-theme-on-surface">Interaction</native:text>
            <firstlight:icon-button icon="plus" variant="primary" a11y-label="Record press" @press="recordPress" />
            <native:text class="text-sm text-theme-on-surface">Recorded presses: {{ $pressCount }}</native:text>
        </native:column>
    </native:column>
</native:scroll-view>

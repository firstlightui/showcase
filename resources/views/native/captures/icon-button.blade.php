<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-5 gap-5">
        <native:text class="text-lg font-semibold text-theme-on-surface">Compact actions</native:text>
        <native:row class="w-full gap-3 flex-wrap">
            <firstlight:icon-button icon="plus" a11y-label="Primary add" @press="capturePress" />
            <firstlight:icon-button icon="info" variant="secondary" a11y-label="Secondary information" @press="capturePress" />
            <firstlight:icon-button icon="trash" variant="destructive" a11y-label="Delete draft" @press="capturePress" />
            <firstlight:icon-button icon="check" variant="success" a11y-label="Confirm selection" @press="capturePress" />
            <firstlight:icon-button icon="ellipsis" variant="ghost" a11y-label="More actions" @press="capturePress" />
        </native:row>
        <native:row class="w-full gap-3">
            <firstlight:icon-button icon="plus" size="sm" a11y-label="Add small item" @press="capturePress" />
            <firstlight:icon-button icon="plus" size="md" a11y-label="Add medium item" @press="capturePress" />
            <firstlight:icon-button icon="plus" size="lg" a11y-label="Add large item" @press="capturePress" />
        </native:row>
        <native:row class="w-full gap-3">
            <firstlight:icon-button icon="share" icon-ios="square.and.arrow.up" icon-android="share" a11y-label="Share item" @press="capturePress" />
            <firstlight:icon-button icon="lock" a11y-label="Unavailable action" disabled @press="capturePress" />
            <firstlight:icon-button icon="arrow-clockwise" a11y-label="Refresh items" loading @press="capturePress" />
        </native:row>
    </native:column>
</native:scroll-view>

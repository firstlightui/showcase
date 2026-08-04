<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-5 gap-5">
        <native:text class="text-lg font-semibold text-theme-on-surface">Loading appointments</native:text>

        <native:row class="w-full items-center gap-3">
            <firstlight:activity-indicator
                size="sm"
                a11y-label="Loading compact appointment summary"
            />
            <native:text class="text-sm text-theme-on-surface">Small</native:text>
        </native:row>

        <native:row class="w-full items-center gap-3">
            <firstlight:activity-indicator a11y-label="Loading appointments" />
            <native:text class="text-sm text-theme-on-surface">Medium</native:text>
        </native:row>

        <native:row class="w-full items-center gap-3">
            <firstlight:activity-indicator
                size="lg"
                a11y-label="Loading detailed appointment timeline"
            />
            <native:text class="text-sm text-theme-on-surface">Large</native:text>
        </native:row>
    </native:column>
</native:scroll-view>

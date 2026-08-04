<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-5">
        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Semantic sizes</native:text>

            <native:row class="w-full items-center gap-3">
                <firstlight:activity-indicator
                    size="sm"
                    a11y-label="Loading compact appointment summary"
                />
                <native:text class="text-sm text-theme-on-surface">Small · compact supporting activity</native:text>
            </native:row>

            <native:row class="w-full items-center gap-3">
                <firstlight:activity-indicator
                    a11y-label="Loading appointment list"
                />
                <native:text class="text-sm text-theme-on-surface">Medium · default activity</native:text>
            </native:row>

            <native:row class="w-full items-center gap-3">
                <firstlight:activity-indicator
                    size="lg"
                    a11y-label="Synchronising the complete historical appointment archive for offline access"
                />
                <native:text class="text-sm text-theme-on-surface">Large · synchronising the complete historical appointment archive for offline access</native:text>
            </native:row>
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Conditional presence</native:text>
            <native:text class="text-sm text-theme-on-surface">Presence means active. Remove the native element when the work ends.</native:text>

            @if ($loading)
                <firstlight:activity-indicator a11y-label="Loading appointments" />

                <firstlight:button
                    label="Hide activity indicator"
                    @press="hideIndicator"
                />
            @else
                <firstlight:button
                    label="Show activity indicator"
                    @press="showIndicator"
                />
            @endif
        </native:column>

        <native:column class="w-full gap-2 pb-6">
            <native:text class="text-sm font-semibold text-theme-on-surface">Activity Indicator or Progress?</native:text>
            <native:text class="text-sm text-theme-on-surface">Use Activity Indicator for circular indeterminate work. Use Progress when completion is measurable or a linear treatment communicates the task.</native:text>
        </native:column>
    </native:column>
</native:scroll-view>

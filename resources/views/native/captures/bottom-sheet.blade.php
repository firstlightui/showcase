<native:column class="w-full h-full bg-theme-background">
    <firstlight:bottom-sheet
        ref="captureSheet"
        :visible="$visible"
        a11y-label="Filters"
        @dismiss="dismiss"
    >
        <native:column class="w-full p-4 gap-3">
            <firstlight:status-label label="Filters" />
            <native:text class="text-sm text-theme-on-surface">Unread only</native:text>
        </native:column>
    </firstlight:bottom-sheet>
</native:column>

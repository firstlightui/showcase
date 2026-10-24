<native:column class="w-full h-full bg-theme-background">
    <firstlight:modal
        ref="captureModal"
        :visible="$visible"
        a11y-label="Account details"
        @dismiss="dismiss"
    >
        <native:column class="w-full p-4 gap-3">
            <native:text class="text-lg font-semibold text-theme-on-surface">Account details</native:text>
            <native:text class="text-sm text-theme-on-surface">Signed in as Alex</native:text>
        </native:column>
    </firstlight:modal>
</native:column>

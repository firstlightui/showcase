<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 pb-24 gap-5">
        <native:text class="text-sm font-semibold text-theme-on-surface">On-device checks</native:text>
        <native:text class="text-sm text-theme-on-surface-variant">Native close increments dismissed once. In the programmatic example, tap Close from PHP: dismissed stays unchanged. Reopen and use native close: dismissed increases. Required notice stays open until Acknowledge.</native:text>
        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Default overlay</native:text>
            <native:button ref="showDefault" label="Open account details" variant="primary" @press="showDefault" />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Required notice</native:text>
            <native:button ref="showLocked" label="Open required notice" variant="secondary" @press="showLocked" />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Programmatic reconciliation</native:text>
            <native:button ref="showProgrammatic" label="Open programmatic example" variant="secondary" @press="showProgrammatic" />
            <native:text class="text-sm text-theme-on-surface-variant">{{ $lastOutcome }} · {{ $dismissalCount }} dismissed</native:text>
        </native:column>

        <firstlight:modal
            ref="defaultModal"
            :visible="$defaultVisible"
            a11y-label="Account details"
            @dismiss="dismissDefault"
        >
            <native:column class="w-full p-4 gap-3">
                <firstlight:status-label label="Account details" />
                <native:text class="text-sm text-theme-on-surface">Use the native close control to dismiss this overlay.</native:text>
            </native:column>
        </firstlight:modal>

        <firstlight:modal
            ref="lockedModal"
            :visible="$lockedVisible"
            :dismissible="false"
            a11y-label="Required notice"
            @dismiss="dismissLocked"
        >
            <native:column class="w-full p-4 gap-3">
                <firstlight:status-label label="Required notice" />
                <firstlight:button ref="acknowledgeLocked" label="Acknowledge" variant="primary" @press="acknowledgeLocked" />
            </native:column>
        </firstlight:modal>

        <firstlight:modal
            ref="programmaticModal"
            :visible="$programmaticVisible"
            a11y-label="Programmatic closure"
            @dismiss="dismissProgrammatic"
        >
            <native:column class="w-full p-4 gap-3">
                <firstlight:status-label label="Programmatic closure" />
                <native:text class="text-sm text-theme-on-surface">Close from PHP should not record a native dismissal.</native:text>
                <firstlight:button ref="closeProgrammatic" label="Close from PHP" variant="primary" @press="closeProgrammatic" />
            </native:column>
        </firstlight:modal>
    </native:column>
</native:scroll-view>

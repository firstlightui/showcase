<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 pb-24 gap-5">
        <native:text class="text-sm font-semibold text-theme-on-surface">On-device checks</native:text>
        <native:text class="text-sm text-theme-on-surface-variant">Drag down or tap outside: dismissed increases once. In the programmatic example, tap Close from PHP: dismissed stays unchanged. Reopen and dismiss natively: dismissed increases.</native:text>
        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Default sheet</native:text>
            <native:button ref="showDefault" label="Open filters" variant="primary" @press="showDefault" />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Programmatic reconciliation</native:text>
            <native:button ref="showProgrammatic" label="Open programmatic example" variant="secondary" @press="showProgrammatic" />
            <native:text class="text-sm text-theme-on-surface-variant">{{ $lastOutcome }} · {{ $dismissalCount }} dismissed</native:text>
        </native:column>

        <firstlight:bottom-sheet
            ref="defaultSheet"
            :visible="$defaultVisible"
            a11y-label="Filters"
            @dismiss="dismissDefault"
        >
            <native:column class="w-full p-4 gap-3">
                <firstlight:status-label label="Filters" />
                <native:text class="text-sm text-theme-on-surface">Drag down or tap outside to dismiss this sheet.</native:text>
            </native:column>
        </firstlight:bottom-sheet>

        <firstlight:bottom-sheet
            ref="programmaticSheet"
            :visible="$programmaticVisible"
            a11y-label="Programmatic closure"
            @dismiss="dismissProgrammatic"
        >
            <native:column class="w-full p-4 gap-3">
                <firstlight:status-label label="Programmatic closure" />
                <native:text class="text-sm text-theme-on-surface">Close from PHP should not record a native dismissal.</native:text>
                <firstlight:button ref="closeProgrammatic" label="Close from PHP" variant="primary" @press="closeProgrammatic" />
            </native:column>
        </firstlight:bottom-sheet>
    </native:column>
</native:scroll-view>

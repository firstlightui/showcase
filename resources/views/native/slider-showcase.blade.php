<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-5">
        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Synchronization policies</native:text>
            <firstlight:slider
                ref="liveDose"
                native:model="liveDose"
                :min="0"
                :max="10"
                label="Live dose"
                helper="Publishes every changed whole-number step."
                a11y-value="5 milligrams"
            />
            <firstlight:slider
                ref="blurTemperature"
                native:model.blur="blurTemperature"
                :min="-1.5"
                :max="1.5"
                :step="0.25"
                label="Blur temperature offset"
                helper="Keeps a fractional native draft until release."
            />
            <firstlight:slider
                ref="debouncedRatio"
                native:model.debounce.500ms="debouncedRatio"
                :min="0"
                :max="1"
                :step="0.1"
                label="Debounced ratio"
                helper="Publishes after 500 ms of quiet and flushes on release."
            />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Validation and state</native:text>
            <firstlight:slider
                :value="4"
                :min="0"
                :max="10"
                label="Dose with error"
                error="Dose needs clinical review."
            />
            <firstlight:slider
                :value="7"
                :min="0"
                :max="10"
                label="Disabled dose"
                helper="Dose changes are unavailable."
                disabled
            />
        </native:column>

        <native:column class="w-full gap-2 pb-6">
            <native:text class="text-sm font-semibold text-theme-on-surface">Reconciliation</native:text>
            <firstlight:slider
                ref="rejectedDose"
                :value="$approvedDose"
                :min="0"
                :max="10"
                label="Server-approved dose"
                helper="New proposals are deliberately rejected by PHP."
                @change="rejectDose"
            />
            <firstlight:slider
                ref="programmaticDose"
                native:model="programmaticDose"
                :min="0"
                :max="10"
                label="Programmatic dose"
                a11y-hint="Use the button below to replace the published value"
            />
            <native:button label="Publish higher dose" variant="secondary" @press="publishHigherDose" />
        </native:column>
    </native:column>
</native:scroll-view>

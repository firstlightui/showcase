<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-5 pb-8">
        <native:text class="text-sm text-theme-on-surface">On-device checks: step whole and quarter values. Minimum can increase and maximum can decrease; bounds block the opposite action. Disabled stays fixed. Rejected proposals increment the count without changing 4; publish changes to 8.</native:text>
        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Exact numeric models</native:text>
            <firstlight:stepper
                native:model="quantity"
                :min="0"
                :max="10"
                label="Quantity"
                helper="Publishes exact PHP integers one item at a time."
            />
            <firstlight:stepper
                native:model="fractionalDose"
                :min="0.0"
                :max="1.0"
                :step="0.25"
                label="Fractional dose"
                helper="Preserves an exact PHP float proposal."
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $quantity }}; fractional: {{ $fractionalDose }}</native:text>
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Bounds and validation</native:text>
            <native:text class="text-sm text-theme-on-surface">Static error presentation example; editing does not clear the message. Use Validation for real rules.</native:text>
            <firstlight:stepper
                ref="minimumQuantity"
                native:model="minimumQuantity"
                :min="0"
                :max="10"
                label="Minimum quantity"
                helper="Decrease is unavailable at the lower bound."
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $minimumQuantity }}</native:text>
            <firstlight:stepper
                ref="maximumQuantity"
                native:model="maximumQuantity"
                :min="0"
                :max="10"
                label="Maximum quantity"
                helper="Increase is unavailable at the upper bound."
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $maximumQuantity }}</native:text>
            <firstlight:stepper
                ref="errorQuantity"
                native:model="errorQuantity"
                :min="0"
                :max="10"
                label="Quantity with error"
                error="Quantity needs clinical review."
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $errorQuantity }}</native:text>
            <firstlight:stepper
                :value="7"
                :min="0"
                :max="10"
                label="Disabled quantity"
                helper="Quantity changes are unavailable."
                disabled
            />
        </native:column>

        <native:column class="w-full gap-2 pb-6">
            <native:text class="text-sm font-semibold text-theme-on-surface">Reconciliation</native:text>
            <firstlight:stepper
                :value="$approvedQuantity"
                :min="0"
                :max="10"
                label="Server-approved quantity"
                helper="New proposals are deliberately rejected by PHP."
                @change="rejectQuantity"
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $approvedQuantity }}; Rejected attempts: {{ $rejectedAttempts }}</native:text>
            <firstlight:stepper
                native:model="programmaticQuantity"
                :min="0"
                :max="10"
                label="Programmatic quantity"
                a11y-hint="Use the button below to replace the published value"
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $programmaticQuantity }}</native:text>
            <native:button label="Publish higher quantity" variant="secondary" @press="publishHigherQuantity" />
        </native:column>
    </native:column>
</native:scroll-view>

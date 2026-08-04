<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-5">
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
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Bounds and validation</native:text>
            <firstlight:stepper
                :value="0"
                :min="0"
                :max="10"
                label="Minimum quantity"
                helper="Decrease is unavailable at the lower bound."
            />
            <firstlight:stepper
                :value="10"
                :min="0"
                :max="10"
                label="Maximum quantity"
                helper="Increase is unavailable at the upper bound."
            />
            <firstlight:stepper
                :value="4"
                :min="0"
                :max="10"
                label="Quantity with error"
                error="Quantity needs clinical review."
            />
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
            <firstlight:stepper
                native:model="programmaticQuantity"
                :min="0"
                :max="10"
                label="Programmatic quantity"
                a11y-hint="Use the button below to replace the published value"
            />
            <native:button label="Publish higher quantity" variant="secondary" @press="publishHigherQuantity" />
        </native:column>
    </native:column>
</native:scroll-view>

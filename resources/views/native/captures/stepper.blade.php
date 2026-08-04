<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-5 gap-6">
        <firstlight:stepper
            native:model="quantity"
            :min="0"
            :max="10"
            label="Medication quantity"
            helper="Whole-item increments."
        />

        <firstlight:stepper
            :value="0.5"
            :min="0.0"
            :max="1.0"
            :step="0.25"
            label="Fractional dose"
            helper="Quarter-step float grid."
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
</native:scroll-view>

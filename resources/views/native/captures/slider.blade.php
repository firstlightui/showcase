<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-5 gap-6">
        <firstlight:slider
            native:model="dose"
            :min="0"
            :max="10"
            label="Medication dose"
            helper="Whole milligram increments."
            a11y-value="5 milligrams"
        />

        <firstlight:slider
            :value="0.25"
            :min="-1.5"
            :max="1.5"
            :step="0.25"
            label="Temperature offset"
            helper="Fractional grid across a negative range."
        />

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
</native:scroll-view>

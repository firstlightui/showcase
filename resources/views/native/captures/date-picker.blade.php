<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-5 gap-6">
        <firstlight:date-picker
            native:model="appointmentDate"
            label="Appointment date"
            placeholder="Choose a date"
            helper="Uses the clinic's local calendar."
            locale="en-AU"
            timezone="Australia/Sydney"
        />

        <firstlight:date-picker
            value="2026-08-04"
            label="Review date"
            helper="Choose a date during August 2026."
            min="2026-08-01"
            max="2026-08-31"
            required
        />

        <firstlight:date-picker
            label="Discharge date"
            placeholder="Choose a discharge date"
            error="Choose a discharge date."
            required
        />

        <firstlight:date-picker
            value="2026-08-20"
            label="Disabled date"
            disabled
        />
    </native:column>
</native:scroll-view>

<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-5 gap-6">
        <firstlight:time-picker
            native:model="appointmentTime"
            label="Appointment time"
            placeholder="Choose a time"
            helper="Uses the clinic's local clock."
            locale="en-AU"
            timezone="Australia/Sydney"
        />

        <firstlight:time-picker
            value="14:30"
            label="Review time"
            helper="A required accepted wall-clock time."
            required
        />

        <firstlight:time-picker
            label="Arrival time"
            placeholder="Choose an arrival time"
            error="Choose an arrival time."
            required
        />

        <firstlight:time-picker
            value="20:15"
            label="Disabled time"
            disabled
        />
    </native:column>
</native:scroll-view>

<native:column class="w-full h-full bg-theme-background">
    <firstlight:confirmation-dialog
        ref="captureDialog"
        :visible="$visible"
        title="Delete appointment?"
        message="This removes the appointment and cannot be undone."
        confirm-label="Delete appointment"
        cancel-label="Keep appointment"
        tone="destructive"
        @press="confirm"
        @dismiss="dismiss"
    />
</native:column>

<native:column class="w-full h-full bg-theme-background">
    <firstlight:alert-dialog
        ref="captureDialog"
        :visible="$visible"
        title="Changes saved"
        message="Your profile was updated."
        action-label="OK"
        @dismiss="acknowledge"
    />
</native:column>

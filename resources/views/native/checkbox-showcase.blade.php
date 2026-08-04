<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-5">
        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Checked state</native:text>
            <firstlight:checkbox ref="unchecked" native:model="unchecked" label="Unchecked" />
            <firstlight:checkbox ref="checkedRequired" native:model="checkedRequired" label="Checked and required" required />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Disabled</native:text>
            <firstlight:checkbox ref="disabledUnchecked" :value="$disabledUnchecked" label="Disabled unchecked" disabled />
            <firstlight:checkbox ref="disabledChecked" :value="$disabledChecked" label="Disabled checked" disabled />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Field feedback</native:text>
            <firstlight:checkbox
                ref="helperText"
                native:model="helper"
                label="Helper text"
                helper="Required before continuing."
            />
            <firstlight:checkbox
                ref="validationError"
                native:model="error"
                label="Validation error"
                error="Agreement is required before continuing."
                required
                a11y-hint="Resolve the agreement error"
            />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Server-authoritative state</native:text>
            <firstlight:checkbox
                ref="rejectedCheckbox"
                :value="$rejected"
                @change="rejectCheckbox"
                label="Rejected agreement"
                helper="Every proposed value is rejected by the server."
            />
            <firstlight:checkbox
                ref="programmaticCheckbox"
                native:model="programmatic"
                label="Programmatic agreement"
                helper="Use the buttons below to publish accepted state."
            />
            <native:row class="gap-3">
                <native:button ref="acceptProgrammaticAgreement" label="Accept programmatic agreement" variant="primary" @press="acceptProgrammaticAgreement" />
                <native:button ref="resetProgrammaticAgreement" label="Reset programmatic agreement" variant="secondary" @press="resetProgrammaticAgreement" />
            </native:row>
        </native:column>

        <native:column class="w-full gap-3 pb-6">
            <native:text class="text-sm font-semibold text-theme-on-surface">Long label</native:text>
            <firstlight:checkbox
                ref="longLabel"
                native:model="longLabel"
                label="A considerably longer agreement label that wraps naturally across multiple lines"
            />
        </native:column>
    </native:column>
</native:scroll-view>

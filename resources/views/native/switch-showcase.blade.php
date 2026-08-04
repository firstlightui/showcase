<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-5">
        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">On and off</native:text>
            <firstlight:switch ref="notificationsOff" native:model="notificationsOff" label="Notifications off" />
            <firstlight:switch ref="notificationsOn" native:model="notificationsOn" label="Notifications on" />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Disabled</native:text>
            <firstlight:switch ref="disabledOff" :value="$disabledOff" label="Disabled off" disabled />
            <firstlight:switch ref="disabledOn" :value="$disabledOn" label="Disabled on" disabled />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Field feedback</native:text>
            <firstlight:switch
                ref="helperText"
                native:model="helper"
                label="Helper text"
                helper="Receive updates when this setting is enabled."
            />
            <firstlight:switch
                ref="validationError"
                native:model="error"
                label="Validation error"
                error="Choose whether updates can be sent."
            />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Server-authoritative state</native:text>
            <firstlight:switch
                ref="rejectedSwitch"
                :value="$rejected"
                @change="rejectSwitch"
                label="Rejected setting"
                helper="Every proposed value is rejected by the server."
            />
            <firstlight:switch
                ref="programmaticSwitch"
                native:model="programmatic"
                label="Programmatic setting"
                helper="Use the buttons below to change this setting on the server."
            />
            <native:row class="gap-3">
                <native:button ref="enableProgrammaticSwitch" label="Enable programmatic setting" variant="primary" @press="enableProgrammaticSwitch" />
                <native:button ref="resetProgrammaticSwitch" label="Reset programmatic setting" variant="secondary" @press="resetProgrammaticSwitch" />
            </native:row>
        </native:column>

        <native:column class="w-full gap-3 pb-6">
            <native:text class="text-sm font-semibold text-theme-on-surface">Long label</native:text>
            <firstlight:switch
                ref="longLabel"
                native:model="longLabel"
                label="A considerably longer setting label that wraps naturally"
            />
        </native:column>
    </native:column>
</native:scroll-view>

<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-5 pb-8">
        <native:text class="text-sm text-theme-on-surface">On-device checks: toggle enabled switches and compare PHP state. Disabled values stay fixed. Rejected setting stays false while attempts increase. Programmatic buttons set true then false. The error under Field feedback is a static presentation example; turning Share records off shows a real Laravel message bound by ValidatesFields.</native:text>
        <native:text class="text-sm text-theme-on-surface">PHP accepted state: off example {{ $notificationsOff ? 'true' : 'false' }}; on example {{ $notificationsOn ? 'true' : 'false' }}; helper {{ $helper ? 'true' : 'false' }}; error {{ $error ? 'true' : 'false' }}; long label {{ $longLabel ? 'true' : 'false' }}; programmatic {{ $programmatic ? 'true' : 'false' }}; rejected {{ $rejected ? 'true' : 'false' }}; rejected attempts {{ $rejectedAttempts }}; unlabelled {{ $unlabelledReminders ? 'true' : 'false' }}; share records {{ $shareRecords ? 'true' : 'false' }}</native:text>
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

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Accessibility name and hint</native:text>
            <native:text class="text-sm text-theme-on-surface">This switch has no visible label. VoiceOver and TalkBack read its a11y-label, Appointment reminders, followed by its a11y-hint.</native:text>
            <firstlight:switch
                ref="unlabelledReminders"
                native:model="unlabelledReminders"
                a11y-label="Appointment reminders"
                a11y-hint="Sends a reminder the day before each appointment"
            />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Laravel validation</native:text>
            <native:text class="text-sm text-theme-on-surface">Turn this off: PHP accepts false, validateOnly fails the accepted rule, and the message binds to this switch without an error attribute. Turn it back on to clear it.</native:text>
            <firstlight:switch
                ref="shareRecords"
                native:model="shareRecords"
                label="Share records"
            />
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

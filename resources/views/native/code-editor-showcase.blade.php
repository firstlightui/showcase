<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-5 pb-32">
        <native:text class="text-sm text-theme-on-surface">On-device checks: Return keeps indentation; in JSON it adds a level after { or [ and splits {} pairs, and typing } outdents. YAML indents after a trailing colon. The key row sits above the keyboard (hidden with a hardware keyboard, where Tab and Shift-Tab indent); Undo and Redo reverse key presses. Line numbers stay silent to VoiceOver and TalkBack. The live log stays at the newest line until you scroll up, then holds your place while lines arrive and old lines are trimmed.</native:text>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Languages and synchronisation</native:text>
            <firstlight:code-editor
                ref="payload"
                native:model="payload"
                language="json"
                label="Webhook payload"
                helper="Validated as JSON on every change."
                :error="$this->payloadError()"
                :required="true"
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $payload }}</native:text>
            <firstlight:code-editor
                ref="workflow"
                native:model.blur="workflow"
                language="yaml"
                label="Deploy workflow"
                helper="Publishes when focus leaves the editor."
                :indent="2"
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $workflow }} (blur)</native:text>
            <firstlight:code-editor
                ref="environment"
                native:model.debounce.500ms="environment"
                language="env"
                label="Environment"
                helper="One KEY=value per line. Not a secure field."
                :min-lines="4"
                :max-lines="10"
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $environment }} (debounced)</native:text>
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Layout and non-editable states</native:text>
            <firstlight:code-editor
                :value="$wide"
                language="json"
                label="No-wrap JSON"
                :wrap="false"
                :indent="4"
                :min-lines="3"
                :max-lines="6"
            />
            <firstlight:code-editor
                :value="$environment"
                language="env"
                label="Read-only environment"
                helper="Selection and copy remain native."
                :line-numbers="false"
                :read-only="true"
                :min-lines="3"
                :max-lines="6"
            />
            <firstlight:code-editor
                value="Editing is unavailable."
                label="Disabled editor"
                :disabled="true"
                :min-lines="2"
                :max-lines="4"
            />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Programmatic replacement</native:text>
            <firstlight:code-editor
                ref="programmatic"
                native:model="programmatic"
                language="json"
                label="Programmatic JSON"
                a11y-hint="Use the buttons below to replace the published value"
                :min-lines="4"
                :max-lines="8"
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $programmatic }}</native:text>
            <native:row class="w-full gap-2">
                <native:button label="Replace JSON" variant="secondary" @press="replaceProgrammatic" />
                <native:button label="Reset JSON" variant="ghost" @press="resetProgrammatic" />
            </native:row>
        </native:column>

        <native:column class="w-full gap-2 pb-6">
            <native:text class="text-sm font-semibold text-theme-on-surface">Live log (follow)</native:text>
            <firstlight:code-editor
                ref="log"
                :value="$log"
                label="Deployment log"
                helper="Capped at the last 2,000 lines."
                :read-only="true"
                :follow="true"
                :wrap="false"
                :min-lines="8"
                :max-lines="16"
            />
            <native:text class="text-sm text-theme-on-surface">Lines written: {{ $logLines }}</native:text>
            <native:row class="w-full gap-2">
                <native:button label="Append 25 lines" variant="secondary" @press="appendLogLines" />
                <native:button label="Fill 2,000 lines" variant="secondary" @press="fillLog" />
                <native:button label="Clear log" variant="ghost" @press="clearLog" />
            </native:row>
        </native:column>
    </native:column>
</native:scroll-view>

<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-5 pb-32">
        <native:text class="text-sm text-theme-on-surface">On-device checks: enter multiple lines; live notes update PHP while typing, handover on blur, summary after 500 ms quiet. Read-only permits copy, disabled cannot edit. Rejected edits keep approved wording and count attempts. Replace/reset must update the editor and PHP.</native:text>
        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Live multiline notes</native:text>
            <firstlight:text-area
                ref="notes"
                native:model="notes"
                label="Clinical notes"
                placeholder="Add relevant history and observations"
                helper="Relevant details only."
                :required="true"
                :min-lines="4"
                :max-lines="8"
                autocapitalize="sentences"
                :autocorrect="true"
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $notes }}</native:text>
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Deferred synchronisation</native:text>
            <firstlight:text-area
                ref="handover"
                native:model.blur="handover"
                label="Handover note"
                placeholder="Add a note for the next clinician"
                helper="Publishes when focus leaves the editor."
                :min-lines="3"
                :max-lines="6"
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $handover ?: '(empty)' }} (blur)</native:text>
            <firstlight:text-area
                ref="summary"
                native:model.debounce.500ms="summary"
                label="Debounced summary"
                helper="Publishes after a quiet period."
                :min-lines="3"
                :max-lines="5"
                autocapitalize="sentences"
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $summary }} (debounced)</native:text>
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Validation and non-editable states</native:text>
            <native:text class="text-sm text-theme-on-surface">Static error presentation example; editing does not clear the message. Use Validation for real rules.</native:text>
            <firstlight:text-area
                native:model="errorNotes"
                label="Note with error"
                error="Add at least one observation."
                :min-lines="3"
                :max-lines="5"
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $errorNotes }}</native:text>
            <firstlight:text-area
                value="This note is available for selection and copy."
                label="Read-only note"
                helper="Selection and scrolling remain native."
                read-only
            />
            <firstlight:text-area
                value="Editing is unavailable."
                label="Disabled note"
                disabled
            />
        </native:column>

        <native:column class="w-full gap-2 pb-6">
            <native:text class="text-sm font-semibold text-theme-on-surface">Reconciliation</native:text>
            <firstlight:text-area
                ref="rejectedNotes"
                :value="$approved"
                label="Server-approved note"
                helper="Edits are deliberately rejected by PHP."
                @change="rejectNotes"
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $approved }}; Rejected attempts: {{ $rejectedAttempts }}</native:text>
            <firstlight:text-area
                ref="programmaticNotes"
                native:model="programmatic"
                label="Programmatic note"
                a11y-hint="Use the buttons below to replace the published value"
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $programmatic }}</native:text>
            <native:row class="w-full gap-2">
                <native:button label="Replace note" variant="secondary" @press="replaceProgrammaticNotes" />
                <native:button label="Reset note" variant="ghost" @press="resetProgrammaticNotes" />
            </native:row>
        </native:column>
    </native:column>
</native:scroll-view>

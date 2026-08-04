<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-5">
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
            <firstlight:text-area
                ref="summary"
                native:model.debounce.500ms="summary"
                label="Debounced summary"
                helper="Publishes after a quiet period."
                :min-lines="3"
                :max-lines="5"
                autocapitalize="sentences"
            />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Validation and non-editable states</native:text>
            <firstlight:text-area
                value="Incomplete note"
                label="Note with error"
                error="Add at least one observation."
                :min-lines="3"
                :max-lines="5"
            />
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
            <firstlight:text-area
                ref="programmaticNotes"
                native:model="programmatic"
                label="Programmatic note"
                a11y-hint="Use the buttons below to replace the published value"
            />
            <native:row class="w-full gap-2">
                <native:button label="Replace note" variant="secondary" @press="replaceProgrammaticNotes" />
                <native:button label="Reset note" variant="ghost" @press="resetProgrammaticNotes" />
            </native:row>
        </native:column>
    </native:column>
</native:scroll-view>

<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-5 gap-6">
        <firstlight:text-area
            native:model="notes"
            label="Clinical notes"
            helper="Relevant details only."
            :required="true"
            :min-lines="4"
            :max-lines="8"
            autocapitalize="sentences"
        />

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
    </native:column>
</native:scroll-view>

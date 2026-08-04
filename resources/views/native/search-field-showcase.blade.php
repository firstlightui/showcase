<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-5">
        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Live query and native clear</native:text>
            <firstlight:search-field
                native:model="query"
                placeholder="Search referrals"
                a11y-label="Search referrals"
                a11y-hint="Enter a patient, provider, or specialty"
                autocapitalize="words"
                :autocorrect="false"
                @submit="submitQuery"
            />
            <native:button label="Reset query" variant="secondary" @press="resetQuery" />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Debounced query</native:text>
            <firstlight:search-field
                native:model.debounce.500ms="debouncedQuery"
                placeholder="Search clinicians"
                a11y-label="Search clinicians"
                autocapitalize="words"
                @submit="submitQuery"
            />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Blur synchronisation</native:text>
            <firstlight:search-field
                native:model.blur="blurQuery"
                placeholder="Search specialties"
                a11y-label="Search specialties"
                @submit="submitQuery"
            />
        </native:column>

        <native:column class="w-full gap-2 pb-6">
            <native:text class="text-sm font-semibold text-theme-on-surface">Disabled search</native:text>
            <firstlight:search-field
                value="Archived referral"
                placeholder="Search archive"
                a11y-label="Search archived referrals"
                disabled
            />
        </native:column>
    </native:column>
</native:scroll-view>

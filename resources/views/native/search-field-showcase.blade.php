<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-5 pb-8">
        <native:text class="text-sm text-theme-on-surface">On-device checks: type/clear live query, then reset to Referral. Debounced query updates PHP after 500 ms quiet; blur query on leaving focus. Submit each keyboard Search action: last submitted text and count must update. Lazy query updates on leaving focus or submit. Rejected query stays Ward 4 and counts attempts. Disabled search cannot edit or submit.</native:text>
        <native:text class="text-sm text-theme-on-surface">Last submitted: {{ $lastSubmitted ?: '(none)' }}; submissions: {{ $submitCount }}</native:text>
        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Live query and native clear</native:text>
            <firstlight:search-field
                ref="liveSearch"
                native:model="query"
                placeholder="Search referrals"
                a11y-label="Search referrals"
                a11y-hint="Enter a patient, provider, or specialty"
                autocapitalize="words"
                :autocorrect="false"
                @submit="submitQuery"
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $query ?: '(empty)' }} (live)</native:text>
            <native:button label="Reset query" variant="secondary" @press="resetQuery" />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Debounced query</native:text>
            <firstlight:search-field
                ref="debouncedSearch"
                native:model.debounce.500ms="debouncedQuery"
                placeholder="Search clinicians"
                a11y-label="Search clinicians"
                autocapitalize="words"
                @submit="submitQuery"
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $debouncedQuery ?: '(empty)' }} (debounced)</native:text>
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Blur synchronisation</native:text>
            <firstlight:search-field
                ref="blurSearch"
                native:model.blur="blurQuery"
                placeholder="Search specialties"
                a11y-label="Search specialties"
                @submit="submitQuery"
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $blurQuery ?: '(empty)' }} (blur)</native:text>
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Lazy synchronisation</native:text>
            <native:text class="text-sm text-theme-on-surface">Lazy publishes only on focus loss or submit.</native:text>
            <firstlight:search-field
                ref="lazySearch"
                native:model.lazy="lazyQuery"
                placeholder="Search wards"
                a11y-label="Search wards"
                @submit="submitQuery"
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $lazyQuery ?: '(empty)' }} (lazy)</native:text>
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Server rejection</native:text>
            <native:text class="text-sm text-theme-on-surface">PHP rejects every query edit, including clear: the field shows Ward 4 again and the count rises.</native:text>
            <firstlight:search-field
                :value="$approvedQuery"
                placeholder="Search approved wards"
                a11y-label="Search approved wards"
                @change="rejectQuery"
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $approvedQuery }}; Rejected queries: {{ $rejectedQueries }}</native:text>
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

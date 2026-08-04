<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-5 gap-6">
        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Populated search</native:text>
            <firstlight:search-field
                native:model="query"
                placeholder="Search referrals"
                a11y-label="Search referrals"
                autocapitalize="words"
                :autocorrect="false"
            />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Empty search</native:text>
            <firstlight:search-field
                value=""
                placeholder="Search clinicians"
                a11y-label="Search clinicians"
            />
        </native:column>

        <native:column class="w-full gap-2">
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

<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-5 gap-5">
        <native:text class="text-lg font-semibold text-theme-on-surface">Document upload</native:text>

        <native:column class="w-full gap-2">
            <native:text class="text-sm text-theme-on-surface">Not started · 0%</native:text>
            <firstlight:progress :value="0" a11y-label="Document upload not started" />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm text-theme-on-surface">Preparing · 25%</native:text>
            <firstlight:progress :value="0.25" a11y-label="Preparing document upload" />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm text-theme-on-surface">Uploading · 50%</native:text>
            <firstlight:progress :value="0.5" a11y-label="Uploading documents" />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm text-theme-on-surface">Processing · 75%</native:text>
            <firstlight:progress :value="0.75" a11y-label="Processing uploaded documents" />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm text-theme-on-surface">Complete · 100%</native:text>
            <firstlight:progress :value="1" a11y-label="Document upload complete" />
        </native:column>

    </native:column>
</native:scroll-view>

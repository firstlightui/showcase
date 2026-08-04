<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-5">
        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Determinate progress</native:text>

            <native:column class="w-full gap-2">
                <native:text class="text-sm text-theme-on-surface">Not started · 0%</native:text>
                <firstlight:progress :value="0" a11y-label="Upload not started" />
            </native:column>

            <native:column class="w-full gap-2">
                <native:text class="text-sm text-theme-on-surface">Uploading · 42%</native:text>
                <firstlight:progress :value="0.42" a11y-label="Uploading documents" />
            </native:column>

            <native:column class="w-full gap-2">
                <native:text class="text-sm text-theme-on-surface">Nearly complete · 87%</native:text>
                <firstlight:progress :value="0.87" a11y-label="Upload nearly complete" />
            </native:column>

            <native:column class="w-full gap-2">
                <native:text class="text-sm text-theme-on-surface">Complete · 100%</native:text>
                <firstlight:progress :value="1" a11y-label="Upload complete" />
            </native:column>
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Indeterminate progress</native:text>
            <firstlight:progress indeterminate a11y-label="Preparing documents" />
            <firstlight:progress a11y-label="Checking document status" />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Long accessible context</native:text>
            <native:text class="text-sm text-theme-on-surface">Synchronising the complete historical document archive for offline access</native:text>
            <firstlight:progress a11y-label="Synchronising the complete historical document archive for offline access" />
        </native:column>

        <native:column class="w-full gap-3 pb-6">
            <native:text class="text-sm font-semibold text-theme-on-surface">Programmatic updates · {{ (int) ($uploadProgress * 100) }}%</native:text>
            <firstlight:progress :value="$uploadProgress" a11y-label="Interactive upload progress" />
            <firstlight:button label="Advance progress" @press="advanceProgress" />
            <firstlight:button label="Reset progress" variant="ghost" @press="resetProgress" />
        </native:column>
    </native:column>
</native:scroll-view>

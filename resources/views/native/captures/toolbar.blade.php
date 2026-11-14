<native:column class="w-full flex-1 bg-theme-background">
    <native:scroll-view class="w-full flex-1">
        <native:column class="w-full p-5 gap-4">
            <native:text class="text-lg font-semibold text-theme-on-surface">Weekend camping</native:text>
            <native:text class="text-base text-theme-on-surface">Pack the tent, two sleeping bags and the camp stove.</native:text>
            <native:text class="text-base text-theme-on-surface">Check the forecast on Thursday evening before leaving.</native:text>
            <native:text class="text-base text-theme-on-surface">Fill both water containers and bring the filter.</native:text>
            <native:text class="text-base text-theme-on-surface">Leave the trail plan with someone at home.</native:text>
            <native:text class="text-base text-theme-on-surface">Charge the head torches and the spare battery.</native:text>
        </native:column>
    </native:scroll-view>

    <firstlight:toolbar>
        <firstlight:toolbar-item icon="checklist" a11y-label="Checklist" @press="capturePress" />
        <firstlight:toolbar-item icon="paperclip" icon-android="attach_file" a11y-label="Attach" @press="capturePress" />
        <firstlight:toolbar-item icon="pencil.tip.crop.circle" icon-android="draw" a11y-label="Markup" @press="capturePress" />
        <firstlight:toolbar-item primary icon="square.and.pencil" icon-android="edit_square" a11y-label="New note" @press="capturePress" />
    </firstlight:toolbar>
</native:column>

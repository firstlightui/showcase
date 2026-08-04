<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-5">
        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Semantic variants</native:text>
            <firstlight:button @press="recordPress">Primary action</firstlight:button>
            <firstlight:button variant="secondary" @press="recordPress">Secondary action</firstlight:button>
            <firstlight:button variant="destructive" @press="recordPress">Delete draft</firstlight:button>
            <firstlight:button variant="success" @press="recordPress">Confirm selection</firstlight:button>
            <firstlight:button variant="ghost" @press="recordPress">Ghost action</firstlight:button>
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Native sizes</native:text>
            <firstlight:button size="sm" @press="recordPress">Small</firstlight:button>
            <firstlight:button size="md" @press="recordPress">Medium</firstlight:button>
            <firstlight:button size="lg" @press="recordPress">Large</firstlight:button>
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Icons</native:text>
            <firstlight:button icon="plus" @press="recordPress">Add item</firstlight:button>
            <firstlight:button icon-trailing="chevron-right" @press="recordPress">Continue</firstlight:button>
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">States</native:text>
            <firstlight:button label="Unavailable" disabled />
            <firstlight:button label="Saving changes" loading a11y-hint="Saving is in progress" />
        </native:column>

        <native:column class="w-full gap-3 pb-6">
            <native:text class="text-sm font-semibold text-theme-on-surface">Interaction</native:text>
            <firstlight:button variant="primary" @press="recordPress">Record press</firstlight:button>
            <native:text class="text-sm text-theme-on-surface">Recorded presses: {{ $pressCount }}</native:text>
        </native:column>
    </native:column>
</native:scroll-view>

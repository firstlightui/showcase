<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 pb-8 gap-5">
        <native:text class="text-sm font-semibold text-theme-on-surface">On-device checks</native:text>
        <native:text class="text-sm text-theme-on-surface-variant">Every icon on this screen except the bell is an SVG in resources/icons, installed with php artisan firstlight:icon and drawn natively as custom:name. Check that each one takes its control's colour in light and dark appearances and keeps the size of the system icons beside it. Jump builds have no custom icons, so there they show system icons instead.</native:text>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Icon Button and Button</native:text>
            <native:row class="w-full gap-3 flex-wrap">
                <firstlight:icon-button icon="custom:rocket-launch-solid" a11y-label="Launch" @press="recordPress('Launch')" />
                <firstlight:icon-button icon="custom:mug-hot" variant="secondary" a11y-label="Take a break" @press="recordPress('Take a break')" />
                <firstlight:icon-button icon="custom:git-commit-vertical" variant="ghost" a11y-label="Commit" @press="recordPress('Commit')" />
                <firstlight:icon-button icon="plus" a11y-label="System add" @press="recordPress('System add')" />
            </native:row>
            <firstlight:button label="Commit changes" icon="custom:git-commit-vertical" variant="ghost" @press="recordPress('Commit changes')" />
            <native:text class="text-sm text-theme-on-surface">Last action: {{ $lastAction }}</native:text>
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">List Item</native:text>
            <firstlight:list-item
                headline="Greenhouse"
                supporting="Custom leading and trailing icons"
                leading-icon="custom:plant-2"
                leading-icon-tone="success"
                trailing-icon="custom:git-commit-vertical"
                @press="recordPress('Greenhouse')"
            />
            <firstlight:list-item
                headline="Settings"
                supporting="System icons for comparison"
                leading-icon="settings"
                trailing-icon="chevron-right"
                trailing-icon-ios="chevron.right"
                trailing-icon-android="chevron_right"
                @press="recordPress('Settings')"
            />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Pill Group, Text Field and Status Label</native:text>
            <firstlight:pill-group :options="$filterOptions" native:model="filter" a11y-label="Filter" />
            <firstlight:text-field label="Branch" native:model="branch" leading-icon="custom:git-commit-vertical" />
            <firstlight:status-label label="Launched" tone="success" icon="custom:rocket-launch-solid" />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">One platform only, and a missing icon</native:text>
            <native:text class="text-sm text-theme-on-surface-variant">The first row uses the paw on iOS and Material's pets icon on Android. The bell has no SVG, so both platforms draw the system bell.</native:text>
            <firstlight:list-item headline="Pets" leading-icon="pets" leading-icon-ios="custom:paw-print-fill" @press="recordPress('Pets')" />
            <firstlight:list-item headline="Alerts" leading-icon="custom:bell" @press="recordPress('Alerts')" />
        </native:column>

        <native:text class="text-xs text-theme-on-surface-variant">Icons from Lucide (ISC), Heroicons (MIT), Tabler (MIT), Phosphor (MIT), Material Symbols (Apache 2.0) and Font Awesome Free (CC BY 4.0), through Iconify.</native:text>
    </native:column>
</native:scroll-view>

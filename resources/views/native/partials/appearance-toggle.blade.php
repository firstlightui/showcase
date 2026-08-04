<native:row class="items-center px-3 py-2 rounded-full bg-theme-surface border border-theme-outline-variant shadow-sm">
    <native:toggle
        native:model="darkMode"
        label="Dark mode"
        a11y-label="{{ $darkMode ? 'Preview light appearance' : 'Preview dark appearance' }}"
        a11y-hint="Applies the selected Firstlight palette throughout the interactive showcase"
    />
</native:row>

<native:column class="w-full flex-1 bg-theme-background">
    <native:column class="w-full px-4 py-3 gap-2">
        <firstlight:search-field
            native:model="query"
            placeholder="Find a control or Blade tag"
            a11y-label="Find a demo"
            autocapitalize="none"
            :autocorrect="false"
        />
    </native:column>
    <native:list :shows-indicators="true" class="w-full flex-1">
        <native:list-section header="On-device test run" footer="Use sample data only. These demos are not a production app.">
            <native:text class="text-sm text-theme-on-surface">Open every demo, follow its checks, and compare the control with PHP accepted state. Use native Back to return. Reopen a demo for its initial state. Try rapid input, keyboard dismissal, background/resume, large text and VoiceOver or TalkBack.</native:text>
            <native:text class="text-sm text-theme-on-surface-variant">The floating toggle previews Firstlight colours. Change device appearance separately to test system sheets and keyboards. Disabled examples must stay fixed; rejected examples count attempts without accepting them. Static error examples do not run validation rules.</native:text>
        </native:list-section>
        @forelse ($sections as $section => $demos)
            <native:list-section :header="$section">
                @foreach ($demos as $component)
                    <native:list-item
                        :headline="$component['label']"
                        :overline="$component['tag']"
                        :supporting="$component['description']"
                        trailing-icon="chevron-right"
                        @navigate($component['path'])
                    />
                @endforeach
            </native:list-section>
        @empty
            <native:text class="text-sm text-theme-on-surface">No demos match. Clear search to see every control.</native:text>
        @endforelse
        @if ($query !== '')
            <native:button label="Clear search" variant="secondary" @press="clearSearch" />
        @endif
        <native:text class="text-sm text-theme-on-surface-variant pb-32">{{ $sections->sum(fn ($demos) => $demos->count()) }} of {{ count($components) }} demos</native:text>
    </native:list>
</native:column>

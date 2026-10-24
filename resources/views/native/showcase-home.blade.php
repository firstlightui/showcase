<native:column class="w-full flex-1 bg-theme-background">
    <native:column class="w-full px-4 pt-2 pb-2">
        <firstlight:search-field
            native:model="query"
            placeholder="Search components and Blade tags"
            a11y-label="Search components"
            autocapitalize="none"
            :autocorrect="false"
        />
    </native:column>

    @if ($sections->isEmpty())
        <native:column class="w-full px-4 pt-4 gap-3">
            <firstlight:callout
                message="No component matches that search."
                tone="neutral"
                action-label="Clear search"
                @press="clearSearch"
            />
        </native:column>
    @else
        <firstlight:list class="w-full flex-1 bg-theme-background">
            @foreach ($sections as $section => $demos)
                @if ($loop->last)
                    <firstlight:list-section :header="$section" footer="Each demo opens with its on-device checks for testers. Sample data only.">
                        @include('native.partials.component-rows')
                    </firstlight:list-section>
                @else
                    <firstlight:list-section :header="$section">
                        @include('native.partials.component-rows')
                    </firstlight:list-section>
                @endif
            @endforeach
        </firstlight:list>
    @endif
</native:column>

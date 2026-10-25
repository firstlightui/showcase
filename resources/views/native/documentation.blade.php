<native:column class="w-full flex-1 bg-theme-background">
    <native:column class="w-full px-4 pt-3 pb-2 gap-2">
        <firstlight:search-field
            native:model.debounce.250ms="query"
            a11y-label="Search documentation"
            autocapitalize="none"
            :autocorrect="false"
            placeholder="Find a guide, API or code example"
        />
        <native:text class="text-sm text-theme-on-surface-variant">{{ $count }} articles · Firstlight {{ $version }} · Works offline</native:text>
    </native:column>

    @if ($groups->isEmpty())
        <native:column class="w-full p-4">
            <firstlight:callout message="No articles match your search. Try a component name or a shorter phrase." tone="neutral" action-label="Clear search" @press="clearSearch" />
        </native:column>
    @else
        <firstlight:list class="w-full flex-1 bg-theme-background">
            @foreach ($groups as $group => $articles)
                <firstlight:list-section :header="$group">
                    @foreach ($articles as $article)
                        <firstlight:list-item
                            :headline="$article['title']"
                            :supporting="$article['description']"
                            trailing-icon="chevron-right"
                            trailing-icon-ios="chevron.right"
                            trailing-icon-android="chevron_right"
                            a11y-hint="Read the bundled documentation"
                            @press="openArticle('{{ $article['id'] }}')"
                        />
                    @endforeach
                </firstlight:list-section>
            @endforeach
        </firstlight:list>
    @endif
</native:column>

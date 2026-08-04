<native:list :shows-indicators="false">
    <native:list-section
        header="Published components"
        footer="Open a component to inspect its native states and interactions."
    >
        @foreach ($components as $component)
            <native:list-item
                :headline="$component['label']"
                :overline="$component['tag']"
                :supporting="$component['description']"
                trailing-icon="chevron-right"
                @navigate($component['path'])
            />
        @endforeach
    </native:list-section>
</native:list>

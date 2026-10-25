<native:column class="w-full flex-1 bg-theme-background">
    <firstlight:search-field
        placeholder="Search posts"
        a11y-label="Search posts"
        native:model.debounce.300ms="query"
        class="mx-4 my-2"
    />

    @if (count($listItems) === 0)
        <firstlight:status-label
            label="{{ $query === '' ? 'No posts yet' : 'No matching posts' }}"
            tone="neutral"
            class="mx-4"
        />
    @endif

    <firstlight:list separator @refresh="reload" @endReached="loadMore" class="flex-1 bg-theme-background">
        @foreach ($listItems as $post)
            <firstlight:list-item
                :headline="$post->title"
                :supporting="$post->excerpt"
                trailing-icon="chevron-right"
                trailing-icon-ios="chevron.right"
                trailing-icon-android="chevron_right"
                @press="openPost({{ $post->id }})"
                :trailing-actions="[
                    ['method' => 'requestDeletion('.$post->id.')', 'label' => 'Delete', 'role' => 'destructive'],
                ]"
            />
        @endforeach
    </firstlight:list>

    <firstlight:confirmation-dialog
        :visible="$confirmingListDestruction"
        title="Delete post?"
        message="This action cannot be undone."
        confirm-label="Delete"
        cancel-label="Keep post"
        tone="destructive"
        @press="deletePost"
        @dismiss="cancelDeletion"
    />
</native:column>

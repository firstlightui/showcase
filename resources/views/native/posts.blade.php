<native:column class="w-full flex-1 bg-theme-background">
    @if (count($listItems) === 0)
        <native:column class="w-full flex-1 items-center justify-center gap-2 p-8">
            <native:text class="text-lg font-semibold text-center text-theme-on-surface">{{ $query === '' ? 'No posts yet' : 'No matching posts' }}</native:text>
            <native:text class="text-sm text-center text-theme-on-surface-variant">{{ $query === '' ? 'Posts you write appear here.' : 'Check the spelling or try a different search.' }}</native:text>
        </native:column>
    @else
    <firstlight:list separator @refresh="reload" @endReached="loadMore" class="flex-1 bg-theme-background">
        @foreach ($listItems as $post)
            <firstlight:list-item
                :headline="$post->title"
                :supporting="$post->excerpt"
                navigates
                @press="openPost({{ $post->id }})"
                :trailing-actions="[
                    ['method' => 'requestDeletion('.$post->id.')', 'label' => 'Delete', 'role' => 'destructive'],
                ]"
            />
        @endforeach
    </firstlight:list>
    @endif

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

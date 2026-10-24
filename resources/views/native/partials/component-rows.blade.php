@foreach ($demos as $demo)
    <firstlight:list-item
        :headline="$demo['label']"
        :supporting="$demo['description']"
        :leading-icon="$demo['icon'][0]"
        :leading-icon-ios="$demo['icon'][1]"
        :leading-icon-android="$demo['icon'][2]"
        :leading-icon-tone="$tones[$section]"
        trailing-icon="chevron-right"
        trailing-icon-ios="chevron.right"
        trailing-icon-android="chevron_right"
        @press="openDemo('{{ $demo['slug'] }}')"
    />
@endforeach

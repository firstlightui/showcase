@foreach ($demos as $demo)
    <firstlight:list-item
        :headline="$demo['label']"
        :supporting="$demo['description']"
        :leading-icon="$demo['icon'][0]"
        :leading-icon-ios="$demo['icon'][1]"
        :leading-icon-android="$demo['icon'][2]"
        :leading-icon-tone="$tones[$section]"
        navigates
        @press="openDemo('{{ $demo['slug'] }}')"
    />
@endforeach

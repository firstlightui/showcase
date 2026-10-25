{{-- Inline runs of one bundled documentation text; include inside a <native:text>. --}}
@foreach ($inline['runs'] ?? [['text' => $inline['text']]] as $run)
@if (isset($run['link']) && ! empty($run['code']))
<native:text :text="$run['text']" class="font-mono text-theme-primary bg-theme-surface-variant underline" @press="openLink({{ $run['link'] }})" />
@elseif (isset($run['link']))
<native:text :text="$run['text']" class="text-theme-primary underline" @press="openLink({{ $run['link'] }})" />
@elseif (! empty($run['code']))
<native:text :text="$run['text']" class="font-mono bg-theme-surface-variant" />
@elseif (! empty($run['strong']))
<native:text :text="$run['text']" class="font-semibold" />
@elseif (! empty($run['emphasis']))
<native:text :text="$run['text']" class="italic" />
@else
<native:text :text="$run['text']" />
@endif
@endforeach

<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-4">
        @if (! $available)
            <firstlight:callout message="Answers need a phone with Apple Intelligence or Gemini Nano." tone="info" />
        @endif

        <firstlight:text-area
            label="Question"
            :min-lines="2"
            :disabled="! $available"
            native:model.blur="question"
        />

        @if ($generationId)
            <firstlight:button variant="secondary" @press="cancel">Stop</firstlight:button>
        @else
            <firstlight:button :disabled="! $available || $question === ''" @press="ask">Ask</firstlight:button>
        @endif

        @if ($error)
            <firstlight:callout :message="$error" tone="warning" />
        @endif

        @if ($answer !== '')
            <native:text class="text-base text-theme-on-background">{{ $answer }}</native:text>
        @endif
    </native:column>
</native:scroll-view>

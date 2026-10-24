<native:list class="w-full flex-1">
    <native:list-section header="Dawn Patrol · Bondi surf school">
        <native:column class="w-full px-4 py-4 gap-4 bg-theme-surface rounded-2xl">
            <native:row class="w-full gap-4 items-start">
                <firstlight:metric
                    label="Boards left"
                    :value="$boardsLeft.' of '.\App\NativeComponents\GalleryScreen::CAPACITY"
                    :detail="$sessionLabel"
                    :tone="$boardsLeft === 0 ? 'danger' : ($boardsLeft <= 3 ? 'warning' : 'success')"
                    class="flex-1"
                />
                <firstlight:metric
                    label="Total"
                    :value="$total"
                    :detail="$ridersLabel.($hire ? ' with hire' : '')"
                    class="flex-1"
                />
            </native:row>
            @if ($booked > 0)
                <firstlight:status-label
                    :label="'Booked for '.$booked.($booked === 1 ? ' rider' : ' riders')"
                    tone="success"
                    icon="check"
                    icon-ios="checkmark.circle.fill"
                    icon-android="check_circle"
                />
            @else
                <firstlight:status-label
                    label="Clean offshore wind, 1.2 m swell"
                    icon="waves"
                    icon-ios="water.waves"
                    icon-android="waves"
                />
            @endif
        </native:column>
    </native:list-section>

    <native:list-section header="Session">
        <native:column class="w-full px-4 py-4 gap-4 bg-theme-surface rounded-2xl">
            <firstlight:date-picker
                native:model="date"
                label="Day"
                placeholder="Choose a day"
                :min="$today"
                :max="$lastDay"
                locale="en-AU"
                timezone="Australia/Sydney"
                a11y-hint="Lessons run every day for the next four weeks"
            />
            <firstlight:segmented
                native:model="session"
                :options="$sessionOptions"
                label="Paddle out"
                helper="Full sessions can't be chosen."
            />
        </native:column>
    </native:list-section>

    <native:list-section header="Riders" footer="Lessons are $45 a rider. Board and wetsuit hire adds $15.">
        <native:column class="w-full px-4 py-4 gap-4 bg-theme-surface rounded-2xl">
            <firstlight:stepper
                native:model="riders"
                :min="1"
                :max="4"
                label="Riders"
                :error="$riders > $boardsLeft && $boardsLeft > 0 ? 'Only '.$boardsLeft.($boardsLeft === 1 ? ' board' : ' boards').' left in this session.' : ''"
            />
            <firstlight:switch
                native:model="hire"
                label="Board and wetsuit hire"
                helper="Soft-top boards for every level."
            />
        </native:column>
    </native:list-section>

    <native:list-section footer="Sample booking. Nothing is charged, and Undo gives the boards back.">
        <firstlight:button
            variant="primary"
            size="lg"
            icon="check"
            :disabled="! $canBook"
            :a11y-hint="$canBook ? 'Books '.$ridersLabel.' for '.$sessionLabel : 'Choose a session with enough boards'"
            @press="book"
            class="w-full"
        >{{ $canBook ? 'Book '.$ridersLabel.' · '.$total : ($boardsLeft === 0 ? 'Session full' : 'Not enough boards') }}</firstlight:button>
    </native:list-section>

    <native:list-section header="Another app">
        <native:column class="w-full bg-theme-surface rounded-2xl">
            <firstlight:list-item
                headline="Inbox"
                supporting="Swipe, long-press and triage messages"
                leading-icon="inbox"
                leading-icon-ios="tray.full.fill"
                leading-icon-android="inbox"
                leading-icon-tone="info"
                trailing-icon="chevron-right"
                trailing-icon-ios="chevron.right"
                trailing-icon-android="chevron_right"
                @press="openInbox"
            />
        </native:column>
    </native:list-section>
</native:list>

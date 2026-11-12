<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-5 pb-8">
        <native:text class="text-sm text-theme-on-surface">On-device checks: change each enabled selection, reset Queue to Mine, and check both Queue controls agree. Queue changes rise by one per accepted tap and not for a tap on the selected segment or for Reset. Rejected queue stays Mine while attempts increase. Null begins unselected; empty and disabled groups stay inert. Check long labels at large text sizes. The error under Disabled and error states is a static presentation example; Validated queue shows a real Laravel message.</native:text>
        <native:text class="text-sm text-theme-on-surface">PHP accepted state: queue {{ $queue }}; queue changes {{ $queueChanges }}; priority {{ $priority }}; simple {{ $simple }}; initially empty {{ $unselected ?? '(none)' }}; error {{ $errorSelection }}; required {{ $requiredSelection }}; long label {{ $longSelection }}; rejected attempts {{ $rejectedQueueAttempts }}; view {{ $view }}; unlabelled {{ $unlabelledQueue }}; absent {{ $absentQueue }}; validated {{ $validatedQueue }}</native:text>
        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Stable integer values</native:text>
            <firstlight:segmented
                :options="$priorityOptions"
                native:model="priority"
                label="Priority"
                helper="Routine is selected; Urgent is unavailable."
                a11y-hint="Choose a referral priority"
            />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Server reconciliation</native:text>
            <firstlight:segmented
                :options="$queueOptions"
                native:model="queue"
                label="Queue"
                helper="Change the queue, then reset it from the server."
                a11y-hint="Choose the active queue"
            />
            <native:button label="Reset selections" variant="primary" @press="resetSelections" />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Unchanged-value rejection</native:text>
            <firstlight:segmented
                :options="$queueOptions"
                :value="$rejectedQueue"
                @change="rejectQueue"
                label="Rejected queue"
                helper="Try All; Mine stays selected."
                a11y-hint="The server only allows Mine in this example"
            />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Simple strings</native:text>
            <firstlight:segmented
                :options="$simpleOptions"
                native:model="simple"
                label="Simple queue"
            />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Stable string values</native:text>
            <firstlight:segmented
                :options="$queueOptions"
                native:model="queue"
                label="Stable queue"
            />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Null and empty states</native:text>
            <firstlight:segmented
                :options="$queueOptions"
                native:model="unselected"
                label="No initial selection"
                helper="Null leaves every segment unselected."
            />
            <firstlight:segmented
                :options="$emptyOptions"
                :value="null"
                label="No available options"
                helper="An empty option list is inert."
            />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Disabled and error states</native:text>
            <firstlight:segmented
                :options="$disabledOptions"
                native:model="disabledSelection"
                label="Disabled group"
                disabled
            />
            <firstlight:segmented
                :options="$queueOptions"
                native:model="errorSelection"
                label="Queue with error"
                error="Choose a queue before continuing."
            />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Required and helper text</native:text>
            <firstlight:segmented
                :options="$queueOptions"
                native:model="requiredSelection"
                label="Required queue"
                helper="This selection is required."
                required
            />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Large size beside a large Button</native:text>
            <native:text class="text-sm text-theme-on-surface">size="lg" matches Button size="lg"; the two controls below share one height. This control has no visible label: VoiceOver and TalkBack read its a11y-label, View.</native:text>
            <firstlight:segmented
                :options="$viewOptions"
                native:model="view"
                a11y-label="View"
                size="lg"
            />
            <firstlight:button label="Reset view to List" variant="secondary" size="lg" @press="resetView" />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Named only by a11y-label</native:text>
            <native:text class="text-sm text-theme-on-surface">Default size with no visible label; the accessible name is Unlabelled queue.</native:text>
            <firstlight:segmented
                :options="$queueOptions"
                native:model="unlabelledQueue"
                a11y-label="Unlabelled queue"
            />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Selected value absent from options</native:text>
            <firstlight:segmented
                :options="$queueOptions"
                native:model="absentQueue"
                label="Archived queue"
                helper="PHP holds archived, which is not an option, so no segment is selected."
            />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Laravel validation with native:model.live</native:text>
            <native:text class="text-sm text-theme-on-surface">Choose All: PHP accepts it, validateOnly fails, and the message binds to this control without an error attribute. Choose Mine to clear it.</native:text>
            <firstlight:segmented
                :options="$queueOptions"
                native:model.live="validatedQueue"
                label="Validated queue"
            />
        </native:column>

        <native:column class="w-full gap-2 pb-6">
            <native:text class="text-sm font-semibold text-theme-on-surface">Long labels</native:text>
            <firstlight:segmented
                :options="$longOptions"
                native:model="longSelection"
                label="Workflow status"
                a11y-hint="Choose the workflow status"
            />
        </native:column>
    </native:column>
</native:scroll-view>

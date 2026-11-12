<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 pb-8 gap-5">
        <native:text class="text-sm font-semibold text-theme-on-surface">On-device checks</native:text>
        <native:text class="text-sm text-theme-on-surface-variant">Tap each row: Recorded presses increases and Last row names it. Notifications records only; no navigation. Managed account should not change the result.</native:text>
        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Content</native:text>
            <firstlight:list-item headline="Account" @press="recordAccount" />
            <firstlight:list-item
                headline="Billing"
                supporting="Invoices and payment methods"
                trailing-text="Open"
                @press="recordBilling"
            />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Leading identity</native:text>
            <firstlight:list-item
                headline="Profile"
                supporting="Personal details and security"
                leading-monogram="AJ"
                trailing-icon="chevron-right"
                trailing-icon-ios="chevron.right"
                trailing-icon-android="chevron_right"
                @press="recordProfile"
            />
            <firstlight:list-item
                headline="Team"
                supporting="12 active members"
                leading-monogram="FL"
                trailing-text="Admin"
                @press="recordTeam"
            />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Platform icons</native:text>
            <firstlight:list-item
                headline="Notifications"
                supporting="Mentions and status updates"
                leading-icon="notifications"
                leading-icon-ios="bell.fill"
                leading-icon-android="notifications"
                trailing-icon="chevron-right"
                trailing-icon-ios="chevron.right"
                trailing-icon-android="chevron_right"
                a11y-hint="Records a Notifications press; does not navigate"
                @press="recordNotifications"
            />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Icon tones and colours</native:text>
            <native:text class="text-sm text-theme-on-surface-variant">Tones colour icons from the theme (danger, warning, success, info, neutral). An explicit hex colour wins over tone. Text, monograms and swipe actions keep their colours.</native:text>
            <firstlight:list-item headline="Upload failed" supporting="Leading danger tone" leading-icon="error" leading-icon-tone="danger" @press="recordTone('Upload failed')" />
            <firstlight:list-item headline="Sync delayed" supporting="Leading warning tone" leading-icon="warning" leading-icon-tone="warning" @press="recordTone('Sync delayed')" />
            <firstlight:list-item headline="Referral sent" supporting="Trailing success tone" leading-icon="mail" leading-icon-tone="neutral" trailing-icon="check_circle" trailing-icon-tone="success" @press="recordTone('Referral sent')" />
            <firstlight:list-item headline="Custom colours" supporting="Colour overrides tone on both icons" leading-icon="flag" leading-icon-tone="info" leading-icon-color="#7C3AED" trailing-icon="info" trailing-icon-tone="danger" trailing-icon-color="#0F766E" @press="recordTone('Custom colours')" />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Accessibility label and programmatic copy</native:text>
            <native:text class="text-sm text-theme-on-surface-variant">The clinician row is announced by its a11y-label instead of the combined headline and supporting text. Toggle referral makes PHP republish the referral row's headline and supporting text; no press is emitted.</native:text>
            <firstlight:list-item
                headline="Dr A. Chen"
                supporting="Cardiology · Rm 4"
                leading-monogram="AC"
                a11y-label="Doctor Alice Chen, cardiology, room 4"
                @press="recordClinician"
            />
            <firstlight:list-item
                :headline="$referralAccepted ? 'Referral accepted' : 'Referral pending'"
                :supporting="$referralAccepted ? 'Cardiology booked for 14 March' : 'Waiting for the cardiology clinic'"
                leading-icon="mail"
                @press="recordReferral"
            />
            <firstlight:button label="Toggle referral" variant="secondary" @press="toggleReferral" />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Swipe actions</native:text>
            <native:text class="text-sm text-theme-on-surface-variant">Swipe either way, then tap an action. Long swipes never execute. Delete records only: the row stays visible. Toggle disabled while a drawer is open to check reconciliation.</native:text>
            <firstlight:button label="Toggle swipe rows" @press="toggleSwipeRows" />
            <firstlight:list plain separator class="w-full h-80 bg-theme-background">
                <firstlight:list-item
                    headline="Message"
                    supporting="Archive, flag, or request deletion"
                    :disabled="$swipeRowsDisabled"
                    :leading-actions="[
                        ['method' => 'recordSwipe(\'Archive\',42)', 'label' => 'Archive', 'icon' => 'archive', 'ios' => 'archivebox'],
                    ]"
                    :trailing-actions="[
                        ['method' => 'recordSwipe(\'Delete\',42)', 'label' => 'Delete', 'icon' => 'delete', 'ios' => 'trash', 'role' => 'destructive'],
                        ['method' => 'recordSwipe(\'Flag\',42)', 'label' => 'Flag', 'icon' => 'flag'],
                    ]"
                    @press="recordMessage"
                />
                <firstlight:list-item
                    headline="Message with menu"
                    supporting="Swipe for Archive, or long-press for Reply and Pin"
                    :disabled="$swipeRowsDisabled"
                    :trailing-actions="[
                        ['method' => 'recordSwipe(\'Archive\',55)', 'label' => 'Archive', 'icon' => 'archive', 'ios' => 'archivebox'],
                    ]"
                    :context-menu="[
                        ['method' => 'recordMenu(\'Reply\',55)', 'label' => 'Reply', 'icon' => 'reply', 'ios' => 'arrowshape.turn.up.left'],
                        ['method' => 'recordMenu(\'Pin\',55)', 'label' => 'Pin', 'icon' => 'push_pin', 'ios' => 'pin'],
                    ]"
                    @press="recordMessage"
                />
                <firstlight:list-section header="Grouped rows">
                    <firstlight:list-item
                        headline="Grouped message"
                        supporting="Actions also work inside a section"
                        :disabled="$swipeRowsDisabled"
                        :trailing-actions="[
                            ['method' => 'recordSwipe(\'Flag\',73)', 'label' => 'Flag', 'icon' => 'flag'],
                        ]"
                        @press="recordMessage"
                    />
                    <firstlight:list-item
                        headline="Disabled message"
                        supporting="Neither edge nor accessibility actions can activate"
                        disabled
                        :trailing-actions="[
                            ['method' => 'recordSwipe(\'Delete\',99)', 'label' => 'Delete', 'role' => 'destructive'],
                        ]"
                        @press="recordMessage"
                    />
                </firstlight:list-section>
            </firstlight:list>
            <native:text class="text-sm text-theme-on-surface">Recorded swipe actions: {{ $swipeCount }}</native:text>
            <native:text class="text-sm text-theme-on-surface">Last swipe action: {{ $lastSwipe }}</native:text>
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Context menu</native:text>
            <native:text class="text-sm text-theme-on-surface-variant">Long-press the row to open the menu, or use the VoiceOver Actions rotor or TalkBack actions menu without a long press. Forward starts disabled. Delete records only: the row stays visible. A plain tap still records a row press.</native:text>
            <firstlight:button label="Toggle Forward" @press="toggleForward" />
            <firstlight:button label="Toggle menu row" @press="toggleMenuRow" />
            <firstlight:list-item
                headline="Message from Alex"
                supporting="Long-press for more actions"
                leading-monogram="AL"
                :disabled="$menuRowDisabled"
                :context-menu="[
                    ['method' => 'recordMenu(\'Reply\',42)', 'label' => 'Reply', 'icon' => 'reply', 'ios' => 'arrowshape.turn.up.left'],
                    ['method' => 'recordMenu(\'Forward\',42)', 'label' => 'Forward', 'icon' => 'forward', 'ios' => 'arrowshape.turn.up.right', 'disabled' => ! $canForward],
                    ['method' => 'recordMenu(\'Pin\',42)', 'label' => 'Pin', 'icon' => 'push_pin', 'ios' => 'pin'],
                    ['method' => 'recordMenu(\'Delete\',42)', 'label' => 'Delete', 'icon' => 'delete', 'ios' => 'trash', 'role' => 'destructive'],
                ]"
                @press="recordMessage"
            />
            <native:text class="text-sm text-theme-on-surface">Recorded menu actions: {{ $menuCount }}</native:text>
            <native:text class="text-sm text-theme-on-surface">Last menu action: {{ $lastMenu }}</native:text>
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">List sections</native:text>
            <native:text class="text-sm text-theme-on-surface-variant">Sections with a footer only, with no header or footer, and with a header and footer that PHP renames or removes. Rows only record presses.</native:text>
            <firstlight:list separator class="w-full h-96 bg-theme-background">
                <firstlight:list-section footer="Footer-only section: no header is authored.">
                    <firstlight:list-item headline="Footer-only row" @press="recordSection('Footer-only row')" />
                </firstlight:list-section>
                <firstlight:list-section>
                    <firstlight:list-item headline="Bare section row" supporting="No header or footer" @press="recordSection('Bare section row')" />
                </firstlight:list-section>
                @if ($sectionNotesShown)
                    <firstlight:list-section :header="'Ward round '.$sectionRevision" :footer="'Revision '.$sectionRevision.' published by PHP'">
                        <firstlight:list-item headline="Ward round row" @press="recordSection('Ward round row')" />
                    </firstlight:list-section>
                @else
                    <firstlight:list-section>
                        <firstlight:list-item headline="Ward round row" @press="recordSection('Ward round row')" />
                    </firstlight:list-section>
                @endif
            </firstlight:list>
            <firstlight:button label="Rename section" variant="secondary" @press="renameSection" />
            <firstlight:button :label="$sectionNotesShown ? 'Remove header and footer' : 'Restore header and footer'" variant="secondary" @press="toggleSectionNotes" />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: section revision {{ $sectionRevision }} · header and footer {{ $sectionNotesShown ? 'shown' : 'removed' }}</native:text>
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Disabled</native:text>
            <firstlight:list-item
                headline="Managed account"
                supporting="Only an administrator can make changes"
                leading-icon="lock"
                trailing-text="Unavailable"
                disabled
                @press="recordAccount"
            />
        </native:column>

        <native:column class="w-full gap-3">
            <native:text class="text-sm font-semibold text-theme-on-surface">Identity, status and pending states</native:text>
            <firstlight:list-item headline="Alex Morgan" supporting="Admission" leading-label="4B-12" trailing-status="Billed today" trailing-status-tone="success" @press="recordProfile" />
            <firstlight:list-item headline="Sam Lee" supporting="Admission" leading-label="4B-13" trailing-status="Waiting to sync" trailing-status-tone="warning" state="provisional" state-label="Provisional admission waiting to sync" @press="recordProfile" />
            <firstlight:list-item headline="Taylor Green" supporting="Discharge" leading-label="4B-14" trailing-status="Waiting to sync" trailing-status-tone="warning" state="pending-removal" state-label="Discharge waiting to sync" @press="recordProfile" />
        </native:column>
        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Read-only summary and comparison</native:text>
            <native:text class="text-theme-on-surface">Patient: Alex Morgan</native:text>
            <native:text class="text-theme-on-surface">Date of birth: 12 May 1980</native:text>
            <native:text class="text-theme-on-surface">Label identifier: 1234</native:text>
            <native:text class="text-theme-on-surface">On file: 1234</native:text>
            <firstlight:status-label label="Matches" tone="success" icon="check" icon-ios="checkmark" />
        </native:column>

        <native:column class="w-full gap-2 pb-6">
            <native:text class="text-sm font-semibold text-theme-on-surface">Interaction</native:text>
            <native:text class="text-sm text-theme-on-surface">Recorded presses: {{ $pressCount }}</native:text>
            <native:text class="text-sm text-theme-on-surface">Last row: {{ $lastPressed }}</native:text>
        </native:column>
    </native:column>
</native:scroll-view>

<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-5 pb-8">
        <native:text class="text-sm text-theme-on-surface">On-device checks: edit/clear email then reset. Search updates PHP after 500 ms quiet. Password updates on blur; reveal only changes native visibility, diagnostics show length only. Patient ID permits copy, not edits. Recipient trailing action simulates entry without a camera.</native:text>
        <native:column class="w-full gap-2">
            <firstlight:text-field label="Checked identifier" value="1234" helper="Checksum OK" helper-tone="success" read-only />
            <firstlight:text-field label="Review identifier" value="1234" helper="Check this" helper-tone="warning" read-only />
            <firstlight:text-field label="Label identifier" value="1234" helper="From label" read-only />
        </native:column>
        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Native email input</native:text>
            <firstlight:text-field
                native:model="email"
                label="Email"
                placeholder="you@example.com"
                helper="Used for appointment updates."
                keyboard="email"
                content-type="email"
                autocapitalize="none"
                :autocorrect="false"
                leading-icon="mail"
                leading-icon-ios="envelope"
                leading-icon-android="email"
                submit-label="next"
                required
                clearable
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $email ?: '(empty)' }}</native:text>
            <native:button label="Reset email" variant="secondary" @press="resetEmail" />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Formatting masks</native:text>
            <native:text class="text-sm text-theme-on-surface">Spaces are inserted as you type; PHP receives only the slot characters. Letters are dropped from digit slots and input stops when the pattern is full.</native:text>
            <firstlight:text-field
                native:model="cardNumber"
                label="Card number"
                placeholder="1234 5678 9012 3456"
                mask="#### #### #### ####"
                keyboard="number"
                clearable
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $cardNumber ?: '(empty)' }}</native:text>
            <firstlight:text-field
                native:model.blur="postcode"
                label="Postal code"
                helper="Letter, digit, letter, space, digit, letter, digit."
                mask="A#A #A#"
                autocapitalize="characters"
                :autocorrect="false"
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $postcode ?: '(empty)' }} (blur)</native:text>
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Debounced search</native:text>
            <firstlight:text-field
                native:model.debounce.500ms="search"
                label="Search referrals"
                placeholder="Name or reference"
                keyboard="text"
                autocapitalize="words"
                leading-icon="search"
                leading-icon-ios="magnifyingglass"
                leading-icon-android="search"
                submit-label="search"
                clearable
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $search ?: '(empty)' }} (debounced)</native:text>
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Secure entry</native:text>
            <firstlight:text-field
                native:model.blur="password"
                label="Password"
                helper="Use at least twelve characters."
                content-type="password"
                autocapitalize="none"
                :autocorrect="false"
                secure
                revealable
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted password length: {{ mb_strlen($password) }} (blur)</native:text>
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Read-only and validation</native:text>
            <native:text class="text-sm text-theme-on-surface">Static error presentation example; editing does not clear the message. Use Validation for real rules.</native:text>
            <firstlight:text-field
                :value="$patientId"
                label="Patient ID"
                helper="Select and copy this identifier."
                read-only
            />
            <firstlight:text-field
                native:model="errorEmail"
                label="Email with error"
                error="Enter a valid email address."
                keyboard="email"
                content-type="email"
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $errorEmail }}</native:text>
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Disabled from PHP</native:text>
            <native:text class="text-sm text-theme-on-surface">While disabled the field cannot take focus, edit, or clear. PHP toggles the state.</native:text>
            <firstlight:text-field
                native:model="reference"
                label="Reference"
                :disabled="$referenceDisabled"
                clearable
            />
            <native:text class="text-sm text-theme-on-surface">PHP disabled: {{ $referenceDisabled ? 'yes' : 'no' }}; PHP accepted state: {{ $reference ?: '(empty)' }}</native:text>
            <native:button label="{{ $referenceDisabled ? 'Enable reference' : 'Disable reference' }}" variant="secondary" @press="toggleReferenceDisabled" />
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Keyboard submit</native:text>
            <native:text class="text-sm text-theme-on-surface">Press the keyboard Send action: @submit receives the current text and the count rises, including for an empty note.</native:text>
            <firstlight:text-field
                ref="noteField"
                native:model.blur="note"
                label="Note"
                placeholder="Write a short note"
                submit-label="send"
                @submit="submitNote"
            />
            <native:text class="text-sm text-theme-on-surface">Last submitted: {{ $lastSubmittedNote ?: '(empty)' }}; submissions: {{ $noteSubmits }}</native:text>
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Lazy and live models</native:text>
            <native:text class="text-sm text-theme-on-surface">Lazy publishes only on focus loss or submit; live publishes every edit.</native:text>
            <firstlight:text-field
                native:model.lazy="lazyReferral"
                label="Lazy referral"
                placeholder="Type, then leave the field"
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $lazyReferral ?: '(empty)' }} (lazy)</native:text>
            <firstlight:text-field
                native:model.live="liveReferral"
                label="Live referral"
                placeholder="Type to publish each edit"
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $liveReferral ?: '(empty)' }} (live)</native:text>
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Server rejection</native:text>
            <native:text class="text-sm text-theme-on-surface">PHP rejects every edit: after each change the field shows Ward 4 again and the count rises.</native:text>
            <firstlight:text-field
                :value="$approvedWard"
                label="Server-approved ward"
                helper="Edits are deliberately rejected by PHP."
                @change="rejectWard"
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $approvedWard }}; Rejected edits: {{ $rejectedWardEdits }}</native:text>
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Keyboards and autofill</native:text>
            <native:text class="text-sm text-theme-on-surface">Each field asks for its own keyboard or autofill suggestion. On iOS, the phone, number, and decimal keyboards show a Done accessory.</native:text>
            <firstlight:text-field native:model.blur="fullName" label="Full name" content-type="name" autocapitalize="words" />
            <firstlight:text-field native:model.blur="username" label="Username" content-type="username" autocapitalize="none" :autocorrect="false" />
            <firstlight:text-field native:model.blur="newPassword" label="New password" content-type="new-password" autocapitalize="none" :autocorrect="false" secure />
            <firstlight:text-field native:model.blur="oneTimeCode" label="One-time code" content-type="one-time-code" keyboard="number" />
            <firstlight:text-field native:model.blur="phone" label="Phone" keyboard="phone" />
            <firstlight:text-field native:model.blur="website" label="Website" keyboard="url" autocapitalize="none" :autocorrect="false" />
            <firstlight:text-field native:model.blur="dose" label="Dose (mg)" keyboard="decimal" />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: name {{ $fullName ?: '(empty)' }}; username {{ $username ?: '(empty)' }}; new password length {{ mb_strlen($newPassword) }}; code {{ $oneTimeCode ?: '(empty)' }}; phone {{ $phone ?: '(empty)' }}; website {{ $website ?: '(empty)' }}; dose {{ $dose ?: '(empty)' }} (blur)</native:text>
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Accessibility label only</native:text>
            <native:text class="text-sm text-theme-on-surface">No visible label: VoiceOver and TalkBack name this field Ward code.</native:text>
            <firstlight:text-field
                native:model="wardCode"
                a11y-label="Ward code"
                a11y-hint="Four characters, for example W4AB"
                placeholder="W4AB"
                autocapitalize="characters"
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $wardCode ?: '(empty)' }}</native:text>
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Flush before button press</native:text>
            <native:text class="text-sm text-theme-on-surface">Type in Handover note and, while it still has focus, press Record PHP value. The blur change is queued before the press, so the recorded value shows the typed text.</native:text>
            <firstlight:text-field
                native:model.blur="flushNote"
                label="Handover note"
                placeholder="Type, keep focus, then press below"
            />
            <firstlight:button variant="secondary" @press="recordFlush">Record PHP value</firstlight:button>
            <native:text class="text-sm text-theme-on-surface">PHP value at press: {{ $flushSnapshot }}; presses: {{ $flushPresses }}</native:text>
        </native:column>

        <native:column class="w-full gap-2 pb-6">
            <native:text class="text-sm font-semibold text-theme-on-surface">Authored trailing action</native:text>
            <native:text class="text-sm text-theme-on-surface">Simulated recipient entry: tap the trailing action to populate PT-2048 and increment the count. No scan or camera is performed.</native:text>
            <firstlight:text-field
                ref="recipientAction"
                native:model="recipient"
                label="Recipient"
                placeholder="Simulate or enter an identifier"
                trailing-icon="qr-code-scanner"
                trailing-icon-ios="qrcode.viewfinder"
                trailing-icon-android="qr_code_scanner"
                trailing-a11y-label="Simulate recipient entry"
                @press="scanRecipient"
            />
            <native:text class="text-sm text-theme-on-surface">PHP accepted state: {{ $recipient ?: '(empty)' }}; Simulated actions: {{ $scanRequests }}</native:text>
        </native:column>
    </native:column>
</native:scroll-view>

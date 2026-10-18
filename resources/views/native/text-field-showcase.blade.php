<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-5 pb-32">
        <native:text class="text-sm text-theme-on-surface">On-device checks: edit/clear email then reset. Search updates PHP after 500 ms quiet. Password updates on blur; reveal only changes native visibility, diagnostics show length only. Patient ID permits copy, not edits. Recipient trailing action simulates entry without a camera.</native:text>
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

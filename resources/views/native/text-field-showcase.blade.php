<native:scroll-view class="w-full flex-1 bg-theme-background">
    <native:column class="w-full p-4 gap-5">
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
        </native:column>

        <native:column class="w-full gap-2">
            <native:text class="text-sm font-semibold text-theme-on-surface">Read-only and validation</native:text>
            <firstlight:text-field
                :value="$patientId"
                label="Patient ID"
                helper="Select and copy this identifier."
                read-only
            />
            <firstlight:text-field
                value="invalid-address"
                label="Email with error"
                error="Enter a valid email address."
                keyboard="email"
                content-type="email"
            />
        </native:column>

        <native:column class="w-full gap-2 pb-6">
            <native:text class="text-sm font-semibold text-theme-on-surface">Authored trailing action</native:text>
            <firstlight:text-field
                native:model="recipient"
                label="Recipient"
                placeholder="Scan or enter an identifier"
                trailing-icon="qr-code-scanner"
                trailing-icon-ios="qrcode.viewfinder"
                trailing-icon-android="qr_code_scanner"
                trailing-a11y-label="Scan recipient code"
                @press="scanRecipient"
            />
        </native:column>
    </native:column>
</native:scroll-view>

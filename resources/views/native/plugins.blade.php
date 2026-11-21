<firstlight:list class="w-full flex-1 bg-theme-background">
    <firstlight:list-section header="Security" footer="Locks after a minute in the background. Unlocking needs the paid biometrics plugin.">
        <firstlight:list-item
            headline="App lock"
            supporting="Hide content in the app switcher and unlock with Face ID or a fingerprint"
            leading-icon="lock"
            leading-icon-ios="lock.fill"
            leading-icon-android="lock"
            leading-icon-tone="danger"
            navigates
            a11y-hint="Opens the app lock recipe"
            @press="openVault"
        />
    </firstlight:list-section>
    <firstlight:list-section header="System" footer="Shortcuts appear after a native build. The vault shortcut shows the lock screen first when the app is locked.">
        <firstlight:list-item
            headline="App shortcuts"
            supporting="Open screens from Siri, Spotlight and the app icon"
            leading-icon="shortcut"
            leading-icon-ios="arrow.up.forward.app"
            leading-icon-android="shortcut"
            leading-icon-tone="info"
            navigates
            a11y-hint="Opens the app shortcuts recipe"
            @press="openShortcuts"
        />
        <firstlight:list-item
            headline="Customise tabs"
            supporting="Let people reorder the tab bar and move tabs to More"
            leading-icon="tab"
            leading-icon-ios="square.grid.3x1.below.line.grid.1x2"
            leading-icon-android="tab"
            leading-icon-tone="info"
            navigates
            a11y-hint="Opens the customisable tab bar recipe"
            @press="openCustomTabs"
        />
    </firstlight:list-section>
    <firstlight:list-section header="On device" footer="Nothing leaves the phone. Scanning needs a real device; answers need Apple Intelligence or Gemini Nano.">
        <firstlight:list-item
            headline="Scan documents"
            supporting="Scan pages with the system scanner and read their text with Local OCR"
            leading-icon="document-scanner"
            leading-icon-ios="doc.viewfinder"
            leading-icon-android="document_scanner"
            leading-icon-tone="info"
            navigates
            a11y-hint="Opens the document scanning recipe"
            @press="openScan"
        />
        <firstlight:list-item
            headline="Ask on-device AI"
            supporting="Stream an answer from the phone's own model with Local AI"
            leading-icon="auto-awesome"
            leading-icon-ios="sparkles"
            leading-icon-android="auto_awesome"
            leading-icon-tone="success"
            navigates
            a11y-hint="Opens the on-device text generation recipe"
            @press="openAsk"
        />
    </firstlight:list-section>
</firstlight:list>

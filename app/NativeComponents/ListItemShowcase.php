<?php

namespace App\NativeComponents;

use Illuminate\View\View;

class ListItemShowcase extends ShowcaseScreen
{
    public int $pressCount = 0;

    public string $lastPressed = 'None';

    public int $swipeCount = 0;

    public string $lastSwipe = 'None';

    public bool $swipeRowsDisabled = false;

    public int $menuCount = 0;

    public string $lastMenu = 'None';

    public bool $canForward = false;

    public bool $menuRowDisabled = false;

    public bool $referralAccepted = false;

    public bool $sectionNotesShown = true;

    public int $sectionRevision = 1;

    public function toggleReferral(): void
    {
        $this->referralAccepted = ! $this->referralAccepted;
    }

    public function recordReferral(): void
    {
        $this->record('Referral');
    }

    public function recordClinician(): void
    {
        $this->record('Clinician');
    }

    public function recordTone(string $headline): void
    {
        $this->record($headline);
    }

    public function recordSection(string $headline): void
    {
        $this->record($headline);
    }

    public function renameSection(): void
    {
        $this->sectionRevision++;
    }

    public function toggleSectionNotes(): void
    {
        $this->sectionNotesShown = ! $this->sectionNotesShown;
    }

    public function recordMenu(string $action, int $id): void
    {
        $this->menuCount++;
        $this->lastMenu = "{$action} #{$id}";
    }

    public function toggleForward(): void
    {
        $this->canForward = ! $this->canForward;
    }

    public function toggleMenuRow(): void
    {
        $this->menuRowDisabled = ! $this->menuRowDisabled;
    }

    public function recordMessage(): void
    {
        $this->record('Message');
    }

    public function recordSwipe(string $action, int $id): void
    {
        $this->swipeCount++;
        $this->lastSwipe = "{$action} #{$id}";
    }

    public function toggleSwipeRows(): void
    {
        $this->swipeRowsDisabled = ! $this->swipeRowsDisabled;
    }

    public function recordNotifications(): void
    {
        $this->record('Notifications');
    }

    public function recordAccount(): void
    {
        $this->record('Account');
    }

    public function recordProfile(): void
    {
        $this->record('Profile');
    }

    public function recordTeam(): void
    {
        $this->record('Team');
    }

    public function recordBilling(): void
    {
        $this->record('Billing');
    }

    public function navTitle(): string
    {
        return 'Firstlight List Item';
    }

    public function render(): View
    {
        return view('native.list-item-showcase');
    }

    private function record(string $headline): void
    {
        $this->pressCount++;
        $this->lastPressed = $headline;
    }
}

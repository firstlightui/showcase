<?php

namespace App\NativeComponents;

use Illuminate\View\View;

class PillGroupShowcase extends ShowcaseScreen
{
    public ?string $errorQueue = null;

    public ?string $workflowStatus = 'clinical-review';

    public ?string $queue = 'mine';

    /** @var list<string> */
    public array $queues = ['mine', 'urgent'];

    public ?string $rejectedQueue = 'mine';

    public int $rejectedAttempts = 0;

    public ?int $priority = 10;

    public ?string $mailFilter = 'all';

    /** @var list<string> */
    public array $invoiceStatuses = ['overdue'];

    /** @var list<array{value: string, label: string, icon: string, icon-ios?: string}> */
    public array $mailFilterOptions = [
        ['value' => 'all', 'label' => 'All', 'icon' => 'inbox', 'icon-ios' => 'tray'],
        ['value' => 'unread', 'label' => 'Unread', 'icon' => 'mail', 'icon-ios' => 'envelope.badge'],
        ['value' => 'starred', 'label' => 'Starred', 'icon' => 'star', 'icon-ios' => 'star.fill'],
        ['value' => 'attachments', 'label' => 'Attachments', 'icon' => 'attach_file', 'icon-ios' => 'paperclip'],
        ['value' => 'mentions', 'label' => 'Mentions', 'icon' => 'alternate_email', 'icon-ios' => 'at'],
        ['value' => 'archived', 'label' => 'Archived', 'icon' => 'archive', 'icon-ios' => 'archivebox'],
    ];

    /** @var list<array{value: string, label: string, icon: string, icon-ios?: string}> */
    public array $invoiceStatusOptions = [
        ['value' => 'draft', 'label' => 'Draft', 'icon' => 'edit_note', 'icon-ios' => 'square.and.pencil'],
        ['value' => 'sent', 'label' => 'Sent', 'icon' => 'send', 'icon-ios' => 'paperplane'],
        ['value' => 'overdue', 'label' => 'Overdue', 'icon' => 'schedule', 'icon-ios' => 'clock'],
        ['value' => 'paid', 'label' => 'Paid', 'icon' => 'check_circle', 'icon-ios' => 'checkmark.circle'],
        ['value' => 'void', 'label' => 'Void', 'icon' => 'block', 'icon-ios' => 'nosign'],
    ];

    /** @var array<string, string> */
    public array $queueOptions = [
        'mine' => 'Mine',
        'all' => 'All',
        'urgent' => 'Urgent',
    ];

    /** @var list<array{value: int, label: string, disabled: bool}> */
    public array $priorityOptions = [
        ['value' => 10, 'label' => 'Routine', 'disabled' => false],
        ['value' => 20, 'label' => 'Urgent', 'disabled' => true],
        ['value' => 30, 'label' => 'Immediate', 'disabled' => false],
    ];

    /** @var array<string, string> */
    public array $longOptions = [
        'clinical-review' => 'Awaiting clinical review',
        'assignment' => 'Ready for assignment',
        'follow-up' => 'Needs follow-up',
    ];

    public function rejectQueue(?string $queue): void
    {
        $this->rejectedAttempts++;
    }

    public function resetSelections(): void
    {
        $this->queue = 'mine';
        $this->queues = ['mine', 'urgent'];
    }

    public function navTitle(): string
    {
        return 'Firstlight Pill Group';
    }

    public function render(): View
    {
        return view('native.pill-group-showcase');
    }
}

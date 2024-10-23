<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\HelpTopicApprover;
use App\Models\Ticket;
use App\Models\TicketApproval;
use Livewire\Component;

class DropdownApprovalButton extends Component
{
    public Ticket $ticket;
    public bool $isApproverIsInConfiguration = false;
    private array $levelsOfApproval = [1, 2, 3, 4, 5];

    protected $listeners = ['loadDropdownApprovalButton' => '$refresh'];

    public function mount()
    {
        $this->isApproverIsInConfiguration = $this->isApproverIsInConfiguration();
        dump($this->canApproveLevel1());
        // dump($this->ticketHasMoreThanOneApproval());
    }

    private function isApproverIsInConfiguration()
    {
        return $this->ticket->withWhereHas('ticketApprovals.helpTopicApprover', function ($approver) {
            $approver->where('user_id', auth()->user()->id);
        })->exists();
    }

    private function ticketHasMoreThanOneApproval()
    {
        return TicketApproval::where([
            ['ticket_id', $this->ticket->id],
            ['is_approved', false]
        ])->withWhereHas('helpTopicApprover', function ($approver) {
            $approver->whereIn('level', $this->levelsOfApproval);
        })->get()->count() > 1;
    }

    private function canApproveLevel1()
    {
        return TicketApproval::where([
            ['ticket_id', $this->ticket->id],
            ['is_approved', false]
        ])->withWhereHas('helpTopicApprover', function ($approver) {
            $approver->whereIn('level', $this->levelsOfApproval)
                ->where([
                    ['level', 1],
                    ['user_id', auth()->user()->id]
                ]);
        })->exists()
            && (!$this->level1IsApproved()
                && !$this->level2IsApproved()
                && !$this->level3IsApproved()
                && !$this->level4IsApproved()
                && !$this->level5IsApproved());
    }

    private function canApproveLevel2()
    {
        return TicketApproval::where([
            ['ticket_id', $this->ticket->id],
            ['is_approved', false]
        ])->withWhereHas('helpTopicApprover', function ($approver) {
            $approver->whereIn('level', $this->levelsOfApproval)
                ->where([
                    ['level', 2],
                    ['user_id', auth()->user()->id]
                ]);
        })->exists()
            && ($this->level1IsApproved()
                && !$this->level2IsApproved()
                && !$this->level3IsApproved()
                && !$this->level4IsApproved()
                && !$this->level5IsApproved());
    }

    private function canApproveLevel3()
    {
        return TicketApproval::where([
            ['ticket_id', $this->ticket->id],
            ['is_approved', false]
        ])->withWhereHas('helpTopicApprover', function ($approver) {
            $approver->whereIn('level', $this->levelsOfApproval)
                ->where([
                    ['level', 3],
                    ['user_id', auth()->user()->id]
                ]);
        })->exists()
            && ($this->level1IsApproved()
                && $this->level2IsApproved()
                && !$this->level3IsApproved()
                && !$this->level4IsApproved()
                && !$this->level5IsApproved());
    }

    private function canApproveLevel4()
    {
        return TicketApproval::where([
            ['ticket_id', $this->ticket->id],
            ['is_approved', false]
        ])->withWhereHas('helpTopicApprover', function ($approver) {
            $approver->whereIn('level', $this->levelsOfApproval)
                ->where([
                    ['level', 4],
                    ['user_id', auth()->user()->id]
                ]);
        })->exists()
            && ($this->level1IsApproved()
                && $this->level2IsApproved()
                && $this->level3IsApproved()
                && !$this->level4IsApproved()
                && !$this->level5IsApproved());
    }

    private function canApproveLevel5()
    {
        return TicketApproval::where([
            ['ticket_id', $this->ticket->id],
            ['is_approved', false]
        ])->withWhereHas('helpTopicApprover', function ($approver) {
            $approver->whereIn('level', $this->levelsOfApproval)
                ->where([
                    ['level', 5],
                    ['user_id', auth()->user()->id]
                ]);
        })->exists()
            && ($this->level1IsApproved()
                && $this->level2IsApproved()
                && $this->level3IsApproved()
                && $this->level4IsApproved()
                && !$this->level5IsApproved());
    }

    private function isApprovedForLevel(int $level)
    {
        return TicketApproval::where([
            ['ticket_id', $this->ticket->id],
            ['is_approved', true]
        ])->withWhereHas('helpTopicApprover', function ($approver) use ($level) {
            $approver->whereIn('level', $this->levelsOfApproval)
                ->where('level', $level);
        })->exists();
    }

    private function level1IsApproved()
    {
        return $this->isApprovedForLevel(1);
    }

    public function level2IsApproved()
    {
        return $this->isApprovedForLevel(2);
    }

    public function level3IsApproved()
    {
        return $this->isApprovedForLevel(3);
    }

    public function level4IsApproved()
    {
        return $this->isApprovedForLevel(4);
    }

    public function level5IsApproved()
    {
        return $this->isApprovedForLevel(5);
    }

    public function render()
    {
        return view('livewire.staff.ticket.dropdown-approval-button');
    }
}
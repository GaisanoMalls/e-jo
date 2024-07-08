<?php

namespace App\Http\Livewire\Requester\TicketStatus;

use App\Enums\ApprovalStatusEnum;
use App\Http\Traits\Requester\Tickets;
use App\Models\Ticket;
use Livewire\Component;

class Approved extends Component
{
    use Tickets;

    public bool $allApprovedTickets = true;
    public bool $withCosting = false;
    public bool $withOutCosting = false;
    public array $approvedTickets = [];

    public function mount()
    {
        $this->approvedTickets = $this->loadApprovedTickets();
    }

    public function filterAllApprovedTickets()
    {
        $this->withCosting = false;
        $this->withOutCosting = false;
        $this->allApprovedTickets = true;
        $this->loadApprovedTickets();
    }

    public function filterApprovedTicketsWithCosting()
    {
        $this->allApprovedTickets = false;
        $this->withOutCosting = false;
        $this->withCosting = true;
        $this->loadApprovedTickets();
    }

    public function filterApprovedTicketsWithoutCosting()
    {
        $this->allApprovedTickets = false;
        $this->withCosting = false;
        $this->withOutCosting = true;
        $this->loadApprovedTickets();
    }

    public function loadApprovedTickets()
    {
        if ($this->allApprovedTickets) {
            $this->approvedTickets = $this->getApprovedTickets();
        }

        if ($this->withCosting) {
            $this->approvedTickets = Ticket::whereHas('ticketCosting')
                ->with(['replies', 'priorityLevel'])
                ->where([
                    ['approval_status', ApprovalStatusEnum::APPROVED],
                    ['user_id', auth()->user()->id]
                ])
                ->orderByDesc('created_at')
                ->get();
        }

        if ($this->withOutCosting) {
            $this->approvedTickets = Ticket::whereDoesntHave('ticketCosting')
                ->with(['replies', 'priorityLevel'])
                ->where([
                    ['approval_status', ApprovalStatusEnum::APPROVED],
                    ['user_id', auth()->user()->id]
                ])
                ->orderByDesc('created_at')
                ->get();
        }

        return $this->approvedTickets;
    }

    public function render()
    {
        return view('livewire.requester.ticket-status.approved');
    }
}
<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\ApprovalStatus;
use App\Models\Status;
use App\Models\Ticket;
use Livewire\Component;

class SlaTimer extends Component
{
    public Ticket $ticket;
    public $slaDays;
    public $isTicketApprovedForSLA;

    protected $listeners = ['loadSlaTimer' => '$refresh'];

    public function mount()
    {
        $this->slaDays = (int) $this->ticket->sla->time_unit[0]; // Get the first index of the string which is a number
        $this->isTicketApprovedForSLA = $this->isApprovedForSLA();
    }

    public function isApprovedForSLA()
    {
        return ($this->ticket->status_id == Status::APPROVED || $this->ticket->approval_status == ApprovalStatus::APPROVED)
            ? true
            : false;
    }

    public function render()
    {
        return view('livewire.staff.ticket.sla-timer');
    }
}

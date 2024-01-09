<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\ApprovalStatus;
use App\Models\Status;
use App\Models\Ticket;
use Livewire\Component;
use Carbon\Carbon;

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

    public function updateCountdown()
    {
        // Get the current date and time
        $currentDate = now()->timestamp;

        // Get the target date from the server or any other data source
        $targetDate = Carbon::parse($this->ticket->svcdept_date_approved)
            ->addHours($this->slaDays * 24)
            ->timestamp;

        // Calculate the time remaining
        $timeRemaining = $targetDate - $currentDate;

        // Calculate days, hours, and minutes
        $days = floor($timeRemaining / (60 * 60 * 24));
        $hours = floor(($timeRemaining % (60 * 60 * 24)) / (60 * 60));
        $minutes = floor(($timeRemaining % (60 * 60)) / 60);

        // Check if the countdown has reached zero
        if ($timeRemaining <= 0) {
            $countTimer = 'Ticket is overdue';
        } else {
            $countTimer = "{$days} days, {$hours} hours, {$minutes} minutes";
        }

        return $countTimer;
    }

    public function render()
    {
        return view('livewire.staff.ticket.sla-timer', [
            'slaTimer' => $this->updateCountdown()
        ]);
    }
}

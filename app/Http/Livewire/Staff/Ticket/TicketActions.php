<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\IctRecommendation;
use App\Models\Ticket;
use Illuminate\Support\Collection;
use Livewire\Component;

class TicketActions extends Component
{
    public Ticket $ticket;
    public ?IctRecommendation $ictRecommendationApprover = null;

    protected $listeners = ['loadTicketActions' => '$refresh'];

    public function getCurrentTeamOrAgent()
    {
        if (!is_null($this->ticket->team_id)) {
            $this->dispatchBrowserEvent('get-current-team-or-agent', ['ticket' => $this->ticket]);
        }
    }

    public function isRecommendationRequested()
    {
        return IctRecommendation::where([
            ['ticket_id', $this->ticket->id],
            ['is_requesting_ict_approval', true],
        ])->exists();
    }

    public function render()
    {
        $this->ictRecommendationApprover = IctRecommendation::with('approver.profile')->where('ticket_id', $this->ticket->id)->first();
        return view('livewire.staff.ticket.ticket-actions');
    }
}
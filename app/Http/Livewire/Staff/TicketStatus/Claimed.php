<?php

namespace App\Http\Livewire\Staff\TicketStatus;

use App\Http\Traits\TicketsByStaffWithSameTemplates;
use App\Models\PriorityLevel;
use Illuminate\Support\Collection;
use Livewire\Component;

class Claimed extends Component
{
    use TicketsByStaffWithSameTemplates;

    public Collection|array $claimedTickets = [];
    public string $searchTicket = "";
    public ?int $priorityLevelId = null;
    public ?string $priorityLevelName = null;
    public Collection $priorityLevels;

    public function mount()
    {
        $this->priorityLevels = PriorityLevel::orderBy('value')->get(['id', 'name']);
    }

    public function clearSearchTicket()
    {
        $this->searchTicket = "";
        $this->priorityLevelId = null;
        $this->priorityLevelName = null;
    }

    public function filterPriorityLevel(PriorityLevel $priorityLevel)
    {
        $this->priorityLevelId = $priorityLevel->id;
        $this->priorityLevelName = $priorityLevel->name;
    }

    public function filterAllPriorityLevels()
    {
        $this->priorityLevelId = null;
        $this->priorityLevelName = null;
    }

    public function render()
    {
        $this->claimedTickets = $this->getClaimedTickets()->filter(function ($ticket) {
            $matchSearch = stripos($ticket->ticket_number, $this->searchTicket) !== false
                || stripos($ticket->subject, $this->searchTicket) !== false
                || stripos($ticket->branch->name, $this->searchTicket) !== false;

            $matchesPriority = $this->priorityLevelId ? $ticket->priority_level_id == $this->priorityLevelId : true;

            return $matchSearch && $matchesPriority;
        });

        return view('livewire.staff.ticket-status.claimed', [
            'claimedTickets' => $this->claimedTickets
        ]);
    }
}
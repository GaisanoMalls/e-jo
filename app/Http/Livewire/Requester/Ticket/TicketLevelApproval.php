<?php

namespace App\Http\Livewire\Requester\Ticket;

use App\Models\Ticket;
use App\Models\TicketApproval;
use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Component;

class TicketLevelApproval extends Component
{
    public Ticket $ticket;
    public Collection $approvers;
    public Collection $ticketApprovals;
    public array $approvalLevels = [1, 2, 3, 4, 5];

    public function mount()
    {
        $this->ticketApprovals = TicketApproval::with('helpTopicApprover')
            ->withWhereHas('ticket', fn($ticket) => $ticket->where('id', $this->ticket->id))
            ->get();
    }

    public function fetchedApprovers(int $level)
    {
        return User::with('profile')->withWhereHas('helpTopicApprovals', function ($query) use ($level) {
            $query->where('level', $level)
                ->withWhereHas('configuration', function ($config) {
                    $config->with('approvers')
                        ->where('help_topic_id', $this->ticket->help_topic_id)
                        ->where('bu_department_id', $this->ticket->user->buDepartments->pluck('id')->first());
                });
        })->get();
    }

    public function render()
    {
        return view('livewire.requester.ticket.ticket-level-approval');
    }
}

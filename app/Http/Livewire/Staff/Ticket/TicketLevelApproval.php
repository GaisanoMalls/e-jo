<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Http\Traits\Utils;
use App\Models\Ticket;
use App\Models\TicketApproval;
use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Component;

class TicketLevelApproval extends Component
{
    use Utils;

    public Ticket $ticket;
    public Collection $approvers;
    public Collection $ticketApprovals;
    public array $approvalLevels = [1, 2, 3, 4, 5];

    protected $listeners = ['loadLevelOfApproval' => 'render'];

    public function mount()
    {
        $this->ticketApprovals = TicketApproval::withWhereHas('helpTopicApprover', fn($approver) => $approver->whereIn('level', $this->approvalLevels))
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

    public function islevelApproved(int $level)
    {
        return TicketApproval::where('is_approved', true)
            ->withWhereHas('helpTopicApprover', fn($approver) => $approver->where('level', $level)
                ->where('help_topic_id', $this->ticket->help_topic_id))
            ->withWhereHas('ticket', fn($ticket) => $ticket->where('id', $this->ticket->id))
            ->exists();
    }

    private function actionOnSubmit()
    {
        $this->emit('loadLevelOfApproval');
        $this->emit('loadTicketLogs');
    }

    public function render()
    {
        return view('livewire.staff.ticket.ticket-level-approval');
    }
}

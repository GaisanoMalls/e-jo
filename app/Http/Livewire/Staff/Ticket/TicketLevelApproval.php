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
        $this->ticketApprovals = TicketApproval::where('ticket_id', $this->ticket->id)
            ->withWhereHas('helpTopicApprover', function ($approver) {
                $approver->whereIn('level', $this->approvalLevels);
            })->get();
    }

    public function fetchedApprovers(int $level)
    {
        return User::with('profile')->withWhereHas('helpTopicApprovals', function ($query) use ($level) {
            $query->where('level', $level)
                ->withWhereHas('configuration', function ($config) {
                    $config->with('approvers')
                        ->where('help_topic_id', $this->ticket->help_topic_id)
                        ->where('bu_department_id', $this->ticket->user?->buDepartments->pluck('id')->first());
                });
        })->get();
    }

    public function isApprovalApproved()
    {
        return TicketApproval::where([
            ['ticket_id', $this->ticket->id],
            ['is_approved', true],
        ])
            ->withWhereHas('helpTopicApprover', fn($approver)
                =>
                $approver->where('help_topic_id', $this->ticket->help_topic_id))
            ->exists();
    }

    public function islevelApproved(int $level)
    {
        return TicketApproval::where([
            ['ticket_id', $this->ticket->id],
            ['is_approved', true],
        ])
            ->withWhereHas('helpTopicApprover', fn($approver) =>
                $approver->where('level', $level)
                    ->where('help_topic_id', $this->ticket->help_topic_id))
            ->exists();
    }

    private function triggerEvents()
    {
        $events = [
            'loadLevelOfApproval',
            'loadTicketLogs',
        ];

        foreach ($events as $event) {
            $this->emit($event);
        }
    }

    private function actionOnSubmit()
    {
        $this->triggerEvents();
    }

    public function render()
    {
        return view('livewire.staff.ticket.ticket-level-approval');
    }
}

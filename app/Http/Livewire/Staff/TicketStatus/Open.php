<?php

namespace App\Http\Livewire\Staff\TicketStatus;

use App\Enums\ApprovalStatusEnum;
use App\Http\Traits\TicketsByStaffWithSameTemplates;
use App\Models\ActivityLog;
use App\Models\PriorityLevel;
use App\Models\Status;
use App\Models\Ticket;
use Illuminate\Support\Collection;
use Livewire\Component;

class Open extends Component
{
    use TicketsByStaffWithSameTemplates;

    public Collection|array $openTickets = [];
    public string $searchTicket = "";
    public ?int $priorityLevelId = null;
    public ?string $priorityLevelName = null;
    public Collection $priorityLevels;

    public function mount()
    {
        $this->priorityLevels = PriorityLevel::orderBy('value')->get(['id', 'name']);
    }

    private function isRequestersServiceDepartmentAdmin(Ticket $ticket)
    {
        return $ticket->withWhereHas('user', function ($requester) {
            $requester->withWhereHas('buDepartments', function ($department) {
                $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray());
            });
        })->exists();
    }

    public function seenTicket(Ticket $ticket)
    {
        if (
            $ticket->status_id != Status::VIEWED
            && $ticket->approval_status != ApprovalStatusEnum::APPROVED
            && $this->isRequestersServiceDepartmentAdmin($ticket)
            || !$ticket->whereDoesntHave('recommendations')
        ) {
            $ticket->update(['status_id' => Status::VIEWED]);
            ActivityLog::make(ticket_id: $ticket->id, description: 'seen the ticket');

            auth()->user()->notifications->each(function ($notification) use ($ticket) {
                if ($notification->data['ticket']['id'] == $ticket->id) {
                    $notification->markAsRead();
                }
            });
        }

        return redirect()->route('staff.ticket.view_ticket', $ticket->id);
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
        $this->openTickets = $this->getOpenTickets()->filter(function ($ticket) {
            $matchSearch = stripos($ticket->ticket_number, $this->searchTicket) !== false
                || stripos($ticket->subject, $this->searchTicket) !== false
                || stripos($ticket->branch->name, $this->searchTicket) !== false;

            $matchesPriority = $this->priorityLevelId ? $ticket->priority_level_id == $this->priorityLevelId : true;

            return $matchSearch && $matchesPriority;
        });

        return view('livewire.staff.ticket-status.open', [
            'openTickets' => $this->openTickets
        ]);
    }
}
<?php

namespace App\Http\Livewire\Staff\TicketStatus;

use App\Enums\ApprovalStatusEnum;
use App\Http\Traits\TicketsByStaffWithSameTemplates;
use App\Http\Traits\Utils;
use App\Models\ActivityLog;
use App\Models\Status;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class Open extends Component
{
    use TicketsByStaffWithSameTemplates;

    public bool $allOpenTickets = true;
    public bool $withPr = false;
    public bool $withoutPr = false;
    public $openTickets = [];

    public function mount()
    {
        $this->openTickets = $this->loadOpenTickets();
    }

    public function filterAllOpenTickets()
    {
        $this->withPr = false;
        $this->withoutPr = false;
        $this->allOpenTickets = true;
        $this->loadOpenTickets();
    }

    public function filterOpenTicketsWithPr()
    {
        $this->allOpenTickets = false;
        $this->withoutPr = false;
        $this->withPr = true;
        $this->loadOpenTickets();
    }

    public function filterOpenTicketsWithoutPr()
    {
        $this->allOpenTickets = false;
        $this->withPr = false;
        $this->withoutPr = true;
        $this->loadOpenTickets();
    }

    public function loadOpenTickets()
    {
        if ($this->allOpenTickets) {
            $this->openTickets = $this->getOpenTickets();
        }

        if ($this->withPr) {
            $this->openTickets = Ticket::where(fn(Builder $statusQuery) => $statusQuery->where('status_id', Status::OPEN)->where('approval_status', ApprovalStatusEnum::FOR_APPROVAL))
                ->where(fn(Builder $byUserQuery) => $byUserQuery->withWhereHas('user.branches', fn(Builder $query) => $query->orWhereIn('branches.id', auth()->user()->branches->pluck('id')->toArray()))
                    ->withWhereHas('user.buDepartments', fn(Builder $query) => $query->where('departments.id', auth()->user()->buDepartments->pluck('id')->first())))
                ->orWhere(fn(Builder $query) => $query->withWhereHas('specialProjectAmountApproval', fn(Builder $spAmountApproval) => $spAmountApproval->where('is_done', true)))
                ->whereHas('ticketCosting', fn(Builder $costing) => $costing->has('prFileAttachments'))
                ->orderByDesc('created_at')
                ->get();
        }

        if ($this->withoutPr) {
            $this->openTickets = Ticket::where(fn(Builder $statusQuery) => $statusQuery->where('status_id', Status::OPEN)->where('approval_status', ApprovalStatusEnum::FOR_APPROVAL))
                ->where(fn(Builder $byUserQuery) => $byUserQuery->withWhereHas('user.branches', fn(Builder $query) => $query->orWhereIn('branches.id', auth()->user()->branches->pluck('id')->toArray()))
                    ->withWhereHas('user.buDepartments', fn(Builder $query) => $query->where('departments.id', auth()->user()->buDepartments->pluck('id')->first())))
                ->orWhere(fn(Builder $query) => $query->withWhereHas('specialProjectAmountApproval', fn(Builder $spAmountApproval) => $spAmountApproval->where('is_done', true)))
                ->whereHas('ticketCosting', fn(Builder $costing) => $costing->whereDoesntHave('prFileAttachments'))
                ->orderByDesc('created_at')
                ->get();
        }

        return $this->openTickets;
    }

    public function seenTicket($id)
    {
        $ticket = Ticket::findOrFail($id);

        if ($ticket->status_id !== Status::VIEWED) {
            $ticket->update(['status_id' => Status::VIEWED]);
            ActivityLog::make($id, 'seen the ticket');
        }

        return redirect()->route('staff.ticket.view_ticket', $id);
    }

    public function render()
    {
        return view('livewire.staff.ticket-status.open');
    }
}
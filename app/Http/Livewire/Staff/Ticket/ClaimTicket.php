<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Http\Traits\AppErrorLog;
use App\Models\ActivityLog;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\TicketApproval;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ClaimTicket extends Component
{
    public Ticket $ticket;

    protected $listeners = ['loadClaimTicket' => '$refresh'];

    private function actionOnSubmit()
    {
        $this->emit('loadTicketLogs');
        $this->emit('loadClaimTicket');
        $this->emit('loadTicketDetails');
        $this->emit('loadLevelOfApproval');
        $this->emit('loadBackButtonHeader');
        $this->emit('loadCostingButtonHeader');
        $this->emit('loadTicketStatusTextHeader');
        $this->emit('loadSidebarCollapseTicketStatus');
    }

    public function claimTicket()
    {
        try {
            DB::transaction(function () {
                $existingAgentId = Ticket::where('id', $this->ticket->id)->value('agent_id');

                if (!is_null($existingAgentId)) {
                    noty()->addError('Ticket has already been claimed by another agent. Select another ticket to claim.');
                }

                $this->ticket->update([
                    'agent_id' => auth()->user()->id,
                    'status_id' => Status::CLAIMED,
                ]);

                $this->ticket->teams()->attach($this->ticket->agent->teams->pluck('id')->toArray());

                ActivityLog::make($this->ticket->id, 'claimed the ticket');
            });

            $this->actionOnSubmit();
            noty()->addSuccess("You have claimed the ticket - {$this->ticket->ticket_number}.");

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function isDoneFirstLevelApproval()
    {
        return TicketApproval::where([
            ['ticket_id', $this->ticket->id],
            ['approval_1->level_1_approver->is_approved', true],
            ['approval_1->level_2_approver->is_approved', true],
        ])->exists();
    }

    public function render()
    {
        return view('livewire.staff.ticket.claim-ticket');
    }
}
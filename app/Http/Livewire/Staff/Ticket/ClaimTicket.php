<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\ActivityLog;
use App\Models\Status;
use App\Models\Ticket;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
            Log::channel('appErrorLog')->error($e->getMessage(), [url()->full()]);
            noty()->addError('Failed to claim the ticket.');
        }
    }

    public function render()
    {
        return view('livewire.staff.ticket.claim-ticket');
    }
}
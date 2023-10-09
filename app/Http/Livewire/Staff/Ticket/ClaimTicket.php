<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\ActivityLog;
use App\Models\Status;
use App\Models\Ticket;
use Livewire\Component;

class ClaimTicket extends Component
{
    public Ticket $ticket;

    protected $listeners = ['loadClaimTicket' => '$refresh'];

    public function actionOnSubmit()
    {
        sleep(1);
        $this->emit('loadTicketLogs');
        $this->emit('loadClaimTicket');
        $this->emit('loadTicketDetails');
        $this->emit('loadBackButtonHeader');
        $this->emit('loadTicketStatusTextHeader');
    }

    public function claimTicket()
    {
        try {
            $existingAgentId = Ticket::where('id', $this->ticket->id)->value('agent_id');

            if (!is_null($existingAgentId)) {
                flash()->addError('Ticket has already been claimed by another agent. Select another ticket to claim.');
            }

            $this->ticket->update([
                'agent_id' => auth()->user()->id,
                'status_id' => Status::CLAIMED
            ]);

            ActivityLog::make($this->ticket->id, 'claimed the ticket');
            $this->actionOnSubmit();
            flash()->addSuccess("You have claimed the ticket - {$this->ticket->ticket_number}.");

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to claim the ticket.');
        }
    }

    public function render()
    {
        return view('livewire.staff.ticket.claim-ticket');
    }
}
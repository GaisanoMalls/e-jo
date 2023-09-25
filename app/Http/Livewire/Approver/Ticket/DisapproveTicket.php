<?php

namespace App\Http\Livewire\Approver\Ticket;

use App\Http\Requests\Approver\StoreDisapproveTicketRequest;
use App\Models\ActivityLog;
use App\Models\ApprovalStatus;
use App\Models\Reason;
use App\Models\Status;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DisapproveTicket extends Component
{
    public Ticket $ticket;
    public $description;

    public function rules()
    {
        return (new StoreDisapproveTicketRequest())->rules();
    }

    public function disapproveTicket()
    {
        $validatedData = $this->validate();

        try {
            DB::transaction(function () use ($validatedData) {
                $reason = Reason::create([
                    'ticket_id' => $this->ticket->id,
                    'description' => $validatedData['description']
                ]);

                $reason->ticket()->where('id', $this->ticket->id)
                    ->update([
                        'status_id' => Status::CLOSED,
                        'approval_status' => ApprovalStatus::DISAPPROVED
                    ]);

                sleep(1);
                $this->emit('loadReason');
                $this->emit('loadTicketLogs');
                $this->emit('loadApprovalButtonHeader');
                $this->emit('loadTicketStatusHeaderText');
                $this->dispatchBrowserEvent('close-modal');
                $this->reset('description');
                ActivityLog::make($this->ticket->id, 'disapproved the ticket');
            });

            flash()->addSuccess('The ticket has been disapproved.');

        } catch (\Exception $e) {
            flash()->addError('Faild to disapprove the ticket.');
        }
    }

    public function render()
    {
        return view('livewire.approver.ticket.disapprove-ticket');
    }
}
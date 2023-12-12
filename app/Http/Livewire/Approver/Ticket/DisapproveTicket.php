<?php

namespace App\Http\Livewire\Approver\Ticket;

use App\Http\Requests\Approver\StoreDisapproveTicketRequest;
use App\Models\ActivityLog;
use App\Models\ApprovalStatus;
use App\Models\Reason;
use App\Models\Status;
use App\Models\Ticket;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DisapproveTicket extends Component
{
    public Ticket $ticket;
    public $reasonDescription;

    public function rules()
    {
        return (new StoreDisapproveTicketRequest())->rules();
    }

    private function actionOnSubmit()
    {
        $this->emit('loadReason');
        $this->emit('loadTicketLogs');
        $this->emit('loadTicketDetails');
        $this->emit('loadBackButtonHeader');
        $this->emit('loadApprovalButtonHeader');
        $this->emit('loadTicketStatusHeaderText');
        $this->dispatchBrowserEvent('close-modal');
        $this->reset('reasonDescription');
    }

    public function disapproveTicket()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $reason = Reason::create([
                    'ticket_id' => $this->ticket->id,
                    'description' => $this->reasonDescription,
                ]);

                $reason->ticket()->where('id', $this->ticket->id)
                    ->update([
                        'status_id' => Status::CLOSED,
                        'approval_status' => ApprovalStatus::DISAPPROVED,
                    ]);

                ActivityLog::make($this->ticket->id, 'disapproved the ticket');
            });

            $this->actionOnSubmit();
            flash()->addSuccess('The ticket has been disapproved.');

        } catch (Exception $e) {
            dump($e->getMessage());
            flash()->addError('Faild to disapprove the ticket.');
        }
    }

    public function render()
    {
        return view('livewire.approver.ticket.disapprove-ticket');
    }
}

<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\Ticket;
use App\Models\TicketCosting as Costing;
use App\Models\TicketCostingFile;
use Livewire\Component;

class TicketCosting extends Component
{
    public Ticket $ticket;
    public $editingFieldId;
    public $amount;

    protected $listeners = ['loadTicketCosting' => '$refresh'];

    public function toggleEditCostingAmount(Costing $costing)
    {
        $ticketCosting = $costing->where('ticket_id', $this->ticket->id)->select('id', 'amount')->first();

        if ($ticketCosting) {
            $this->editingFieldId = $ticketCosting->id == $this->editingFieldId ? null : $ticketCosting->id;
            $this->amount = $ticketCosting->amount;
        } else {
            noty()->addError('Ticket costing not found.');
        }

        $this->resetValidation();
    }

    public function updateTicketCosting()
    {
        $this->validate(['amount' => ['required', 'numeric']]);

        $this->ticket->ticketCosting->update(['amount' => $this->amount]);
        $this->editingFieldId = null;
        $this->amount = null;
    }

    public function isCostingGreaterOrEqual()
    {
        return $this->ticket->ticketCosting?->amount >= $this->ticket->helpTopic->specialProject?->amount;
    }

    public function deleteCostingAttachent(TicketCostingFile $ticketCostingFile)
    {
        $ticketCostingFile->delete();
    }

    public function render()
    {
        return view('livewire.staff.ticket.ticket-costing', [
            'isCostingGreaterOrEqual' => $this->isCostingGreaterOrEqual(),
        ]);
    }
}

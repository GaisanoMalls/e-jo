<?php

namespace App\Http\Livewire\Approver\Ticket;

use App\Models\Ticket;
use Livewire\Component;

class LoadApprovalButtonsHeader extends Component
{
    public Ticket $ticket;

    protected $listeners = ['loadApprovalButtonHeader' => 'render'];

    public function render()
    {
        return view('livewire.approver.ticket.load-approval-buttons-header');
    }
}
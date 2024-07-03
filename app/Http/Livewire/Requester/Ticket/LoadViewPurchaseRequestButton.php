<?php

namespace App\Http\Livewire\Requester\Ticket;

use App\Models\Ticket;
use Livewire\Component;

class LoadViewPurchaseRequestButton extends Component
{
    public Ticket $ticket;

    public $customFormData = [];

    public function loadCustomForm()
    {
        $this->emit('getCustomFormData');
    }

    public function render()
    {
        return view('livewire.requester.ticket.load-view-purchase-request-button');
    }
}

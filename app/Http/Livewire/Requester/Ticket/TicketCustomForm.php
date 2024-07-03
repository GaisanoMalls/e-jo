<?php

namespace App\Http\Livewire\Requester\Ticket;

use App\Models\Ticket;
use Livewire\Component;

class TicketCustomForm extends Component
{
    public Ticket $ticket;
    public $customFormFields;

    protected $listeners = ['getCustomFormData' => 'customFormData'];

    public function customFormData()
    {
        $this->customFormFields = $this->ticket;
    }

    public function render()
    {
        return view('livewire.requester.ticket.ticket-custom-form');
    }
}

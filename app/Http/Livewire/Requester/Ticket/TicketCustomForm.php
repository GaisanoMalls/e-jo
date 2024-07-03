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
        $this->customFormFields = $this->ticket->helpTopic->form->customFields->map(function ($field) {
            return [
                'id' => $field->id,
                'name' => $field->name,
                'label' => $field->label,
                'type' => $field->type,
                'variable_name' => $field->variable_name,
                'is_required' => $field->is_required,
                'is_enabled' => $field->is_enabled,
                'value' => null, // To store the value of the given inputs
            ];
        })->toArray();
    }

    public function render()
    {
        return view('livewire.requester.ticket.ticket-custom-form');
    }
}

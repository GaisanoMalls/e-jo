<?php

namespace App\Http\Livewire\Requester\Ticket;

use App\Models\FieldHeaderValue;
use App\Models\FieldRowValue;
use App\Models\Ticket;
use Livewire\Component;

class TicketCustomForm extends Component
{
    public Ticket $ticket;
    public array $customFormHeaderFields = [];
    public array $customFormRowFields = [];

    protected $listeners = ['remountRequesterCustomForm' => 'mount'];

    public function mount()
    {
        $this->customFormHeaderFields = FieldHeaderValue::with('field')
            ->where('ticket_id', $this->ticket->id)
            ->get()
            ->toArray();

        $this->customFormRowFields = FieldRowValue::with('field')
            ->where('ticket_id', $this->ticket->id)
            ->get()
            ->toArray();
    }

    public function getFilteredRowFields()
    {
        $headers = [];
        $fields = [];
        foreach ($this->customFormRowFields as $fieldData) {
            $fieldName = $fieldData['field']['name'];
            $rowId = $fieldData['row'];

            if (!isset($fields[$rowId])) {
                $fields[$rowId] = [];
            }

            if (!in_array($fieldName, $headers)) {
                $headers[] = $fieldName;
            }

            $fields[$rowId][$fieldName] = $fieldData['value'];
        }

        return ['headers' => $headers, 'fields' => $fields];
    }

    public function render()
    {
        return view('livewire.requester.ticket.ticket-custom-form');
    }
}

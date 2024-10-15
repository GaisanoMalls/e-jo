<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\FieldHeaderValue;
use App\Models\FieldRowValue;
use App\Models\Recommendation;
use App\Models\Ticket;
use Livewire\Component;

class TicketCustomForm extends Component
{
    public Ticket $ticket;
    public ?Recommendation $recommendationServiceDeptAdmin = null;
    public array $customFormHeaderFields = [];
    public array $customFormRowFields = [];

    protected $listeners = ['loadCustomForm' => '$refresh'];

    public function mount()
    {
        $this->customFormHeaderFields = FieldHeaderValue::with('field')->where('ticket_id', $this->ticket->id)->get()->toArray();
        $this->customFormRowFields = FieldRowValue::with('field')->where('ticket_id', $this->ticket->id)->get()->toArray();
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

        sort($headers);

        return ['headers' => $headers, 'fields' => $fields];
    }

    public function render()
    {
        $this->recommendationServiceDeptAdmin = Recommendation::with('requestedByServiceDeptAdmin.profile')
            ->where('ticket_id', $this->ticket->id)
            ->first();

        return view('livewire.staff.ticket.ticket-custom-form');
    }
}

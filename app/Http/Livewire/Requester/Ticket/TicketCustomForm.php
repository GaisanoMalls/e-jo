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

    /**
     * Organizes custom form row fields into a structured format for display.
     *
     * Processes the customFormRowFields property to generate:
     * - A unique list of field names (headers)
     * - A structured array of row data with field values grouped by row ID
     *
     * The resulting format is optimized for tabular display where:
     * - Headers represent column names
     * - Each row contains field values keyed by field names
     *
     * @return array Returns an associative array with two keys:
     *              - 'headers': Array of unique field names (columns)
     *              - 'fields': Nested array where keys are row IDs and values are field-value pairs
     */
    public function getFilteredRowFields()
    {
        // Initialize empty arrays for headers and organized fields
        $headers = [];
        $fields = [];

        // Process each field in customFormRowFields
        foreach ($this->customFormRowFields as $fieldData) {
            $fieldName = $fieldData['field']['name']; // Get field name
            $rowId = $fieldData['row']; // Get row identifier

            // Initialize row array if it doesn't exist
            if (!isset($fields[$rowId])) {
                $fields[$rowId] = [];
            }

            // Add field name to headers if not already present
            if (!in_array($fieldName, $headers)) {
                $headers[] = $fieldName;
            }

            // Store field value in the appropriate row
            $fields[$rowId][$fieldName] = $fieldData['value'];
        }

        // Return structured data for display
        return ['headers' => $headers, 'fields' => $fields];
    }

    public function render()
    {
        return view('livewire.requester.ticket.ticket-custom-form');
    }
}

<?php

namespace App\Http\Livewire\Approver\Ticket;

use App\Enums\RecommendationApprovalStatusEnum;
use App\Models\FieldHeaderValue;
use App\Models\FieldRowValue;
use App\Models\Recommendation;
use App\Models\Ticket;
use Livewire\Component;

class TicketCustomForm extends Component
{

    public Ticket $ticket;
    public ?Recommendation $recommendation = null;
    public array $customFormHeaderFields = [];
    public array $customFormRowFields = [];

    protected $listeners = ['remountRequesterCustomForm' => 'mount'];

    public function mount()
    {
        $this->recommendation = Recommendation::where('ticket_id', $this->ticket->id)->first();

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
     * Organizes custom form row fields into a structured format with headers and row-based values.
     *
     * Processes the customFormRowFields property to generate:
     * - A unique list of field names as headers
     * - A structured array of row data with field values grouped by row ID
     *
     * The resulting array structure is useful for display purposes, such as generating tables
     * where each row represents a form row and columns represent the field values.
     *
     * @return array Returns an associative array with two keys:
     *               - 'headers': Array of unique field names (columns)
     *               - 'fields': Nested array where:
     *                 - First level keys are row IDs
     *                 - Second level contains field-value pairs for each row
     *
     * @example
     * [
     *     'headers' => ['name', 'email', 'phone'],
     *     'fields' => [
     *         1 => ['name' => 'John', 'email' => 'john@example.com', 'phone' => '12345'],
     *         2 => ['name' => 'Jane', 'email' => 'jane@example.com', 'phone' => '67890']
     *     ]
     * ]
     */
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
        return view('livewire.approver.ticket.ticket-custom-form');
    }
}

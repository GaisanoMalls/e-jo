<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\Ticket;
use App\Models\TicketCustomFormField;
use App\Models\TicketCustomFormFile;
use Illuminate\Support\Collection;
use Livewire\Component;

class TicketCustomForm extends Component
{
    public Ticket $ticket;
    public ?Collection $ticketCustomFormField = null;
    public ?Collection $customFormFields = null;
    public ?Collection $customFormImageFiles = null;
    public ?Collection $customFormDocumentFiles = null;

    public function mount()
    {
        $this->customFormData();
    }

    public function getCustomFormFieldsWithValues()
    {
        $this->customFormFields = $this->ticketCustomFormField->map(function ($field) {
            return [
                'id' => $field->id,
                'name' => $field->name,
                'label' => $field->label,
                'type' => $field->type,
                'variable_name' => $field->variable_name,
                'is_required' => $field->is_required,
                'is_enabled' => $field->is_enabled,
                'value' => $field->value, // To store the value of the given inputs
            ];
        });
    }

    public function customFormData()
    {
        $this->ticketCustomFormField = TicketCustomFormField::with('ticketCustomFormFiles')->where([
            ['ticket_id', $this->ticket->id],
            ['form_id', $this->ticket->helpTopic->form?->id],
        ])->get(); // Return a single record.

        if ($this->ticketCustomFormField) {
            $customFormFiles = TicketCustomFormFile::with('ticketCustomFormField')
                ->whereIn('ticket_custom_form_field_id', $this->ticketCustomFormField->pluck('id')->toArray())
                ->get();

            $this->customFormImageFiles = $customFormFiles->filter(function ($field) {
                $imageExtensions = ['jpg', 'jpeg', 'png'];
                $fileExtension = strtolower(pathinfo($field->file_attachment, PATHINFO_EXTENSION));
                return in_array($fileExtension, $imageExtensions);
            });

            $this->customFormDocumentFiles = $customFormFiles->filter(function ($field) {
                $documentExtensions = ['pdf', 'doc', 'docx', 'xlsx', 'xls', 'csv'];
                $fileExtension = strtolower(pathinfo($field->file_attachment, PATHINFO_EXTENSION));
                return in_array($fileExtension, $documentExtensions);
            });

            $this->getCustomFormFieldsWithValues();
        }
    }

    public function loadCustomFormFields()
    {
        return $this->customFormFields;
    }

    public function render()
    {
        return view('livewire.staff.ticket.ticket-custom-form');
    }
}

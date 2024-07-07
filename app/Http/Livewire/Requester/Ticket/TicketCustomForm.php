<?php

namespace App\Http\Livewire\Requester\Ticket;

use App\Http\Traits\AppErrorLog;
use App\Models\Ticket;
use App\Models\TicketCustomFormField;
use App\Models\TicketCustomFormFile;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class TicketCustomForm extends Component
{
    public Ticket $ticket;
    public TicketCustomFormField $ticketCustomFormField;
    public Collection $customFormFields;
    public Collection $customFormImageFiles;
    public Collection $customFormDocumentFiles;

    protected $listeners = [
        'getCustomFormData' => 'customFormData',
        'loadCustomFormFiles' => 'loadCustomFormFiles'
    ];

    public function mount()
    {
        $this->customFormFields = collect();
        $this->customFormImageFiles = collect();
        $this->customFormDocumentFiles = collect();
    }

    public function customFormData()
    {
        $ticketCustomFormField = TicketCustomFormField::with('ticketCustomFormFiles')->where([
            ['ticket_id', $this->ticket->id],
            ['form_id', $this->ticket->helpTopic->form->id],
        ])->get(); // Return a single data only.

        if ($ticketCustomFormField) {
            $customFormFiles = TicketCustomFormFile::with('ticketCustomFormField')->whereIn('ticket_custom_form_field_id', $ticketCustomFormField->pluck('id')->toArray())->get();

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

            $this->customFormFields = $ticketCustomFormField->map(function ($field) {
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
    }

    public function saveCustomFormField()
    {
        //
    }

    public function loadCustomFormFields()
    {
        return $this->customFormFields;
    }

    public function loadCustomFormFiles()
    {
        $this->customFormImageFiles;
        $this->customFormDocumentFiles;
    }

    public function deleteCustomFormFile(TicketCustomFormFile $file)
    {
        try {
            if ($file->file_attachment && Storage::exists($file->file_attachment)) {
                $file->delete();
                Storage::delete($file->file_attachment);
                $this->emitSelf('loadCustomFormFiles'); // Reload the file attachments
            }
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.requester.ticket.ticket-custom-form');
    }
}

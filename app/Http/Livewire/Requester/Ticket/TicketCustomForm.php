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
use Livewire\WithFileUploads;

class TicketCustomForm extends Component
{
    use WithFileUploads;

    public Ticket $ticket;
    public ?Collection $ticketCustomFormField = null;
    public ?Collection $customFormFields = null;
    public ?Collection $customFormImageFiles = null;
    public ?Collection $customFormDocumentFiles = null;
    public bool $isEditing = false;

    protected $listeners = [
        'loadCustomFormFiles' => 'loadCustomFormFiles'
    ];

    public function mount()
    {
        $this->customFormData();
    }

    public function customFormData()
    {
        $this->ticketCustomFormField = TicketCustomFormField::with('ticketCustomFormFiles')->where([
            ['ticket_id', $this->ticket->id],
            ['form_id', $this->ticket->helpTopic->form->id],
        ])->get(); // Return a single record.

        if ($this->ticketCustomFormField) {
            $customFormFiles = TicketCustomFormFile::with('ticketCustomFormField')->whereIn('ticket_custom_form_field_id', $this->ticketCustomFormField->pluck('id')->toArray())->get();

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
    }

    public function saveCustomFormField()
    {
        try {
            $ticketCustomFormField = TicketCustomFormField::where([
                ['ticket_id', $this->ticket->id],
                ['form_id', $this->ticket->helpTopic->form->id],
            ])->first();

            foreach ($this->customFormFields as $field) {
                dump($field);
                if ($field['type'] === 'file' && is_array($field['value'])) {
                    // foreach ($field['value'] as $uploadedCustomFile) {
                    //     dump($uploadedCustomFile);
                    // }
                }
            }
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
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

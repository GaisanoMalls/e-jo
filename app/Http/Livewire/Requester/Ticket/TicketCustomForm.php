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
    public Collection $customFormFields;
    public Collection $customFormFiles;
    protected $listeners = [
        'getCustomFormData' => 'customFormData',
        'loadCustomFormFiles' => 'loadCustomFormFiles'
    ];

    public function mount()
    {
        $this->customFormFields = collect();
        $this->customFormFiles = collect();
    }

    public function customFormData()
    {
        $queryCustomFormField = TicketCustomFormField::with('ticketCustomFormFiles')->where([
            ['ticket_id', $this->ticket->id],
            ['form_id', $this->ticket->helpTopic->form->id],
        ])->get();

        if ($queryCustomFormField) {
            $this->customFormFiles = TicketCustomFormFile::with('ticketCustomFormField')->whereIn('ticket_custom_form_field_id', $queryCustomFormField->pluck('id')->toArray())->get();
            $this->customFormFields = $queryCustomFormField->map(function ($field) {
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

    public function loadCustomFormFiles()
    {
        $this->customFormFiles;
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

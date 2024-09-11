<?php

namespace App\Http\Livewire\Requester\Ticket;

use App\Models\FieldHeaderValue;
use App\Models\FieldRowValue;
use App\Models\IctRecommendation;
use App\Models\Ticket;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithFileUploads;

class TicketCustomForm extends Component
{
    use WithFileUploads;

    public Ticket $ticket;
    public ?IctRecommendation $ictRecommendationServiceDeptAdmin = null;
    public Collection $customFormHeaderFields;
    public Collection $customFormRowFields;

    protected $listeners = ['remountRequesterCustomForm' => 'mount'];

    public function mount()
    {
        $this->ictRecommendationServiceDeptAdmin = IctRecommendation::where('ticket_id', $this->ticket->id)->first();
        $this->customFormHeaderFields = FieldHeaderValue::with('field')->where('ticket_id', $this->ticket->id)->get();
        $this->customFormRowFields = FieldRowValue::with('field')->where('ticket_id', $this->ticket->id)->get();
        dd($this->getFilteredRowFields());
    }

    public function getFilteredRowFields()
    {
        $filteredFields = [];
        foreach ($this->customFormRowFields->toArray() as $headerFields) {
            // Check if 'field' key exists and is an array
            if (isset($headerFields['field']) && is_array($headerFields['field'])) {
                // Extract 'name' from 'field' if it exists
                $fieldName = $headerFields['field']['name'] ?? null;
                if ($fieldName) {
                    $headers[] = $fieldName;
                }
            }
        }

        return ['headers' => $headers, 'fields' => $filteredFields];
    }

    public function isTicketIctRecommendationIsApproved()
    {
        return IctRecommendation::where([
            ['ticket_id', $this->ticket->id],
            ['is_approved', true]
        ])->exists();
    }

    public function isRecommendationRequested()
    {
        return IctRecommendation::where([
            ['ticket_id', $this->ticket->id],
            ['is_requesting_ict_approval', true],
        ])->exists();
    }

    public function render()
    {
        return view('livewire.requester.ticket.ticket-custom-form');
    }
}

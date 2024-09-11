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
    }

    public function getFilteredRowFields()
    {
        $headers = [];
        $filteredFields = [];

        foreach ($this->customFormRowFields->toArray() as $headerFields) {
            if (isset($headerFields['field']) && is_array($headerFields['field'])) {
                $fieldName = $headerFields['field']['name'];

                if ($fieldName) {
                    if (!isset($filteredFields[$fieldName])) {
                        $filteredFields[$fieldName] = [];
                    }

                    $filteredFields[$fieldName][][] = $headerFields;

                    if (!in_array($fieldName, $headers)) {
                        $headers[] = $fieldName;
                    }
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

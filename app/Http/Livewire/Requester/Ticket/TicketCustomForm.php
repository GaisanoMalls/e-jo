<?php

namespace App\Http\Livewire\Requester\Ticket;

use App\Enums\RecommendationApprovalStatusEnum;
use App\Models\FieldHeaderValue;
use App\Models\FieldRowValue;
use App\Models\Recommendation;
use App\Models\Ticket;
use Livewire\Component;
use Livewire\WithFileUploads;

class TicketCustomForm extends Component
{
    use WithFileUploads;

    public Ticket $ticket;
    public ?Recommendation $recommendationServiceDeptAdmin = null;
    public array $customFormHeaderFields = [];
    public array $customFormRowFields = [];

    protected $listeners = ['remountRequesterCustomForm' => 'mount'];

    public function mount()
    {
        $this->recommendationServiceDeptAdmin = Recommendation::where('ticket_id', $this->ticket->id)->first();

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

        sort($headers);

        return ['headers' => $headers, 'fields' => $fields];
    }

    public function isTicketRecommendationIsApproved()
    {
        return Recommendation::where([
            ['ticket_id', $this->ticket->id],
            ['approval_status', RecommendationApprovalStatusEnum::APPROVED]
        ])->exists();
    }

    public function isRecommendationRequested()
    {
        return Recommendation::where([
            ['ticket_id', $this->ticket->id],
            ['is_requesting_ict_approval', true],
        ])->exists();
    }

    public function render()
    {
        return view('livewire.requester.ticket.ticket-custom-form');
    }
}

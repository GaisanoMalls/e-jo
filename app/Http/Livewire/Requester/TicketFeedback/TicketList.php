<?php

namespace App\Http\Livewire\Requester\TicketFeedback;

use App\Http\Traits\AppErrorLog;
use App\Models\Feedback;
use App\Models\Status;
use App\Models\Ticket;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class TicketList extends Component
{
    use AppErrorLog;

    public ?Ticket $ticket = null;
    public ?int $rating = null;
    public ?Collection $toRateTickets = null;
    public ?string $ticketNumber = null;
    public ?string $fullName = null;
    public ?string $email = null;
    public ?string $feedback = null;
    public ?string $suggestion = null;
    public ?string $hadIssuesEncountered = null;

    protected $listeners = ['loadTicketsForFeedback' => '$refresh'];

    public function mount()
    {
        $this->email = auth()->user()->email;
        $this->fullName = auth()->user()->profile->getFullName;
    }

    public function rules()
    {
        return [
            'rating' => 'required',
            'hadIssuesEncountered' => 'required',
            'feedback' => 'required',
            'suggestion' => 'nullable',
        ];
    }

    public function giveFeedback(Ticket $ticket)
    {
        $this->ticket = $ticket;
        $this->ticketNumber = $ticket->ticket_number;
    }

    public function cancel()
    {
        $this->reset();
    }

    public function submitFeedback()
    {
        $this->validate();

        try {
            Feedback::create([
                'user_id' => auth()->user()->id,
                'ticket_id' => $this->ticket->id,
                'rating' => $this->rating,
                'had_issues_encountered' => $this->hadIssuesEncountered,
                'description' => $this->feedback,
                'suggestion' => $this->suggestion,
            ]);

            $this->reset();
            $this->emit('loadTicketsForFeedback');
            $this->dispatchBrowserEvent('close-feedback-modal');

        } catch (Exception $e) {
            Log::error($e->getMessage());
            AppErrorLog::getError($e->getMessage(), false);
        }
    }

    public function render()
    {
        $this->toRateTickets = Ticket::where('status_id', Status::CLOSED)
            ->whereDoesntHave('feedback')
            ->where('user_id', auth()->user()->id)
            ->orderByDesc('created_at')
            ->get();

        return view('livewire.requester.ticket-feedback.ticket-list', [
            'toRateTickets' => $this->toRateTickets,
        ]);
    }
}

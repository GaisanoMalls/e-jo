<?php

namespace App\Http\Livewire\Requester\TicketFeedback;

use App\Enums\TicketRatingEnum;
use App\Http\Traits\AppErrorLog;
use App\Models\Feedback;
use App\Models\Status;
use App\Models\Ticket;
use Exception;
use Illuminate\Support\Collection;
use Livewire\Component;

class TicketList extends Component
{
    use AppErrorLog;

    public Ticket $ticket;
    public TicketRatingEnum $rating;
    public Collection $toRateTickets;
    public ?string $ticketNumber = null;
    public ?string $fullName = null;
    public ?string $email = null;
    public ?string $feedback = null;
    public ?string $suggestion = null;
    public bool $had_issues_encountered = false;

    public function mount()
    {
        $this->email = auth()->user()->email;
        $this->fullName = auth()->user()->profile->getFullName();
        $this->toRateTickets = Ticket::where('status_id', Status::CLOSED)
            ->whereDoesntHave('feedback')
            ->where('user_id', auth()->user()->id)
            ->orderByDesc('created_at')
            ->get();
    }

    public function rules()
    {
        return [
            'rating' => 'required|in:1,2,3,4,5',
            'had_issues_encountered' => 'required',
            'feedback' => 'required',
            'suggestion' => 'required',
        ];
    }

    public function giveFeedback(Ticket $ticket)
    {
        $this->ticket = $ticket->id;
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
            if (!Feedback::where('ticket_id', $this->ticket)->exists()) {
                Feedback::where('ticket_id', $this->ticket)->create([
                    'user_id' => auth()->user()->id,
                    'ticket_id' => $this->ticket,
                    'rating' => $this->rating,
                    'had_issues_encountered' => $this->had_issues_encountered,
                    'description' => $this->feedback,
                    'suggestion' => $this->suggestion,
                ]);

                $this->reset();
                $this->dispatchBrowserEvent('close-feedback-modal');
            }

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage(), false);
        }
    }

    public function render()
    {
        return view('livewire.requester.ticket-feedback.ticket-list');
    }
}

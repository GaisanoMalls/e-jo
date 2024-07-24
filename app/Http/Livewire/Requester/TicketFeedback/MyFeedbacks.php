<?php

namespace App\Http\Livewire\Requester\TicketFeedback;

use App\Enums\TicketRatingEnum;
use App\Http\Traits\AppErrorLog;
use App\Models\Feedback;
use Exception;
use Illuminate\Support\Collection;
use Livewire\Component;

class MyFeedbacks extends Component
{
    use AppErrorLog;

    public TicketRatingEnum $rating;
    public Collection $feedbacks;
    public int $userId;
    public int $feedbackId;
    public int $ticket;
    public string $ticketNumber;
    public string $fullName;
    public string $email;
    public string $feedback;
    public string $suggestion;
    public bool $had_issues_encountered = false;

    public function mount()
    {
        $this->userId = auth()->user()->id;
        $this->feedbacks = Feedback::where('user_id', auth()->user()->id)
            ->orderByDesc('created_at')
            ->get();
    }

    public function rules()
    {
        return [
            'rating' => 'required|in:1,2,3,4,5',
            'had_issues_encountered' => 'required',
            'feedback' => 'required',
        ];
    }

    public function editFeedback(Feedback $feedback)
    {
        $this->ticketNumber = $feedback->ticket->ticket_number;
        $this->ticket = $feedback->ticket->id;
        $this->fullName = auth()->user()->profile->getFullName;
        $this->email = auth()->user()->email;

        $this->feedbackId = $feedback->id;
        $this->rating = $feedback->rating;
        $this->had_issues_encountered = $feedback->had_issues_encountered;
        $this->feedback = $feedback->description;
        $this->suggestion = $feedback->suggestion;
    }

    public function updateFeedback()
    {
        $this->validate();

        try {
            Feedback::where([
                ['id', $this->feedbackId],
                ['ticket_id', $this->ticket],
                ['user_id', auth()->user()->id],
            ])->update([
                        'rating' => $this->rating,
                        'had_issues_encountered' => $this->had_issues_encountered,
                        'description' => $this->feedback,
                        'suggestion' => $this->suggestion,
                    ]);

            $this->resetExcept('userId');
            $this->dispatchBrowserEvent('close-edit-feedback-modal');

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage(), false);
        }
    }

    public function deleteFeedback(Feedback $feedback)
    {
        try {
            $feedback->delete();
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage(), false);
        }
    }

    public function cancel()
    {
        $this->resetExcept('userId');
    }

    public function render()
    {
        return view('livewire.requester.ticket-feedback.my-feedbacks');
    }
}

<?php

namespace App\Http\Livewire\Requester\TicketFeedback;

use App\Http\Traits\AppErrorLog;
use App\Models\Feedback;
use Exception;
use Livewire\Component;

class MyFeedbacks extends Component
{
    use AppErrorLog;

    public $userId;
    public $feedbackId;
    public $ticket;
    public $ticketNumber;
    public $fullName;
    public $email;
    public $rating;
    public $feedback;
    public $suggestion;
    public $had_issues_encountered;

    public function mount()
    {
        $this->userId = auth()->user()->id;
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

    public function editFeedback(Feedback $feedback)
    {
        $this->ticketNumber = $feedback->ticket->ticket_number;
        $this->ticket = $feedback->ticket->id;
        $this->fullName = auth()->user()->profile->getFullName();
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

    public function render()
    {
        $feedbacks = Feedback::where('user_id', auth()->user()->id)
            ->orderByDesc('created_at')
            ->get();

        return view('livewire.requester.ticket-feedback.my-feedbacks', [
            'feedbacks' => $feedbacks
        ]);
    }
}

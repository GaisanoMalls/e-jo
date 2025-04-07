<?php

namespace App\Http\Livewire\Requester\TicketFeedback;

use App\Http\Traits\AppErrorLog;
use App\Models\Feedback;
use Exception;
use Illuminate\Support\Collection;
use Livewire\Component;

class MyFeedbacks extends Component
{
    use AppErrorLog;

    public ?int $rating = null;
    public ?Collection $feedbacks = null;
    public ?int $userId = null;
    public ?int $feedbackId = null;
    public ?int $ticket = null;
    public ?string $ticketNumber = null;
    public ?string $fullName = null;
    public ?string $email = null;
    public ?string $feedback = null;
    public ?string $suggestion = null;
    public ?string $hadIssuesEncountered = null;

    protected $listeners = ['loadMyFeedbacks' => '$refresh'];

    public function mount()
    {
        $this->userId = auth()->user()->id;
        $this->feedbacks = Feedback::where('user_id', auth()->user()->id)
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Defines validation rules for feedback submission.
     * 
     * Specifies required fields and valid values for:
     * - Rating (numeric scale)
     * - Issue confirmation (boolean-like)
     * - Feedback comments (text)
     * 
     * @return array Array of validation rules with:
     *              - 'rating': Required, must be 1-5
     *              - 'hadIssuesEncountered': Required
     *              - 'feedback': Required text
     */
    public function rules()
    {
        return [
            'rating' => 'required|in:1,2,3,4,5',          // 5-point rating (required)
            'hadIssuesEncountered' => 'required',         // Issue flag (required)
            'feedback' => 'required',                     // Comments (required)
        ];
    }

    /**
     * Initializes feedback editing form with existing feedback data.
     * 
     * Populates form fields with values from an existing Feedback model:
     * - Ticket reference information
     * - User details
     * - Feedback content and ratings
     * 
     * @param Feedback $feedback The Feedback model being edited
     * @return void
     */
    public function editFeedback(Feedback $feedback)
    {
        // Set ticket reference information
        $this->ticketNumber = $feedback->ticket->ticket_number;
        $this->ticket = $feedback->ticket->id;

        // Populate user details
        $this->fullName = auth()->user()->profile->getFullName;
        $this->email = auth()->user()->email;

        // Load feedback content
        $this->feedbackId = $feedback->id;
        $this->rating = $feedback->rating;
        $this->hadIssuesEncountered = $feedback->had_issues_encountered;
        $this->feedback = $feedback->description;
        $this->suggestion = $feedback->suggestion;
    }

    /**
     * Updates an existing feedback record with new values.
     * 
     * Handles the complete feedback update workflow:
     * 1. Validates input data
     * 2. Updates the feedback record (with ownership verification)
     * 3. Refreshes the feedbacks list
     * 4. Resets the form
     * 5. Closes the edit modal
     * 6. Handles errors gracefully
     * 
     * Security Note: Ensures user can only update their own feedback by verifying:
     * - Feedback ID
     * - Ticket ID
     * - User ID
     */
    public function updateFeedback()
    {
        // Validate form inputs first
        $this->validate();

        try {
            // Update feedback record with ownership verification
            Feedback::where([
                ['id', $this->feedbackId],               // Match feedback ID
                ['ticket_id', $this->ticket],            // Match ticket ID
                ['user_id', auth()->user()->id],         // Verify ownership
            ])
                ->update([
                    'rating' => $this->rating,               // Update rating
                    'had_issues_encountered' => $this->hadIssuesEncountered,  // Update issue flag
                    'description' => $this->feedback,        // Update feedback text
                    'suggestion' => $this->suggestion,       // Update suggestions
                ]);

            // Refresh the feedback list component
            $this->emit('loadMyFeedbacks');

            // Reset form fields except for userId and feedbacks
            $this->resetExcept('userId', 'feedbacks');

            // Close the edit modal
            $this->dispatchBrowserEvent('close-edit-feedback-modal');

        } catch (Exception $e) {
            // Log error without showing user notification
            AppErrorLog::getError($e->getMessage(), false);
        }
    }

    /**
     * Deletes a feedback record and refreshes the feedback list.
     * 
     * Handles the feedback deletion workflow:
     * 1. Attempts to delete the specified feedback record
     * 2. Refreshes the feedback list on success
     * 3. Silently logs any errors that occur
     * 
     * Security Note: The Feedback model binding ensures only authorized users
     * can delete feedback through Laravel's implicit model binding.
     * 
     * @param Feedback $feedback The Feedback model to delete (implicitly authorized)
     * @return void
     */
    public function deleteFeedback(Feedback $feedback)
    {
        try {
            // Delete the feedback record
            $feedback->delete();

            // Refresh the feedback list component
            $this->emit('loadMyFeedbacks');

        } catch (Exception $e) {
            // Log error without user notification (silent fail)
            AppErrorLog::getError($e->getMessage(), false);
        }
    }

    /**
     * Resets the form fields except for the `userId` property.
     * 
     * Used to cancel the editing of a feedback record and reset the form to its
     * initial state.
     * 
     * @return void
     */
    public function cancel()
    {
        // Reset all fields except userId
        $this->resetExcept('userId');
    }

    public function render()
    {
        return view('livewire.requester.ticket-feedback.my-feedbacks');
    }
}

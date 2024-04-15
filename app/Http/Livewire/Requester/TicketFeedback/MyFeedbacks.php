<?php

namespace App\Http\Livewire\Requester\TicketFeedback;

use App\Models\Feedback;
use Livewire\Component;

class MyFeedbacks extends Component
{
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

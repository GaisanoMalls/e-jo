<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Requester\StoreFeedbackRequest;
use App\Models\Feedback;
use App\Models\Status;
use App\Models\Ticket;
use Exception;
use Illuminate\Support\Facades\Log;

class FeedbackController extends Controller
{
    public function index()
    {
        return view('layouts.feedback.index');
    }

    public function reviews()
    {
        $reviews = Feedback::where('user_id', auth()->user()->id)
            ->orderByDesc('created_at')
            ->get();

        return view('layouts.feedback.reviews', compact('reviews'));
    }

    public function ticketsToRate()
    {
        $closedTickets = Ticket::where('status_id', Status::CLOSED)
            ->where('user_id', auth()->user()->id)
            ->orderByDesc('created_at')
            ->get();

        return view('layouts.feedback.tickets_to_rate', compact('closedTickets'));
    }

    public function store(StoreFeedbackRequest $request, Feedback $feedback)
    {
        try {
            $feedback->create([
                'user_id' => auth()->user()->id,
                'rating' => $request->rating,
                'had_issues_encountered' => $request->had_issues_encountered,
                'description' => $request->description,
                'suggestion' => $request->suggestion,
                'accepted_privacy_policy' => (bool) $request->accepted_privacy_policy
            ]);

            return back()->with('success', 'Thank you for sending your feedback!');

        } catch (Exception $e) {
            Log::channel('appErrorLog')->error($e->getMessage(), [url()->full()]);
            return back()->with('error', 'Failed to save feedback. Please try again.');
        }
    }
}
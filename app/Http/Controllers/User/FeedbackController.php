<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\FeedbackRequest;
use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Status;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FeedbackController extends Controller
{
    public function index()
    {
        return view('layouts.feedback.index');
    }

    public function reviews()
    {
        $reviews = Feedback::where('user_id', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('layouts.feedback.reviews', compact('reviews'));
    }

    public function ticketsToRate()
    {
        $closedTickets = Ticket::where('status_id', Status::CLOSED)
            ->where('user_id', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('layouts.feedback.tickets_to_rate', compact('closedTickets'));
    }

    public function store(Request $request, Feedback $feedback)
    {
        $validator = Validator::make($request->all(), [
            'rating' => ['required'],
            'had_issues_encountered' => ['required'],
            'description' => ['required'],
            'suggestion' => ['nullable'],
            'accepted_privacy_policy' => ['required', 'boolean']
        ]);

        if ($validator->fails())
            return back()->withErrors($validator, 'storeFeedback')->withInput()
                ->with('error', 'Failed to update. There was an error while saving the feedback.');

        try {
            $feedback->create([
                'user_id' => auth()->user()->id,
                'rating' => $request->input('rating'),
                'had_issues_encountered' => $request->input('had_issues_encountered'),
                'description' => $request->input('description'),
                'suggestion' => $request->input('suggestion'),
                'accepted_privacy_policy' => (bool) $request->input('accepted_privacy_policy')
            ]);

            return back()->with('success', 'Thank you for sending your feedback!');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to save feedback. Please try again.');
        }
    }
}
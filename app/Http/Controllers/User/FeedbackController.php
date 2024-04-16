<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class FeedbackController extends Controller
{
    public function index()
    {
        return view('layouts.user.feedback.index');
    }

    public function reviews()
    {
        return view('layouts.user.feedback.reviews');
    }

    public function ticketsToRate()
    {
        return view('layouts.user.feedback.tickets_to_rate');
    }
}
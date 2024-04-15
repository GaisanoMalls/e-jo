<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class FeedbackController extends Controller
{
    public function index()
    {
        return view('layouts.feedback.index');
    }

    public function reviews()
    {
        return view('layouts.feedback.reviews');
    }

    public function ticketsToRate()
    {
        return view('layouts.feedback.tickets_to_rate');
    }
}
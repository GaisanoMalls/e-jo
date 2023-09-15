<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Traits\Requester\Tickets as RequestserTickets;

class Dashboard extends Controller
{
    public function __invoke()
    {
        return view('layouts.user.includes.dashboard');
    }
}
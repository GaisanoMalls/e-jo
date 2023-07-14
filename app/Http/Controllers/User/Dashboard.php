<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Status;
use App\Models\Ticket;
use Illuminate\Http\Request;

class Dashboard extends Controller
{
    public function index()
    {
        return view('layouts.user.base');
    }

}
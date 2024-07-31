<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RecommendationsController extends Controller
{
    public function __invoke()
    {
        return view('layouts.staff.ticket.recommendations');
    }
}

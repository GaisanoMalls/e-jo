<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Role;

class TeamsController extends Controller
{
    public function __construct()
    {
        $this->middleware([Role::systemAdmin()]);
    }

    // public function index()
    // {
    //     return view('layouts.staff.teams.teams_main');
    // }
}
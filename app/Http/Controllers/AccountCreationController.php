<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountCreationController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('layouts.auth.create_account');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('layouts.auth.forgot_password');
    }
}
<?php

namespace App\Http\Controllers;

class ForgotPasswordController extends Controller
{
    public function index()
    {
        return view('layouts.auth.forgot_password');
    }
}
<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class AccountController extends Controller
{
    public function profile()
    {
        return view('layouts.user.account.account_settings_base');
    }

    public function password()
    {
        return view('layouts.user.account.password');
    }
}
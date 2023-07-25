<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Http\Traits\AccountSettings;
use App\Http\Traits\AuthUserAccount;
use App\Models\Status;
use App\Models\Ticket;

class AccountController extends Controller
{
    use AuthUserAccount;

    public function profile()
    {
        return view('layouts.user.account.account_settings_base');
    }

    public function password()
    {
        return view('layouts.user.account.password');
    }
}
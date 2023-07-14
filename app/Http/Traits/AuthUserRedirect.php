<?php

namespace App\Http\Traits;

use App\Models\Role;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;

trait AuthUserRedirect
{
    public function redirectAuthenticatedUserWithRole()
    {
        if (Auth::check() && Auth::user()->role_id === Role::USER) {
            return redirect()->intended(RouteServiceProvider::USER_REDIRECT_URL);
        }
    }
}
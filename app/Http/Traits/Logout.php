<?php

namespace App\Http\Traits;

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

trait Logout
{
    public function doLogout($request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(RouteServiceProvider::LOGOUT_REDIRECT_URL);
    }
}
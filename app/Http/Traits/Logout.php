<?php

namespace App\Http\Traits;

use App\Providers\RouteServiceProvider;

trait Logout
{
    public function doLogout($request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(RouteServiceProvider::LOGOUT_REDIRECT_URL);
    }
}

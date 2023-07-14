<?php

namespace App\Http\Controllers;

use App\Http\Traits\AuthStaffRedirect;
use App\Http\Traits\AuthUserRedirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LandingController extends Controller
{
    use AuthStaffRedirect, AuthUserRedirect;

    public function landingPage()
    {
        if (Auth::check()) {
            return self::redirectAuthenticatedStaffWithRole() ?: $this->redirectAuthenticatedUserWithRole();
        }

        return view('layouts.auth.base');
    }
}
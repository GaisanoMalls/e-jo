<?php

namespace App\Http\Controllers;

use App\Http\Traits\AuthRedirect;
use App\Http\Traits\Logout;
use App\Http\Traits\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use Logout, AuthRedirect, Utils;

    public function login()
    {
        return $this->redirectAuthenticatedWithRole() ?: view('layouts.auth.base');
    }

    public function logout(Request $request)
    {
        return $this->doLogout($request);
    }
}
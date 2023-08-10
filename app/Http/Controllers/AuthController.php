<?php

namespace App\Http\Controllers;

use App\Http\Traits\AuthRedirect;
use App\Http\Traits\Logout;
use App\Http\Traits\ValidateLoginCredentials;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use Logout, AuthRedirect, ValidateLoginCredentials;

    public function login()
    {
        return $this->redirectAuthenticatedWithRole() ?: view('layouts.auth.base');
    }

    public function authenticate(Request $request)
    {
        $this->validateLoginCrendentials($request, 'email', 'password');

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'is_active' => true])) {
            $request->session()->regenerate();
            return $this->redirectAuthenticatedWithRole();
        }

        return back()->onlyInput('email')->with('error', 'Invalid email or password. Please try again.');
    }

    public function logout(Request $request)
    {
        return $this->doLogout($request);
    }
}
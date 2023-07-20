<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Traits\AuthUserRedirect;
use App\Http\Traits\Logout;
use App\Http\Traits\TicketNumberGenerator;
use App\Http\Traits\ValidateLoginCredentials;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthControllerUser extends Controller
{
    use Logout, AuthUserRedirect, ValidateLoginCredentials, TicketNumberGenerator;

    public function login()
    {
        return $this->redirectAuthenticatedUserWithRole() ?: view('layouts.auth.user_type.user.user_login_form');
    }

    public function authenticate(Request $request)
    {
        $this->validateLoginCrendentials($request, 'email', 'password');

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'is_active' => true])) {

            if (Auth::user()->role_id === Role::USER) {
                $request->session()->regenerate();
                return $this->redirectAuthenticatedUserWithRole();
            }

            Auth::logout();
            return back()->with('error', 'The account is not recognized as a user.');
        }

        return back()->onlyInput('email')->with('error', 'Invalid email or password. Please try again.');
    }

    public function logout(Request $request)
    {
        return $this->doLogout($request);
    }
}
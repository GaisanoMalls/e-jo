<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Traits\AuthStaffRedirect;
use App\Http\Traits\Logout;
use App\Http\Traits\ValidateLoginCredentials;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthControllerStaff extends Controller
{
    use Logout, AuthStaffRedirect, ValidateLoginCredentials;

    public function login()
    {
        return $this->redirectAuthenticatedStaffWithRole() ?: view('layouts.auth.user_type.staff.staff_login_form');
    }

    public function authenticate(Request $request)
    {
        $this->validateLoginCrendentials($request, 'email', 'password');

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'is_active' => true])) {

            if (Auth::user()->role_id === Role::USER) {
                Auth::logout();
                return back()->with('error', 'The account is not recognized as a staff.');
            }

            $request->session()->regenerate();
            return $this->redirectAuthenticatedStaffWithRole();
        }

        return back()->onlyInput('email')->with('error', 'Invalid email or password. Please try again.');
    }

    public function logout(Request $request)
    {
        return self::doLogout($request);
    }
}
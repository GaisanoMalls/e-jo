<?php

namespace App\Http\Traits;

use App\Models\Role;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;

trait AuthRedirect
{
    use Logout;
    /**
     * Redirect authenticated staffs to a route based on their roles.
     *
     * @return void
     */
    public function redirectAuthenticatedWithRole()
    {
        if (Auth::check()) {
            return match (Auth::user()->role_id) {
                Role::SYSTEM_ADMIN => redirect()->intended(RouteServiceProvider::SUPERADMIN_REDIRECT_URL),
                Role::APPROVER => redirect()->intended(RouteServiceProvider::APPROVER_REDIRECT_URL),
                Role::SERVICE_DEPARTMENT_ADMIN => redirect()->intended(RouteServiceProvider::DEPARTMENT_ADMIN_REDIRECT_URL),
                Role::AGENT => redirect()->intended(RouteServiceProvider::AGENT_REDIRECT_URL),
                Role::USER => redirect()->intended(RouteServiceProvider::USER_REDIRECT_URL),
                default => Auth::logout()->back()->with('error', 'Invalid permission. Please try again.')
            };
        }
    }
}
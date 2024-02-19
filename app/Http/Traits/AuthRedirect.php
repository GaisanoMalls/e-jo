<?php

namespace App\Http\Traits;

use App\Models\Role;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

trait AuthRedirect
{
    use Logout, Utils;
    /**
     * Redirect authenticated staffs to a route based on their roles.
     *
     * @return RedirectResponse
     */

    public function default()
    {
        Auth::logout();
        return redirect()->back()->with('error', 'Invalid permission. Please try again.');
    }

    public function redirectAuthenticatedWithRole()
    {
        if (Auth::check()) {
            return match (true) {
                Auth::user()->hasRole(Role::USER) => redirect()->intended(RouteServiceProvider::USER_REDIRECT_URL),
                Auth::user()->hasRole(Role::AGENT) => redirect()->intended(RouteServiceProvider::AGENT_REDIRECT_URL),
                Auth::user()->hasRole(Role::SYSTEM_ADMIN) => redirect()->intended(RouteServiceProvider::SUPERADMIN_REDIRECT_URL),
                Auth::user()->hasRole(Role::SERVICE_DEPARTMENT_ADMIN) => redirect()->intended(RouteServiceProvider::DEPARTMENT_ADMIN_REDIRECT_URL),
                Auth::user()->hasRole(Role::APPROVER) && !$this->costingApprover2Only() => redirect()->intended(RouteServiceProvider::APPROVER_REDIRECT_URL),
                Auth::user()->hasRole(Role::APPROVER) && $this->costingApprover2Only() => redirect()->intended(RouteServiceProvider::COSTING_APPROVER_REDIRECT_URL),
                default => $this->default()
            };
        }
    }
}

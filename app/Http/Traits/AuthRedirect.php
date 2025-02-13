<?php

namespace App\Http\Traits;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;

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
        auth()->logout();
        return redirect()->back()->with('error', 'Invalid permission. Please try again.');
    }

    public function redirectAuthenticatedWithRole()
    {
        if (auth()->check()) {
            return match (true) {
                auth()->user()->isUser() => redirect()->intended(RouteServiceProvider::USER_REDIRECT_URL),
                auth()->user()->isAgent() => redirect()->intended(RouteServiceProvider::AGENT_REDIRECT_URL),
                auth()->user()->isSystemAdmin() => redirect()->intended(RouteServiceProvider::SUPERADMIN_REDIRECT_URL),
                auth()->user()->isServiceDepartmentAdmin() => redirect()->intended(RouteServiceProvider::DEPARTMENT_ADMIN_REDIRECT_URL),
                auth()->user()->isApprover() && !$this->costingApprover2Only() => redirect()->intended(RouteServiceProvider::APPROVER_REDIRECT_URL),
                auth()->user()->isApprover() && $this->costingApprover2Only() => redirect()->intended(RouteServiceProvider::COSTING_APPROVER_REDIRECT_URL),
                default => $this->default()
            };
        }
    }
}

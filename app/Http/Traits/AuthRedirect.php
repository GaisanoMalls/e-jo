<?php

namespace App\Http\Traits;

use App\Models\Role;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;

trait AuthRedirect
{
    /**
     * Redirect authenticated staffs to a route based on their roles.
     *
     * @return void
     */
    public function redirectAuthenticatedWithRole()
    {
        if (Auth::check()) {
            switch (Auth::user()->role_id) {
                case Role::SYSTEM_ADMIN:
                    return redirect()->intended(RouteServiceProvider::SUPERADMIN_REDIRECT_URL);
                case Role::APPROVER:
                    return redirect()->intended(RouteServiceProvider::APPROVER_REDIRECT_URL);
                case Role::SERVICE_DEPARTMENT_ADMIN:
                    return redirect()->intended(RouteServiceProvider::DEPARTMENT_ADMIN_REDIRECT_URL);
                case Role::AGENT:
                    return redirect()->intended(RouteServiceProvider::AGENT_REDIRECT_URL);
                case Role::USER:
                    return redirect()->intended(RouteServiceProvider::USER_REDIRECT_URL);
            }
        }
    }
}
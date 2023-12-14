<?php

namespace App\Http\Traits;

trait ActiveWithRole
{
    use Logout;

    /**
     * Checks if the authenticated user is active and has one of the specified roles.
     * If the user is inactive or doesn't have any of the roles, logs them out.
     * Usage: Route middleware
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Closure  $next
     * @param  array|string  $role
     * @return mixed
     */
    public function activeAndHasRole($request, $next, $role)
    {
        $user = $request->user();

        return ($user && $user->isActive() && in_array($user->roles()->pluck('name'), $role))
            ? $next($request)
            : $this->doLogout($request)
                ->with('error', 'Invalid permission. Please try again.');
    }
}

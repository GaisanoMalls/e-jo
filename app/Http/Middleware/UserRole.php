<?php

namespace App\Http\Middleware;

use App\Http\Traits\ActiveWithRole;
use Closure;
use Illuminate\Http\Request;

class UserRole
{
    use ActiveWithRole;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$role)
    {
        return $this->activeAndHasRole($request, $next, $role);
    }
}
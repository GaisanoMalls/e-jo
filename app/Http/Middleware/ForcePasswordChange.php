<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForcePasswordChange
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $user = auth()->user();

            // If the user has never updated their record since creation, force password change
            if ($user->created_at && $user->updated_at && $user->created_at->equalTo($user->updated_at)) {
                if (!$request->routeIs('force_password')) {
                    return redirect()->route('force_password');
                }
            }
        }

        return $next($request);
    }
}



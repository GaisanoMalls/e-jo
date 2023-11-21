<?php

namespace App\Providers;

use App\Models\ApprovalStatus;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Role;
use App\Models\ServiceDepartment;
use App\Models\Status;
use App\Models\Suffix;
use App\Models\Ticket;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Gate::after(function ($user, $ability) {
            return $user->hasRole(Role::SYSTEM_ADMIN); // note this returns boolean
        });
    }
}
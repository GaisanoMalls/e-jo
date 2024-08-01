<?php

namespace App\Providers;

use App\Http\Traits\Utils;
use App\Models\Role;
use App\Models\Status;
use App\Models\Ticket;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    use Utils;
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
            return $user->hasRole(Role::SYSTEM_ADMIN); // note: this returns a boolean
        });

        // Search for overdue tickets and update their status to 'Overdue.
        Ticket::each(function ($ticket, $key) {
            if ($this->isSlaOverdue($ticket)) {
                $ticket->update(['status_id' => Status::OVERDUE]);
            }
        });
    }
}
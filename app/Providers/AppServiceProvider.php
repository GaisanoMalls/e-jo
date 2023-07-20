<?php

namespace App\Providers;

use App\Models\ApprovalStatus;
use App\Models\Branch;
use App\Models\Department;
use App\Models\ServiceDepartment;
use App\Models\Status;
use App\Models\Suffix;
use App\Models\Ticket;
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
        $global_departments = Department::all();
        $global_service_departments = ServiceDepartment::orderBy('name', 'asc')->get();
        $global_branches = Branch::all();
        $suffixes = Suffix::orderBy('name', 'asc')->get();

        view()->share([
            'global_departments' => $global_departments,
            'global_service_departments' => $global_service_departments,
            'global_branches' => $global_branches,
            'suffixes' => $suffixes,
        ]);

        // foreach (Ticket::all() as $ticket) {
        //     if ($ticket->replies()->count() !== 0) {
        //         $ticket->update([
        //             'status_id' => Status::ON_PROCESS
        //         ]);
        //     }
        // }
    }
}
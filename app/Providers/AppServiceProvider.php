<?php

namespace App\Providers;

use App\Enums\ApprovalStatusEnum;
use App\Http\Traits\Utils;
use App\Models\Status;
use App\Models\Ticket;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
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
        Paginator::useBootstrapFive();

        Gate::after(function ($user, $ability) {
            return $user->isSystemAdmin(); // This returns a boolean
        });

        // Search for overdue tickets and update their status to 'Overdue.
        if (Schema::hasTable('tickets')) {
            Ticket::whereNot('status_id', Status::CLOSED)
                ->where([
                    ['approval_status', ApprovalStatusEnum::APPROVED],
                    ['has_reached_due_date', false]
                ])
                ->each(function ($ticket, $key) {
                    // Iterate through the tickets whose status is Closed, approval status is Approved, and are not overdue,
                    // then update their status to Overdue when reaeched its due date
                    if ($this->isSlaOverdue($ticket)) {
                        $ticket->update([
                            'status_id' => Status::OVERDUE,
                            'has_reached_due_date' => true
                        ]);
                    }
                });
        } else {
            Log::info('Cannot find table name "tickets" in database ' . '"' . env('DB_DATABASE') . '"');
        }
    }
}
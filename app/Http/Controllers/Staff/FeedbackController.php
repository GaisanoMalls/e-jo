<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Status;

class FeedbackController extends Controller
{
    public function __invoke()
    {
        return view('layouts.staff.feedback', [
            'feedbacks' => Feedback::whereHas('ticket', function ($ticket) {
                $ticket->whereHas('status', fn($status) => $status->where('statuses.id', Status::CLOSED))
                    ->whereHas('user', function ($user) {
                        $user->with('profile')
                            ->whereHas('buDepartments', fn($department) => $department->where('departments.id', auth()->user()->buDepartments->pluck('id')->first()))
                            ->whereHas('branches', fn($branch) => $branch->whereIn('branches.id', auth()->user()->branches->pluck('id')->toArray()));
                    })
                    ->orWhereHas('serviceDepartment', function ($serviceDepartment) {
                        $serviceDepartment->whereIn('service_departments.id', auth()->user()->serviceDepartments->pluck('id')->toArray());
                    });
            })->get()
        ]);
    }
}

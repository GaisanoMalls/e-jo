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
            'feedbacks' => Feedback::withWhereHas('ticket', function ($ticketQuery) {
                $ticketQuery->withWhereHas('status', fn($status) => $status->where('statuses.id', Status::CLOSED))
                    ->withWhereHas('user', function ($userQuery) {
                        $userQuery->withWhereHas('buDepartments', fn($department) => $department->where('departments.id', auth()->user()->buDepartments->pluck('id')->first()))
                            ->withWhereHas('branches', fn($branch) => $branch->where('branches.id', auth()->user()->branches->pluck('id')->toArray()));
                    });
            })->get()
        ]);
    }
}

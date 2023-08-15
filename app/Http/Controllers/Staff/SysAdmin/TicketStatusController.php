<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SysAdmin\Manage\Statuses\StoreStatusRequest;
use App\Models\Status;

class TicketStatusController extends Controller
{
    public function index()
    {
        $statuses = Status::orderBy('created_at', 'desc')->get();
        return view('layouts.staff.system_admin.manage.ticket_statuses.status_index', compact('statuses'));
    }

    public function store(StoreStatusRequest $request, Status $status)
    {
        $status->create([
            'name' => $request->name,
            'color' => $request->color,
            'slug' => \Str::slug($request->name)
        ]);

        return back()->with('success', 'A new ticket status is created successfully.');
    }
}
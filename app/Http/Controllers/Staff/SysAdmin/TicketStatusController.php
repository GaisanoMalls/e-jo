<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TicketStatusController extends Controller
{
    public function index()
    {
        $statuses = Status::orderBy('created_at', 'desc')->get();
        return view('layouts.staff.system_admin.manage.ticket_statuses.status_index', compact('statuses'));
    }

    public function store(Request $request, Status $status)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'unique:statuses,name'],
            'color' => ['required', 'unique:statuses,color']
        ]);

        if ($validator->fails())
            return back()->withErrors($validator, 'storeTicketStatus')->withInput();

        $status->create($request->all());

        return back()->with('success', 'A new ticket status is created successfully.');
    }
}
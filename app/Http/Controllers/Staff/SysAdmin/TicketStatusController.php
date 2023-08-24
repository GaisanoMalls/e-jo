<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SysAdmin\Manage\Statuses\StoreStatusRequest;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TicketStatusController extends Controller
{
    public function index()
    {
        $statuses = Status::orderBy('created_at', 'desc')->get();
        return view('layouts.staff.system_admin.manage.ticket_statuses.status_index', compact('statuses'));
    }

    public function store(StoreStatusRequest $request)
    {
        Status::create([
            'name' => $request->name,
            'color' => $request->color,
            'slug' => \Str::slug($request->name)
        ]);

        return back()->with('success', 'A new ticket status is created successfully.');
    }

    public function update(Request $request, Status $status)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                Rule::unique('statuses')->ignore($status)
            ],
            'color' => [
                'required',
                Rule::unique('statuses')->ignore($status)
            ]
        ]);

        if ($validator->fails()) {
            $request->session()->put('statusId', $status->id); // set a session containing the pk of the status to show modal based on the selected record.
            return back()->withErrors($validator, 'editStatus')->withInput();
        }

        try {
            $status->update([
                'name' => $request->name,
                'color' => $request->color
            ]);

            $request->session()->forget('statusId'); // remove the statusId in the session when form is successful or no errors.
            return back()->with('success', 'Status successfully updated.');

        } catch (\Exception $e) {
            $request->session()->put('statusId', $status->id); // set a session containing the pk of branch to show modal based on the selected record.
            return back()->with('duplicate_name_error', "Status name {$request->name} already exists.");
        }
    }

    public function delete(Status $status)
    {
        try {
            $status->delete();
            return back()->with('success', 'Status successfully deleted.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete the status.');
        }
    }
}
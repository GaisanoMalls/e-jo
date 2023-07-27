<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Department;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnnouncementController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', Role::onlyServiceAndSystemAdmin()]);
    }

    public function index()
    {
        $departments = Department::orderBy('name', 'asc')->get();
        $annnouncements = new Announcement();

        $today_announcements = $annnouncements->whereDate('created_at', Carbon::today()->toDateString())->orderBy('created_at', 'desc')->get();
        $yesterday_announcements = $annnouncements->whereDate('created_at', Carbon::yesterday()->toDateString())->orderBy('created_at', 'desc')->get();
        $recent_announcements = $annnouncements->whereDate('created_at', '!=', Carbon::today()->toDateString())
            ->whereDate('created_at', '!=', Carbon::yesterday()->toDateString())
            ->orderBy('created_at', 'desc')
            ->get();

        return view(
            'layouts.staff.system_admin.announcement.announcement_main',
            compact([
                'departments',
                'today_announcements',
                'yesterday_announcements',
                'recent_announcements'
            ])
        );
    }

    public function store(Request $request, Announcement $announcement)
    {

        $validator = Validator::make($request->all(), [
            'title' => ['required'],
            'department' => ['required'],
            'description' => ['required'],
            'is_important' => ['boolean'],
            'is_draft' => ['boolean']
        ]);

        if ($validator->fails())
            return back()->withErrors($validator, 'storeAnnouncement')->withInput();

        $announcement->create([
            'title' => $request->input('title'),
            'department_id' => (int) $request->input('department'),
            'description' => $request->input('description'),
            'is_important' => (bool) $request->input('is_important')
        ]);

        return back()->with('success', 'Announcement successfully created.');
    }

    public function edit(Request $request, Announcement $announcement)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required'],
            'department' => ['required'],
            'description' => ['required'],
            'is_draft' => ['boolean']
        ]);

        if ($validator->fails())
            return back()->withErrors($validator, 'editAnnouncement')->withInput()->with('error', 'Failed to update. There was an error while updating the announcement.');

        $announcement->update([
            'title' => $request->input('title'),
            'department_id' => (int) $request->input('department'),
            'description' => $request->input('description'),
            'is_important' => (bool) $request->input('is_important')
        ]);

        return back()->with('success', 'Announcement successfully updated.');
    }

    public function delete(Announcement $announcement)
    {
        try {
            $announcement->delete();
            return back()->with('success', 'Announcement successfully deleted.');

        } catch (\Exception $e) {
            return back()->with('success', 'Announcemant cannot be deleted.');
        }
    }

    public function sendEmailCopy(Request $request)
    {
        //
    }

    public function saveAsDraft(Request $request, Announcement $announcement)
    {
        //
    }
}
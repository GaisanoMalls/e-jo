<?php

namespace App\Http\Controllers\Staff\ServiceDeptAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceDeptAdmin\StoreAnnouncementRequest;
use App\Http\Requests\ServiceDeptAdmin\UpdateAnnouncementRequest;
use App\Models\Announcement;
use App\Models\Department;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', Role::onlyServiceAndSystemAdmin()]);
    }

    public function index()
    {
        $departments = Department::orderBy('name', 'asc')->get();
        $annnouncements = Announcement::query();

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

    public function store(StoreAnnouncementRequest $request)
    {
        Announcement::create([
            'title' => $request->title,
            'department_id' => $request->department,
            'description' => $request->description,
            'is_important' => (bool) $request->is_important
        ]);

        return back()->with('success', 'Announcement successfully created.');
    }

    public function edit(UpdateAnnouncementRequest $request, Announcement $announcement)
    {
        $announcement->update([
            'title' => $request->title,
            'department_id' => $request->department,
            'description' => $request->description,
            'is_important' => (bool) $request->is_important
        ]);

        return back()->with('success', 'Announcement successfully updated.');
    }

    public function delete(Announcement $announcement)
    {
        try {
            $announcement->delete();
            return back()->with('success', 'Announcement successfully deleted.');

        } catch (\Exception $e) {
            dd($e->getMessage());
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
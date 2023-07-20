<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Department;
use App\Models\HelpTopic;
use App\Models\ServiceDepartment;
use App\Models\User;

class DependentsController extends Controller
{
    //* For System Admin Use
    public function getBranches(Department $department)
    {
        return response()->json($department->branches);
    }

    public function getServiceDepartments(Department $department)
    {
        return response()->json($department->serviceDepartments);
    }
    //! END For System Admin Use

    //* For User Use (Ticket Creation)
    public function ticketCreationGetDepartmentsByUserBranch(User $user)
    {
        $departments = Department::whereHas('branches', function ($query) use ($user) {
            $query->where('branches.id', $user->branch_id);
        })->get();

        return response()->json($departments);
    }

    public function ticketCreationGetServiceDepartmentByUserBranch(User $user)
    {
        $serviceDepartments = ServiceDepartment::whereHas('branches', function ($query) use ($user) {
            $query->where('branches.id', $user->branch_id);
        })->get();

        return response()->json($serviceDepartments);
    }

    public function getServiceDepartmentHelpTopics(ServiceDepartment $serviceDepartment)
    {
        return response()->json($serviceDepartment->helpTopics);
    }

    public function getServiceDepartmentByHelpTopic(HelpTopic $helpTopic)
    {
        return response()->json($helpTopic->serviceDepartment);
    }

    public function ticketCreationGetBranches(User $user)
    {
        $branches = Branch::where('id', '!=', $user->branch_id)->get();

        return response()->json($branches);
    }
    //! END For User Use (Ticket Creation)

    //* For Ticket Action Use
    public function ticketActionGetDepartmentServiceDepartments(Department $department)
    {
        return response()->json($department->teams);
    }
    //! END For Ticket Action Use
}
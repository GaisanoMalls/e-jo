<?php

namespace App\Http\Traits;

use App\Models\Branch;
use App\Models\Department;
use App\Models\ServiceDepartment;
use App\Models\Suffix;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;

trait UserDetails
{
    public function getSuffixes()
    {
        $suffixes = Suffix::all();
        return $suffixes;
    }

    public function getBranches()
    {
        $branches = Branch::orderBy('name', 'asc')->get();
        return $branches;
    }

    public function getDepartments()
    {
        $departments = Department::orderBy('name', 'asc')->get();
        return $departments;
    }

    public function getServiceDepartments()
    {
        $serviceDepartments = ServiceDepartment::orderBy('name', 'asc')->get();
        return $serviceDepartments;
    }
}
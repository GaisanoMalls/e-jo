<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SysAdmin\Manage\SLA\StoreSLARequest;
use App\Models\ServiceLevelAgreement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SLAController extends Controller
{

    public function __invoke()
    {
        return view('layouts.staff.system_admin.manage.sla.sla_index');
    }
}
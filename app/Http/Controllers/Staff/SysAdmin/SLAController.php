<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SysAdmin\Manage\SLA\StoreSLARequest;
use App\Models\ServiceLevelAgreement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SLAController extends Controller
{
    public function index()
    {
        $slas = ServiceLevelAgreement::orderBy('created_at', 'desc')->get();
        return view(
            'layouts.staff.system_admin.manage.sla.sla_index',
            compact([
                'slas'
            ])
        );
    }

    public function store(StoreSLARequest $request, ServiceLevelAgreement $sla)
    {
        $sla->create([
            'countdown_approach' => $request->countdown_approach,
            'time_unit' => $request->time_unit
        ]);

        return back()->with('success', 'A new SLA is created.');
    }

    public function edit(Request $request, ServiceLevelAgreement $sla)
    {
        $validator = Validator::make($request->all(), [
            'countdown_approach' => ['required'],
            'time_unit' => ['required']
        ]);

        // * TODO
    }

    public function delete(ServiceLevelAgreement $sla)
    {
        try {
            $sla->delete();
            return back()->with('success', 'SLA successfully deleted.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete the SLA');
        }
    }
}
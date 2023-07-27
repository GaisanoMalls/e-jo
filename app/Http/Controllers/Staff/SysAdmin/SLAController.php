<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Models\ServiceLevelAgreement;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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

    public function store(Request $request, ServiceLevelAgreement $sla)
    {
        $validator = Validator::make($request->all(), [
            'countdown_approach' => ['required', 'unique:service_level_agreements,countdown_approach'],
            'time_unit' => ['required', 'unique:service_level_agreements,time_unit']
        ]);

        if ($validator->fails())
            return back()->withErrors($validator, 'storeSLA')->withInput();

        $sla->create([
            'countdown_approach' => $request->input('countdown_approach'),
            'time_unit' => $request->input('time_unit')
        ]);

        return back()->with('success', 'A new SLA is created.');
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
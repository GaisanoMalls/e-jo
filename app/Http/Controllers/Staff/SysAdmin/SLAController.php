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

    public function store(StoreSLARequest $request)
    {
        ServiceLevelAgreement::create([
            'countdown_approach' => $request->countdown_approach,
            'time_unit' => $request->time_unit
        ]);

        return back()->with('success', 'A new SLA is created.');
    }

    public function update(Request $request, ServiceLevelAgreement $sla)
    {
        $validator = Validator::make($request->all(), [
            'countdown_approach' => [
                'required',
                Rule::unique('service_level_agreements')->ignore($sla)
            ],
            'time_unit' => [
                'required',
                Rule::unique('service_level_agreements')->ignore($sla)
            ]
        ]);

        if ($validator->fails()) {
            $request->session()->put('slaId', $sla->id); // set a session containing the pk of the SLA to show modal based on the selected record.
            return back()->withErrors($validator, 'editSLA')->withInput();
        }

        try {
            $sla->update([
                'countdown_approach' => $request->countdown_approach,
                'time_unit' => $request->time_unit
            ]);

            $request->session()->forget('slaId'); // remove the slaId in the session when form is successful or no errors.
            return back()->with('success', 'SLA successfully updated.');

        } catch (\Exception $e) {
            $request->session()->put('slaId', $sla->id); // set a session containing the pk of branch to show modal based on the selected record.
            return back()->with('duplicate_name_error', "SLA name {$request->name} already exists.");
        }
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
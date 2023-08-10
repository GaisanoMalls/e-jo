<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Http\Traits\SlugGenerator;
use App\Http\Traits\UserDetails;
use App\Models\Branch;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AccountAgentController extends Controller
{
    use SlugGenerator, UserDetails;

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'branch' => ['required'],
            'bu_department' => ['required'],
            'team' => ['required'],
            'service_department' => ['required'],
            'first_name' => ['required', 'min:2', 'max:100'],
            'middle_name' => ['nullable', 'min:2', 'max:100'],
            'last_name' => ['required', 'min:2', 'max:100'],
            'suffix' => ['nullable', 'min:1', 'max:4'],
            'email' => ['required', 'max:80']
        ]);

        if ($validator->fails())
            return back()->withErrors($validator, 'storeAgent')->withInput();

        try {
            DB::transaction(function () use ($request) {
                $user = User::create([
                    'branch_id' => $request['branch'],
                    'department_id' => $request['bu_department'],
                    'team_id' => $request->input('team'),
                    'service_department_id' => $request->input('service_department'),
                    'role_id' => Role::AGENT,
                    'email' => $request['email'],
                    'password' => \Hash::make('agent'),
                ]);

                $fullname = $request['first_name'] . $request['middl_name'] ?? "" . $request['last_name'];

                Profile::create([
                    'user_id' => $user->id,
                    'first_name' => $request['first_name'],
                    'middle_name' => $request['middle_name'],
                    'last_name' => $request['last_name'],
                    'suffix' => $request['suffix'],
                    'slug' => $this->slugify($fullname)
                ]);
            });

            return back()->with('success', 'You have successfully created a new agent');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to save a new agent. Please try again.');
        }
    }

    public function viewDetails(User $agent)
    {
        return view(
            'layouts.staff.system_admin.manage.accounts.roles.details.agent_details',
            compact('agent')
        );
    }


    public function editDetails(User $agent)
    {
        $suffixes = $this->suffixes();
        $branches = $this->branches();
        $serviceDepartments = $this->serviceDepartments();

        return view(
            'layouts.staff.system_admin.manage.accounts.edit.edit_agent',
            compact([
                'agent',
                'suffixes',
                'branches',
                'serviceDepartments'
            ])
        );
    }

    public function update(Request $request, User $agent)
    {
        $validator = Validator::make($request->all(), [
            'branch' => ['required'],
            'bu_department' => ['required'],
            'team' => ['required'],
            'service_department' => ['required'],
            'first_name' => ['required', 'min:2', 'max:100'],
            'middle_name' => ['nullable', 'min:2', 'max:100'],
            'last_name' => ['required', 'min:2', 'max:100'],
            'suffix' => ['nullable', 'min:1', 'max:4'],
            'email' => ['required', 'max:80']
        ]);

        if ($validator->fails())
            return back()->withErrors($validator, 'editAgent')->withInput();

        try {
            DB::transaction(function () use ($agent, $request) {
                $agent->update([
                    'branch_id' => $request->input('branch'),
                    'department_id' => $request->input('bu_department'),
                    'service_department_id' => $request->input('service_department'),
                    'email' => $request->input('email')
                ]);

                $agent->profile()->update([
                    'first_name' => $request->input('first_name'),
                    'middle_name' => $request->input('middle_name'),
                    'last_name' => $request->input('last_name'),
                    'suffix' => $request->input('suffix'),
                    'slug' => $this->slugify(implode(" ", [
                        $request->first_name,
                        $request->middle_name,
                        $request->last_name,
                        $request->suffix
                    ]))
                ]);
            });

            return back()->with('success', "You have successfully updated the account for {$agent->profile->getFullName()}.");

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update the agent. Please try again.');
        }
    }

    public function delete(User $agent)
    {
        try {
            $agent->delete();
            $agent->profile()->delete();

            return back()->with('success', 'Agent successfully deleted.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete the agent. Please try again.');
        }
    }

    public function branchDepartments(Branch $branch)
    {
        return response()->json($branch->departments);
    }

    public function branchTeams(Branch $branch)
    {
        return response()->json($branch->teams);
    }
}
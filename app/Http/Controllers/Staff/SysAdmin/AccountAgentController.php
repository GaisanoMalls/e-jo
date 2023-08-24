<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SysAdmin\Manage\Account\StoreAgenRequest;
use App\Http\Requests\SysAdmin\Manage\Account\UpdateAgenRequest;
use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Models\Branch;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AccountAgentController extends Controller
{
    use Utils, BasicModelQueries;

    public function store(StoreAgenRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $user = User::create([
                    'branch_id' => $request->branch,
                    'department_id' => $request->bu_department,
                    'team_id' => $request->team,
                    'service_department_id' => $request->service_department,
                    'role_id' => Role::AGENT,
                    'email' => $request->email,
                    'password' => \Hash::make('agent'),
                ]);

                $fullname = $request->first_name . $request->middl_name ?? "" . $request->last_name;

                Profile::create([
                    'user_id' => $user->id,
                    'first_name' => $request->first_name,
                    'middle_name' => $request->middle_name,
                    'last_name' => $request->last_name,
                    'suffix' => $request->suffix,
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
        $suffixes = $this->querySuffixes();
        $branches = $this->queryBranches();
        $serviceDepartments = $this->queryServiceDepartments();

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

    public function update(UpdateAgenRequest $request, User $agent)
    {
        try {
            DB::transaction(function () use ($agent, $request) {
                $agent->update([
                    'branch_id' => $request->branch,
                    'department_id' => $request->bu_department,
                    'service_department_id' => $request->service_department,
                    'email' => $request->email
                ]);

                $agent->profile()->update([
                    'first_name' => $request->first_name,
                    'middle_name' => $request->middle_name,
                    'last_name' => $request->last_name,
                    'suffix' => $request->suffix,
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
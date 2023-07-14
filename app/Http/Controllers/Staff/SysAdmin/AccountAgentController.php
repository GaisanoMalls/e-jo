<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Http\Traits\SlugGenerator;
use App\Models\Branch;
use App\Models\Profile;
use App\Models\Role;
use App\Models\ServiceDepartment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccountAgentController extends Controller
{
    use SlugGenerator;

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'branch' => ['required'],
            'bu_department' => ['required'],
            'first_name' => ['required', 'min:2', 'max:100'],
            'middle_name' => ['nullable', 'min:2', 'max:100'],
            'last_name' => ['required', 'min:2', 'max:100'],
            'suffix' => ['nullable', 'min:1', 'max:4'],
            'email' => ['required', 'max:80']
        ]);

        if ($validator->fails()) return back()->withErrors($validator, 'storeAgent')->withInput();

        $user = User::create([
            'branch_id' => $request['branch'],
            'department_id' => $request['bu_department'],
            'role_id' => Role::AGENT,
            'email' => $request['email'],
            'password' => 'agent'
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

        return back()->with('success', 'You have successfully created a new agent');
    }

    public function delete(User $agent)
    {
        try {
            $agent->delete();
            return back()->with('success', 'Agent successfully deleted.');
        } catch (\Exception $e) {
            return back()->with('error', `Failed to delete the agent.`);
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

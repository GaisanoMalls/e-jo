<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Http\Traits\SlugGenerator;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccountUserController extends Controller
{
    use SlugGenerator;

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'department' => ['required'],
                'branch' => ['required'],
                'first_name' => ['required', 'min:2', 'max:100'],
                'middle_name' => ['nullable', 'min:2', 'max:100'],
                'last_name' => ['required', 'min:2', 'max:100'],
                'suffix' => ['nullable', 'min:1', 'max:4'],
                'email' => ['required', 'max:80']
            ],
            [
                'department.required' => 'Please assign a department.',
                'branch.required' => 'Please assign a branch.',
                'first_name.required' => 'First name is required.',
                'last_name.required' => 'Last name is required.',
                'email.required' => 'Email is required.'
            ]
        );

        if ($validator->fails()) return back()->withErrors($validator, 'storeUser')->withInput();

        $user = User::create([
            'department_id' => (int) $request->input('department'),
            'branch_id' => (int) $request->input('branch'),
            'role_id' => (int) Role::USER,
            'email' => $request->input('email'),
            'password' => \Hash::make('user')
        ]);

        Profile::create([
            'user_id' => $user->id,
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

        return back()->with('success', 'You have successfully created a new user/requester');
    }

    public function delete(User $user)
    {
        try {
            $user->delete();
            return back()->with('success', `User successfully deleted.`);
        } catch (\Exception $e) {
            return back()->with('error', `Failed to delete the user.`);
        }
    }
}

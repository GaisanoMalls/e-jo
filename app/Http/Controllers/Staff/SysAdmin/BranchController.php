<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Http\Traits\SlugGenerator;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BranchController extends Controller
{
    use SlugGenerator;

    public function index()
    {
        $branches = Branch::orderBy('created_at', 'desc')->get();
        return view('layouts.staff.system_admin.manage.branches.branch_index', compact('branches'));
    }

    public function store(Request $request, Branch $branch)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'unique:branches,name']
        ]);

        if ($validator->fails())
            return back()->withErrors($validator, 'storeBranch')->withInput();

        $branch->create([
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name'))
        ]);

        return back()->with('success', 'Branch successfully created.');
    }

    public function update(Request $request, Branch $branch)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'unique:branches,name']
        ]);

        if ($validator->fails()) {
            $request->session()->put('branchId', $branch->id); // set a session containing the pk of branch to show modal based on the selected record.
            return back()->withErrors($validator, 'editBranch')->withInput();
        }

        $branch->update([
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name'))
        ]);

        $request->session()->forget('branchId'); // remove the branchId in the session when form is successful or no errors.
        return back()->with('success', 'Branch successfully udpated.');
    }

    public function delete(Branch $branch)
    {
        try {
            $branch->delete();
            return back()->with('success', 'Branch successfully deleted.');
        } catch (\Exception $e) {
            return back()->with('error', 'Branch name cannot be deleted.');
        }
    }
}
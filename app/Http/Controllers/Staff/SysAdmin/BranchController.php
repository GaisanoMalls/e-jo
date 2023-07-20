<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SysAdmin\BranchRequest;
use App\Http\Requests\SysAdmin\EditBranchRequest;
use App\Http\Traits\SlugGenerator;
use App\Models\Branch;
use Illuminate\Support\Str;

class BranchController extends Controller
{
    use SlugGenerator;

    public function index()
    {
        $branches = Branch::orderBy('created_at', 'desc')->get();
        return view('layouts.staff.system_admin.manage.branches.branch_index', compact('branches'));
    }

    public function store(BranchRequest $request, Branch $branch)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        $branch->create($data);

        return back()->with('success', 'Branch successfully created.');
    }

    public function edit(EditBranchRequest $request, Branch $branch)
    {
        $branch->update(['name' => $request['name']]);
        return back();
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
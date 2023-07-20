<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::orderBy('created_at', 'desc')->get();
        return view(
            'layouts.staff.system_admin.manage.tags.tag_index',
            compact([
                'tags'
            ])
        );
    }

    public function store(Request $request, Tag $tag)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'unique:tags,name'],
        ]);

        if ($validator->fails())
            return back()->withErrors($validator, 'storeTag')->withInput();

        $tag->create([
            'name' => $request->input('name'),
            'slug' => \Str::slug($request->input('name'))
        ]);

        return back()->with('success', 'A new tag is successfully created.');
    }

    public function delete(Tag $tag)
    {
        try {
            $tag->delete();
            return back()->with('success', 'Tag is successfully deleted.');
        } catch (\Exception $e) {
            return back()->with('success', 'Tag is successfully deleted.');
        }
    }
}
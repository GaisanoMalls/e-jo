<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SysAdmin\Manage\Tag\StoreTagRequest;
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

    public function store(StoreTagRequest $request, Tag $tag)
    {
        $tag->create([
            'name' => $request->name,
            'slug' => \Str::slug($request->name)
        ]);

        return back()->with('success', 'Tag successfully created.');
    }

    public function update(Request $request, Tag $tag)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
        ]);

        if ($validator->fails()) {
            $request->session()->put('tagId', $tag->id); // set a session containing the pk of tag to show modal based on the selected record.
            return back()->withErrors($validator, 'editTag')->withInput();
        }

        $tag->update([
            'name' => $request->name,
            'slug' => \Str::slug($request->name)
        ]);

        $request->session()->forget('tagId'); // remove the tagId in the session when form is successful or no errors.
        return back()->with('success', 'Tag successfully updated.');
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
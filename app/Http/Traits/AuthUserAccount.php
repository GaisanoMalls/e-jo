<?php

namespace App\Http\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

trait AuthUserAccount
{
    public function authUserUpdateProfileInfo($request)
    {
        $user = Auth::user();
        $profile = $user->profile;

        $user->update(['email' => $request->input('email')]);
        $profile->update([
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'suffix' => $request->input('suffix'),
            'mobile_number' => $request->input('mobile_number'),
        ]);

        return ($user->wasChanged('email')
            || $profile->wasChanged([
                'first_name',
                'middle_name',
                'last_name',
                'suffix',
                'mobile_number',
                'picture'
            ]))
            ? back()->with('success', 'Profile successfully updated.')
            : back()->with('info', 'No changes have been made in your profile.');
    }

    public function authUserStoreProfilePicture($request, string $fileField, string $path)
    {
        $profile = Auth::user()->profile;

        if ($request->hasFile($fileField)) {
            $picture = $request->file($fileField);
            $fileName = time() . "_" . $picture->getClientOriginalName();
            $picturePath = $picture->storeAs($path, $fileName, 'public');

            $profile->picture ? Storage::delete('public/' . $profile->picture) : null;
            $profile->picture = $picturePath;
        }
    }

    public function authUserUpdatePassword(string $current, string $new)
    {
        if (!Hash::check($current, Auth::user()->password)) {
            return back()->with('error', 'Current password not matched. Please try again.');
        }

        User::where('id', auth()->user()->id)->update([
            'password' => Hash::make($new)
        ]);

        return back()->with('success', 'Password changed successfully!');
    }
}
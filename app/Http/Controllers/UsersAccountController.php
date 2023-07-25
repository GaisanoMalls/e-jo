<?php

namespace App\Http\Controllers;

use App\Http\Traits\AuthUserAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsersAccountController extends Controller
{
    use AuthUserAccount;

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'min:2'],
            'middle_name' => ['nullable', 'min:1'],
            'last_name' => ['required', 'min:2'],
            'suffix' => ['nullable', 'min:2'],
            'email' => ['required', 'email'],
            'mobile_number' => ['nullable', 'min:11', 'max:11'],
            'picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        if ($validator->fails())
            return back()->with('error', 'Failed to update your profile.')->withErrors($validator, 'updateProfile')->withInput();

        $this->authUserStoreProfilePicture($request, 'picture', 'profile_picture');
        return $this->authUserUpdateProfileInfo($request);
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => ['required'],
            'new_password' => ['required', 'different:current_password'],
            'confirm_password' => ['required', 'same:new_password'],
        ]);

        if ($validator->fails())
            return back()->with('error', 'Failed to update your password.')->withErrors($validator, 'updatePassword')->withInput();

        return $this->authUserUpdatePassword($request->current_password, $request->new_password);
    }
}
<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Http\Traits\SysAdmin\UserAccountConfig;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UpdatePasswordController extends Controller
{
    use UserAccountConfig;

    public function updatePassword(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'new_password' => ['required', 'min:5'],
            'confirm_password' => ['required', 'same:new_password'],
        ]);

        if ($validator->fails()) {
            return back()->with('error', 'Failed to update the password.')
                ->withErrors($validator, 'updatePassword');
        }

        $newPassword = $request->input('new_password');
        $confirmPassword = $request->input('confirm_password');

        return $this->updateUserPassword($user, $newPassword, $confirmPassword);
    }
}
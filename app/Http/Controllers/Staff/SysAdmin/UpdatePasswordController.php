<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SysAdmin\Manage\Account\UpdatePasswordRequest;
use App\Http\Traits\SysAdmin\UserAccountConfig;
use App\Models\User;

class UpdatePasswordController extends Controller
{
    use UserAccountConfig;

    public function updatePassword(UpdatePasswordRequest $request, User $user)
    {
        $newPassword = $request->new_password;
        $confirmPassword = $request->confirm_password;

        return $this->updateUserPassword($user, $newPassword, $confirmPassword);
    }
}
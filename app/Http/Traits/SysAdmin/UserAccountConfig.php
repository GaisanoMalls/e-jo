<?php

namespace App\Http\Traits\SysAdmin;

use Illuminate\Support\Facades\Hash;

trait UserAccountConfig
{
    public function updateUserPassword($user, string $newPassword, string $confirmPassword)
    {
        if ($newPassword !== $confirmPassword) {
            return back()->with('error', 'Current password not matched. Please try again.');
        }

        $user->update([
            'password' => Hash::make($newPassword)
        ]);

        return back()->with('success', 'Password successfully updated.');
    }
}
<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Http\Traits\AccountSettings;
use App\Http\Traits\AuthUserAccount;
use App\Models\Status;
use App\Models\Ticket;

class AccountController extends Controller
{
    use AuthUserAccount;

    public function profile()
    {
        $tickets = Ticket::where('user_id', auth()->user()->id)
                         ->orderBy('created_at', 'desc')
                         ->get();

        $allTickets = $tickets;
        $openTickets = $tickets->where('status_id', Status::OPEN);
        return view('layouts.user.account.account_settings_base', compact('allTickets', 'openTickets'));
    }

    public function password()
    {
        return view('layouts.user.account.password');
    }

    public function updateProfile(UpdateUserProfileRequest $request)
    {
        $this->authUserStoreProfilePicture($request, 'picture', 'profile_picture');
        return $this->authUserUpdateProfileInfo($request);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        return $this->authUserUpdatePassword($request->current_password, $request->new_password);
    }
}

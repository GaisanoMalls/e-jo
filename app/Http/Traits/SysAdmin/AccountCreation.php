<?php

namespace App\Http\Traits\SysAdmin;
use App\Http\Traits\SlugGenerator;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

trait AccountCreation
{
    use SlugGenerator;

    public function storeInfo($request, User $user)
    {
        $user->create([
            'department_id' => $request['department'] ?? null,
            'team_id' => $request['team'] ?? null,
            'role_id' => $request['role'],
            'email' => $request['email'],
            'password' => \Hash::make($request['password']),
            'is_active' => true,
            'is_highest_approver' => (bool) $request['is_highest_approver']
        ]);

        $user->profile()->create([
            'user_id' => $user->id,
            'first_name' => $request['first_name'],
            'middle_name' => $request['middle_name'] ?? null,
            'last_name' => $request['last_name'],
            'suffix' => $request['suffix'] ?? null,
            'mobile_number' => $request['mobile_number'] ?? null,
            'department_phone_number' => $request['department_phone_number'] ?? null,
        ]);

        $user->profile()->update([
            'slug' => $this->slugify($user->profile->getFullName())
        ]);
    }

    public function storeProfilePicture($request, $profileObj, string $fileField, string $path)
    {
        if ($request->hasFile($fileField)) {
            $picture = $request->file($fileField);
            $fileName = time() . "_" . $picture->getClientOriginalName();
            $picturePath = $picture->storeAs($path, $fileName, 'public');

            $profileObj->picture ? Storage::delete('public/' . $profileObj->picture) : null;
            $profileObj->picture = $picturePath;
        }
    }
}
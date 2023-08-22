<?php

namespace App\Http\Traits;

use App\Models\Role;

trait FileUploadDir
{
    public function fileDirByUserType()
    {
        $staffRolePath = '';
        switch (auth()->user()->role_id) {
            case Role::SYSTEM_ADMIN:
                $staffRolePath = 'system_admin';
                break;
            case Role::SERVICE_DEPARTMENT_ADMIN:
                $staffRolePath = 'service_department_admin';
                break;
            case Role::APPROVER:
                $staffRolePath = 'approver';
                break;
            case Role::AGENT:
                $staffRolePath = 'agent';
                break;
            case Role::USER:
                $staffRolePath = 'requester';
                break;
            default:
                $staffRolePath = 'guest';
        }

        return $staffRolePath;
    }

    public function generateNewProfilePictureName($picture)
    {
        return time() . "_" . \Str::slug(auth()->user()->profile->getFullName()) . "." . $picture->getClientOriginalExtension();
    }
}
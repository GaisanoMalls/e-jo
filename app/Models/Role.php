<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    // TODO Create a controller to run RolesSeeder programmatically. To be managed by System Admin.
    // * "The values (integers) of these constant variables are equal to the primary key of the 'roles' table. Therefore, the mentioned requirement is necessary."
    const SYSTEM_ADMIN = 'System Admin';
    const SERVICE_DEPARTMENT_ADMIN = 'Service Department Admin';
    const APPROVER = 'Approver';
    const AGENT = 'Agent';
    const USER = 'User';

    public static function staffsOnly(): string
    {
        return 'role:' . self::SYSTEM_ADMIN . "|" . self::SERVICE_DEPARTMENT_ADMIN . "|" . self::AGENT;
    }

    public static function approversOnly(): string
    {
        return 'role:' . self::APPROVER;
    }

    public static function requestersOnly(): string
    {
        return 'role:' . self::USER;
    }
}

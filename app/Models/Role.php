<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name'];
    public $timestamps = false;

    // TODO Create a controller to run RolesSeeder prgrammatically. To be managed by System Admin.
    // * "The values (integers) of these constant variables are equal to the primary key of the 'roles' table. Therefore, the mentioned requirement is necessary."
    const SYSTEM_ADMIN = 1;
    const SERVICE_DEPARTMENT_ADMIN = 2;
    const APPROVER = 3;
    const AGENT = 4;
    const USER = 5;

    private static string $lbl_user_role = 'user_role:';

    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * For system admin route middleware.
     * Route::middleware(['auth', 'role:1'])
     *
     * @return string
     */
    public static function systemAdmin(): string
    {
        return self::$lbl_user_role . self::SYSTEM_ADMIN;
    }

    /**
     * For department admin route middleware.
     * Route::middleware(['auth', 'role:2'])
     *
     * @return string
     */
    public static function serviceDepartmentAdmin(): string
    {
        return self::$lbl_user_role . self::SERVICE_DEPARTMENT_ADMIN;
    }

    /**
     * For approver route middleware
     * Route::middleware(['auth', 'role:3'])
     *
     * @return string
     */
    public static function approver(): string
    {
        return self::$lbl_user_role . self::APPROVER;
    }

    /**
     * For agent route middleware.
     * Route::middleware(['auth', 'role:4'])
     *
     * @return string
     */
    public static function agent(): string
    {
        return self::$lbl_user_role . self::AGENT;
    }

    /**
     * For user route middleware
     * Route::middleware(['auth', 'role:USER'])
     *
     * @return string
     */
    public static function user(): string
    {
        return self::$lbl_user_role . self::USER;
    }

    /**
     * Returns a string containing the roles of agents and department administrators,
     * separated by commas and prefixed with "role:" for use in Laravel middleware authentication.
     * A string containing the roles of agents and department administrators, prefixed with "role:"
     *
     * @var array<string>
     * @return string
     */
    public static function onlyAgentAndDeptAdmin(): string
    {
        $userRoles = [
            self::SERVICE_DEPARTMENT_ADMIN,
            self::AGENT
        ];

        return self::$lbl_user_role . implode(",", $userRoles);
    }

    public static function onlyServiceAndSystemAdmin(): string
    {
        $userRoles = [
            self::SERVICE_DEPARTMENT_ADMIN,
            self::SYSTEM_ADMIN
        ];

        return self::$lbl_user_role . implode(",", $userRoles);
    }

    public static function onlyStaffs(): string
    {
        $userRoles = [
            self::SERVICE_DEPARTMENT_ADMIN,
            self::AGENT,
            self::SYSTEM_ADMIN
        ];

        return self::$lbl_user_role . implode(",", $userRoles);
    }

    /**
     * Allow authenticated staffs (Agent, Admin, Superuser) and users to access the route.
     * Append the user's role to the array named $allUsersRole, if the user has a role.
     *
     * @var array<string>
     * @return string
     */
    public static function allStaffsAndUsers(): string
    {
        $allUserRoles = [
            self::SERVICE_DEPARTMENT_ADMIN,
            self::AGENT,
            self::SYSTEM_ADMIN,
            self::USER
        ];

        return self::$lbl_user_role . implode(",", $allUserRoles);
    }
}
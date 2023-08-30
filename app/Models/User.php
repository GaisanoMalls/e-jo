<?php

namespace App\Models;

use App\Http\Traits\Utils;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Utils;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'branch_id',
        'department_id',
        'service_department_id',
        'team_id',
        'role_id',
        'email',
        'password',
        'is_active',
        'is_highest_approver'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function serviceDepartment()
    {
        return $this->belongsTo(ServiceDepartment::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function levels()
    {
        return $this->belongsToMany(Level::class, 'level_approver')
            ->withTimestamps();
    }

    public function serviceDepartments()
    {
        return $this->belongsToMany(ServiceDepartment::class, 'user_service_department');
    }

    public function getServiceDepartments()
    {
        $serviceDepartmentNames = [];

        foreach ($this->serviceDepartments as $serviceDepartment) {
            array_push($serviceDepartmentNames, $serviceDepartment->name);
        }

        if (!empty($serviceDepartmentNames)) {
            return implode(', ', $serviceDepartmentNames);
        }

        return '----';
    }

    /**
     * Check if the user is Superuser.
     *
     * @return boolean Returns true if the user has Superuser role, otherwise returns false.
     */
    public function isSystemAdmin(): bool
    {
        return $this->role === Role::SYSTEM_ADMIN;
    }

    /**
     * Check if the user is Department Admin.
     *
     * @return boolean Returns true if the user has Department Admin role, otherwise returns false.
     */
    public function isServiceDepartmentAdmin(): bool
    {
        return $this->role === Role::SERVICE_DEPARTMENT_ADMIN;
    }

    /**
     * Check if the user is Agent.
     *
     * @return boolean Returns true if the user has Agent role, otherwise returns false.
     */
    public function isAgent(): bool
    {
        return $this->role === Role::AGENT;
    }

    /**
     * Check if the user is Approver
     *
     * @return boolean Returns true if the user has Approver role, otherwise returns false.
     */
    public function isApprover(): bool
    {
        return $this->role === Role::APPROVER;
    }

    /**
     * Check if the user is a User (Responsible for ticket creation)
     *
     * @return boolean Returns true if the user has User role, otherwise returns false.
     */
    public function isUser(): bool
    {
        return $this->role === Role::USER;
    }

    /**
     * Check if the user is active or not.
     *
     * @return bool Returns true if the user is active, otherwise returns false.
     */
    public function isActive(): bool
    {
        return $this->is_active === 1;
    }

    // Filter user types by roles
    public static function systemAdmins()
    {
        return self::with(['profile', 'branch'])
            ->whereHas('role', fn($systemAdmin) => $systemAdmin->where('role_id', Role::SYSTEM_ADMIN))
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function serviceDepartmentAdmins()
    {
        return self::with('profile')
            ->whereHas('role', fn($serviceDepartmentAdmin) => $serviceDepartmentAdmin->where('role_id', Role::SERVICE_DEPARTMENT_ADMIN))
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function approvers()
    {
        return self::with(['profile', 'branch'])
            ->whereHas('role', fn($approver) => $approver->where('role_id', Role::APPROVER))
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function agents()
    {
        return self::with(['profile', 'branch'])
            ->whereHas('role', fn($agent) => $agent->where('role_id', Role::AGENT))
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function requesters()
    {
        return self::with('profile')
            ->whereHas('role', fn($requester) => $requester->where('role_id', Role::USER))
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function dateCreated()
    {
        return $this->createdAt($this->created_at);
    }

    public function dateUpdated()
    {
        return $this->updatedAt($this->created_at, $this->updated_at);
    }
}
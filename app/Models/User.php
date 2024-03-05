<?php

namespace App\Models;

use App\Http\Traits\Utils;
use App\Models\ActivityLog;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Feedback;
use App\Models\Level;
use App\Models\Profile;
use App\Models\Role;
use App\Models\ServiceDepartment;
use App\Models\Team;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Utils, HasRoles;

    /**
     * The attributes that are mass assignable.
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'is_active',
        'is_highest_approver',
    ];

    /**
     * The attributes that should be hidden for serialization.
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function feedbacks(): HasMany
    {
        return $this->hasMany(Feedback::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    public function likes()
    {
        return $this->hasMany(ReplyLike::class);
    }

    public function serviceDepartments(): BelongsToMany
    {
        return $this->belongsToMany(ServiceDepartment::class, 'user_service_department', 'user_id', 'service_department_id');
    }

    // For Approvers Only
    public function branches(): BelongsToMany
    {
        return $this->belongsToMany(Branch::class, 'user_branch', 'user_id', 'branch_id');
    }

    public function buDepartments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'user_department', 'user_id', 'department_id')
            ->withPivot('user_id', 'department_id');
    }

    public function levels()
    {
        return $this->belongsToMany(Level::class, 'approver_level')->withPivot(['user_id', 'level_id']);
    }

    // For Agents Only
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'user_team')->withPivot(['user_id', 'team_id']);
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
        return $this->is_active;
    }

    // Filter user types by roles
    public static function systemAdmins()
    {
        return self::with(['profile', 'branch'])->role(Role::SYSTEM_ADMIN)->orderByDesc('created_at')->get();
    }

    public static function serviceDepartmentAdmins()
    {
        return self::with(['profile'])->role(Role::SERVICE_DEPARTMENT_ADMIN)->orderByDesc('created_at')->get();
    }

    public static function approvers()
    {
        return self::with(['profile'])->role(Role::APPROVER)->orderByDesc('created_at')->get();
    }

    public static function agents()
    {
        return self::with(['profile'])->role(Role::AGENT)->orderByDesc('created_at')->get();
    }

    public static function requesters()
    {
        return self::with(['profile'])->role(Role::USER)->orderByDesc('created_at')->get();
    }

    public function dateCreated(): string
    {
        return $this->createdAt($this->created_at);
    }

    public function dateUpdated(): string
    {
        return $this->updatedAt($this->created_at, $this->updated_at);
    }

    public function getServiceDepartments(): string
    {
        $serviceDepartmentNames = [];

        foreach ($this->serviceDepartments as $serviceDepartment) {
            $serviceDepartmentNames[] = $serviceDepartment->name;
        }

        if (!empty($serviceDepartmentNames)) {
            return implode(', ', $serviceDepartmentNames);
        }

        return '----';
    }

    public function getTeams(): string
    {
        $teams = [];

        foreach ($this->teams as $team) {
            $teams[] = $team->name;
        }

        if (!empty($teams)) {
            return implode(', ', $teams);
        }

        return '----';
    }

    // Get the branches assiged to approver.
    public function getBranches(): string
    {
        $branchNames = [];

        foreach ($this->branches as $branch) {
            $branchNames[] = $branch->name;
        }

        if (!empty($branchNames)) {
            return implode(', ', $branchNames);
        }

        return '----';
    }

    // Get the branches assiged to approver.
    public function getBUDepartments(): string
    {
        $buDepartmentNames = [];

        foreach ($this->buDepartments as $buDepartment) {
            $buDepartmentNames[] = $buDepartment->name;
        }

        if (!empty($buDepartmentNames)) {
            return implode(', ', $buDepartmentNames);
        }

        return '----';
    }

    public function getUserRoles()
    {
        $userRoles = [];

        foreach ($this->roles->pluck('name') as $role) {
            $userRoles[] = $role;
        }

        if (!empty($userRoles)) {
            return implode(', ', $userRoles);
        }

        return '----';
    }

    public function getUserPermissions()
    {
        $userPermissions = [];

        foreach ($this->getPermissionsViaRoles()->pluck('name') as $permission) {
            $userPermissions[] = $permission;
        }

        if (!empty($userPermissions)) {
            return implode(', ', $userPermissions);
        }

        return '----';
    }

    // For agent use only
    public function getClaimedTickets()
    {
        return Ticket::withWhereHas('agent', fn(Builder $agent) => $agent->where('agent_id', $this->id))->count();
    }
}

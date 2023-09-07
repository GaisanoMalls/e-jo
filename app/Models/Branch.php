<?php

namespace App\Models;

use App\Http\Traits\Utils;
use App\Models\Department;
use App\Models\Team;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory, Utils;

    protected $fillable = ['name', 'slug'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'department_branch');
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_branch');
    }

    public function approvers()
    {
        return $this->belongsToMany(User::class, 'user_branch')
            ->whereHas('role', fn($query) => $query->where('role_id', Role::APPROVER));
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
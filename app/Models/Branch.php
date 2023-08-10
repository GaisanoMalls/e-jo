<?php

namespace App\Models;

use App\Http\Traits\TimeStamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory, TimeStamps;

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
        return $this->belongsToMany(User::class)->where('role_id', Role::APPROVER);
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
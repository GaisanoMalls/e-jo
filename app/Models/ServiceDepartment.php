<?php

namespace App\Models;

use App\Http\Traits\Utils;
use App\Models\HelpTopic;
use App\Models\Role;
use App\Models\Team;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceDepartment extends Model
{
    use HasFactory, Utils;

    protected $fillable = [
        'name',
        'slug'
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function helpTopics()
    {
        return $this->hasMany(HelpTopic::class);
    }

    public function serviceDepartmentAdmins()
    {
        return $this->belongsToMany(User::class, 'user_service_department')
            ->whereHas('role', fn($serviceDeptAdmin) => $serviceDeptAdmin->where('role_id', Role::APPROVER));
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
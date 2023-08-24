<?php

namespace App\Models;

use App\Http\Traits\Utils;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory, Utils;

    protected $fillable = [
        'service_department_id',
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

    public function helpTopics()
    {
        return $this->hasMany(HelpTopic::class);
    }

    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'team_branch');
    }

    public function serviceDepartment()
    {
        return $this->belongsTo(ServiceDepartment::class);
    }

    public function getBranches()
    {
        $branchNames = [];

        foreach ($this->branches as $branch) {
            array_push($branchNames, $branch->name);
        }

        if (!empty($branchNames)) {
            return implode(', ', $branchNames);
        }

        return '----';
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
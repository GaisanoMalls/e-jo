<?php

namespace App\Models;

use App\Http\Traits\Utils;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
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

    public function helpTopics()
    {
        return $this->hasMany(HelpTopic::class);
    }

    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'department_branch')
            ->withTimestamps();
    }

    public function getTeams()
    {
        $teamNames = [];

        foreach ($this->teams as $team) {
            array_push($teamNames, $team->name);
        }

        if (!empty($teamNames)) {
            return implode(', ', $teamNames);
        }

        return '----';
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
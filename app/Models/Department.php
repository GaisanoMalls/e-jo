<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

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
        return $this->belongsToMany(Branch::class);
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
        return Carbon::parse($this->created_at)->format('M d, Y');
    }

    public function dateUpdated()
    {
        $created_at = Carbon::parse($this->created_at)->isoFormat('MMM DD, YYYY HH:mm:ss');
        $updated_at = Carbon::parse($this->updated_at)->isoFormat('MMM DD, YYYY HH:mm:ss');
        return $updated_at === $created_at
        ? "----"
        : Carbon::parse($this->updated_at)->format('M d, Y @ h:i A');
    }
}
<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

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
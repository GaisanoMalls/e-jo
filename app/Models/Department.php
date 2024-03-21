<?php

namespace App\Models;

use App\Http\Traits\Utils;
use App\Models\Branch;
use App\Models\HelpTopic;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory, Utils;

    protected $fillable = ['name', 'slug'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function helpTopics(): HasMany
    {
        return $this->hasMany(HelpTopic::class);
    }

    public function branches(): BelongsToMany
    {
        return $this->belongsToMany(Branch::class, 'department_branch')
            ->withTimestamps();
    }

    public function approvers(): Builder|BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_department')->role(Role::APPROVER);
    }

    public function getBranches(): string
    {
        $branchNames = [];

        foreach ($this->branches as $branch) {
            $branchNames[] = $branch->name;
        }

        if (!empty ($branchNames)) {
            return implode(', ', $branchNames);
        }

        return '';
    }

    public function dateCreated(): string
    {
        return $this->createdAt($this->created_at);
    }

    public function dateUpdated(): string
    {
        return $this->updatedAt($this->created_at, $this->updated_at);
    }
}

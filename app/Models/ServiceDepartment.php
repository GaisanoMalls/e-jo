<?php

namespace App\Models;

use App\Http\Traits\Utils;
use App\Models\HelpTopic;
use App\Models\Role;
use App\Models\Team;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceDepartment extends Model
{
    use HasFactory, Utils;

    protected $fillable = [
        'name',
        'slug'
    ];

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    public function helpTopics(): HasMany
    {
        return $this->hasMany(HelpTopic::class);
    }

    public function children()
    {
        return $this->hasMany(ServiceDepartmentChild::class);
    }

    public function serviceDepartmentAdmins(): Builder|BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_service_department')->role(Role::APPROVER);
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

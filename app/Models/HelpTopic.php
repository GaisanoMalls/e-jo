<?php

namespace App\Models;

use App\Http\Traits\Utils;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class HelpTopic extends Model
{
    use HasFactory, Utils;

    protected $fillable = [
        'service_department_id',
        'service_dept_child_id',
        'team_id',
        'service_level_agreement_id',
        'name',
        'slug',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function serviceDepartment(): BelongsTo
    {
        return $this->belongsTo(ServiceDepartment::class);
    }

    public function serviceDepartmentChild(): BelongsTo
    {
        return $this->belongsTo(ServiceDepartmentChildren::class, 'service_dept_child_id');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function sla(): BelongsTo
    {
        return $this->belongsTo(ServiceLevelAgreement::class, 'service_level_agreement_id');
    }

    public function specialProject(): HasOne
    {
        return $this->hasOne(SpecialProject::class);
    }

    public function form(): HasOne
    {
        return $this->hasOne(Form::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
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

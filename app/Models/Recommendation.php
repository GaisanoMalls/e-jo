<?php

namespace App\Models;

use App\Http\Traits\Utils;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Recommendation extends Model
{
    use HasFactory, Utils;

    protected $fillable = [
        'ticket_id',
        'requested_by_sda_id', // service department admin
        'reason',
        'level_of_approval',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function requestedByServiceDeptAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by_sda_id')->role(Role::SERVICE_DEPARTMENT_ADMIN);
    }

    public function approvalStatus(): HasOne
    {
        return $this->hasOne(RecommendationApprovalStatus::class, 'recommendation_id');
    }

    public function approvers(): HasMany
    {
        return $this->hasMany(RecommendationApprover::class, 'approver_id');
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

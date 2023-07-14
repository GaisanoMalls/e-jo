<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpTopic extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_department_id',
        'team_id',
        'sla_id',
        'name',
        'level_of_approver',
        'slug'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function serviceDepartment()
    {
        return $this->belongsTo(ServiceDepartment::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function sla()
    {
        return $this->belongsTo(ServiceLevelAgreement::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function helpTopicLevelApprovers()
    {
        return $this->hasMany(HelpTopicLevelApprover::class);
    }

    public function approvalLevels()
    {
        return $this->belongsToMany(ApprovalLevel::class, 'help_topic_level_approvers', 'help_topic_id', 'approval_level_id')
                    ->using(HelpTopicLevelApprover::class)
                    ->withTimestamps();
    }

    public function approvers()
    {
        return $this->belongsToMany(User::class, 'help_topic_level_approvers', 'help_topic_id', 'approver_id')
                    ->using(HelpTopicLevelApprover::class)
                    ->withTimestamps();
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
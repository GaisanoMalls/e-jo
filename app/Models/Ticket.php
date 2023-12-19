<?php

namespace App\Models;

use App\Http\Traits\Utils;
use App\Models\ActivityLog;
use App\Models\Branch;
use App\Models\HelpTopic;
use App\Models\PriorityLevel;
use App\Models\Reason;
use App\Models\Reply;
use App\Models\ServiceDepartment;
use App\Models\ServiceLevelAgreement;
use App\Models\Status;
use App\Models\Tag;
use App\Models\Team;
use App\Models\TicketFile;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ticket extends Model
{
    use HasFactory, Utils;

    protected $fillable = [
        'user_id',
        'agent_id',
        'branch_id',
        'service_department_id',
        'help_topic_id',
        'status_id',
        'priority_level_id',
        'service_level_agreement',
        'ticket_number',
        'subject',
        'description',
        'approval_status',
    ];

    protected $casts = [
        //
    ];

    public function user(): BelongsTo|Builder
    {
        return $this->belongsTo(User::class, 'user_id')->role(Role::USER);
    }

    public function agent(): BelongsTo|Builder
    {
        return $this->belongsTo(User::class, 'agent_id')->role(Role::AGENT);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function serviceDepartment(): BelongsTo
    {
        return $this->belongsTo(ServiceDepartment::class, 'service_department_id');
    }

    public function helpTopic(): BelongsTo
    {
        return $this->belongsTo(HelpTopic::class, 'help_topic_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function priorityLevel(): BelongsTo
    {
        return $this->belongsTo(PriorityLevel::class, 'priority_level_id');
    }

    public function sla(): BelongsTo
    {
        return $this->belongsTo(ServiceLevelAgreement::class, 'service_level_agreement');
    }

    public function bookmark(): HasOne
    {
        return $this->hasOne(Bookmark::class);
    }

    public function fileAttachments(): HasMany
    {
        return $this->hasMany(TicketFile::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Reply::class);
    }

    public function reasons(): HasMany
    {
        return $this->hasMany(Reason::class);
    }

    public function clarifications(): HasMany
    {
        return $this->hasMany(Clarification::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class)->orderByDesc('created_at');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'ticket_tag');
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'ticket_team');
    }

    public function dateCreated(): string
    {
        return $this->createdAt($this->created_at);
    }
}

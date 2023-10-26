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
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory, Utils;

    protected $fillable = [
        'user_id',
        'agent_id',
        'branch_id',
        'service_department_id',
        'team_id',
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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')
            ->whereHas('role', fn($requester) => $requester->where('role_id', Role::USER));
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id')
            ->whereHas('role', fn($agent) => $agent->where('role_id', Role::AGENT));
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function serviceDepartment()
    {
        return $this->belongsTo(ServiceDepartment::class, 'service_department_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function helpTopic()
    {
        return $this->belongsTo(HelpTopic::class, 'help_topic_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function priorityLevel()
    {
        return $this->belongsTo(PriorityLevel::class, 'priority_level_id');
    }

    public function sla()
    {
        return $this->belongsTo(ServiceLevelAgreement::class, 'service_level_agreement');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'ticket_tag');
    }

    public function fileAttachments()
    {
        return $this->hasMany(TicketFile::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function reasons()
    {
        return $this->hasMany(Reason::class);
    }

    public function clarifications()
    {
        return $this->hasMany(Clarification::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class)->orderByDesc('created_at');
    }

    public function bookmark()
    {
        return $this->hasOne(Bookmark::class);
    }

    public function dateCreated()
    {
        return $this->createdAt($this->created_at);
    }
}
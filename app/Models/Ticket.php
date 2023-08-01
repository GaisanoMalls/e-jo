<?php

namespace App\Models;

use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'agent_id',
        'branch_id',
        'service_department_id',
        'team_id',
        'help_topic_id',
        'status_id',
        'priority_level_id',
        'sla_id',
        'ticket_number',
        'subject',
        'description',
        'approval_status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function serviceDepartment()
    {
        return $this->belongsTo(ServiceDepartment::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function helpTopic()
    {
        return $this->belongsTo(HelpTopic::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function priorityLevel()
    {
        return $this->belongsTo(PriorityLevel::class);
    }

    public function sla()
    {
        return $this->belongsTo(ServiceLevelAgreement::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
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

    public function dateCreated()
    {
        return Carbon::parse($this->created_at)->format('M d, Y');
    }
}
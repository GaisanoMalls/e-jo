<?php

namespace App\Models;

use App\Http\Traits\Utils;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpTopic extends Model
{
    use HasFactory, Utils;

    protected $fillable = [
        'service_department_id',
        'team_id',
        'sla_id',
        'name',
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

    public function levels()
    {
        return $this->belongsToMany(Level::class, 'help_topic_level', 'help_topic_id', 'level_id');
    }

    public function dateCreated()
    {
        return $this->createdAt($this->created_at);
    }

    public function dateUpdated()
    {
        return $this->updatedAt($this->created_at, $this->updated_at);
    }
}
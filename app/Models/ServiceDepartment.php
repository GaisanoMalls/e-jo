<?php

namespace App\Models;

use App\Http\Traits\TimeStamps;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceDepartment extends Model
{
    use HasFactory, TimeStamps;

    protected $fillable = [
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

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function helpTopics()
    {
        return $this->hasMany(HelpTopic::class);
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
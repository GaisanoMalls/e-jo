<?php

namespace App\Models;

use App\Http\Traits\Utils;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceLevelAgreement extends Model
{
    use HasFactory, Utils;

    /**
     * countdown_approach: 72
     * time_unit: 3 Days
     */
    protected $fillable = ['countdown_approach', 'time_unit'];

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function helpTopics(): HasMany
    {
        return $this->hasMany(HelpTopic::class);
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

<?php

namespace App\Models;

use App\Http\Traits\Utils;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceLevelAgreement extends Model
{
    use HasFactory, Utils;

    /**
     *
     * countdown_approach: 72
     * time_unit: 3 Days
     */
    protected $fillable = ['countdown_approach', 'time_unit'];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
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
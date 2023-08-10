<?php

namespace App\Models;

use App\Http\Traits\TimeStamps;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory, TimeStamps;

    protected $fillable = ['name', 'slug'];

    public function tickets()
    {
        return $this->belongsToMany(Ticket::class);
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
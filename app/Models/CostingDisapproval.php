<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostingDisapproval extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = ['ticket_costing_id', 'reason', 'date_approved'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}

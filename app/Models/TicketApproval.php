<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketApproval extends Model
{
    use HasFactory;

    protected $table = 'ticket_approval';
    protected $fillable = [
        'ticket_id',
        'approver',
        'is_currently_for_approval',
        'is_approved',
    ];

    /**
     * Properties: approver_id & approved_by
     */
    protected $casts = [
        'approver' => AsArrayObject::class,
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function isAllApproved()
    {
        return $this->is_all_approved === 1;
    }
}

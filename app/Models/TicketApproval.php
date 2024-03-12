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
        'approval_1',
        'approval_2',
        'is_all_approval_done',
    ];

    /**
     * Properties Approval 1 and 2: 
     * level_1_approver - approver_id, approved_by, and is_approved
     * level_2_approver - approver_id, approved_by, and is_approved
     * is_all_approved
     */
    protected $casts = [
        'approval_1' => AsArrayObject::class,
        'approval_2' => AsArrayObject::class,
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}

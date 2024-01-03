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
        'level_1_approver',
        'level_2_approver',
        'is_all_approved',
        'approved_by',
    ];

    /**
     * Properties: approver_id & is_approved
     */
    protected $casts = [
        'level_1_approver' => AsArrayObject::class,
        'level_2_approver' => AsArrayObject::class,
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    // public function level1Approver()
    // {
    //     return $this->belongsTo(User::class, 'level_1_approver->approver_id');
    // }

    // public function level2Approver()
    // {
    //     return $this->belongsTo(User::class, 'level_2_approver->approver_id');
    // }

    public function isAllApproved()
    {
        return $this->is_all_approved === 1;
    }
}

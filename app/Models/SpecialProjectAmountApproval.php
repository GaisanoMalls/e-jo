<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialProjectAmountApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'service_department_admin_approver',
        'fpm_coo_approver',
        'is_done'
    ];

    /**
     * Properties: approver_id, is_approved, and date_approved 
     */
    protected $casts = [
        'service_department_admin_approver' => AsArrayObject::class,
        'fpm_coo_approver' => AsArrayObject::class,
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }


    public function approvedCostings()
    {
        return $this->hasMany(ApprovedCosting::class);
    }

    public function disapprovedCostings()
    {
        return $this->hasMany(DisapprovedCosting::class);
    }
}

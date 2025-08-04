<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SpecialProjectAmountApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'service_department_admin_approver' => 'array',
        'fpm_coo_approver' => 'array',
        'is_done'
    ];

    /**
     * Properties: approver_id, is_approved, and date_approved 
     */
    protected $casts = [
        'service_department_admin_approver' => AsArrayObject::class,
        'fpm_coo_approver' => AsArrayObject::class,
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function approvedCostings(): HasMany
    {
        return $this->hasMany(ApprovedCosting::class);
    }

    public function disapprovedCostings(): HasMany
    {
        return $this->hasMany(DisapprovedCosting::class);
    }
    
}

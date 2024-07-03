<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DisapprovedCosting extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['special_project_amount_approval_id', 'amount', 'reason', 'disapproved_date'];

    public function specialProjectAmountApproval(): BelongsTo
    {
        return $this->belongsTo(SpecialProjectAmountApproval::class);
    }
}

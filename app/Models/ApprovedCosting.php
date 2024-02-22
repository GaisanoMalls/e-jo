<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovedCosting extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['special_project_amount_approval_id', 'approved_date'];

    public function specialProjectAmountApproval()
    {
        return $this->belongsTo(SpecialProjectAmountApproval::class);
    }
}

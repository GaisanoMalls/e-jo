<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpecialProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'help_topic_id',
        'amount',
        'fmp_coo_approver',
        'service_department_approver',
        'bu_department_approver',
    ];

    protected $casts = [
        'fmp_coo_approver' => AsArrayObject::class,
        'service_department_approver' => AsArrayObject::class,
        'bu_department_department_approver' => AsArrayObject::class,
    ];

    public function helpTopic(): BelongsTo
    {
        return $this->belongsTo(HelpTopic::class);
    }
}

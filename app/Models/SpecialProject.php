<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpecialProject extends Model
{
    use HasFactory;

    protected $fillable = ['help_topic_id', 'amount', 'meta_data'];

    protected $casts = [
        'meta_data' => AsArrayObject::class,
    ];

    public function helpTopic(): BelongsTo
    {
        return $this->belongsTo(HelpTopic::class);
    }
}

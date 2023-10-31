<?php

namespace App\Models;

use App\Models\Reply;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReplyFile extends Model
{
    use HasFactory;

    protected $fillable = ['reply_id', 'file_attachment'];
    public $timestamps = false;

    public function reply(): BelongsTo
    {
        return $this->belongsTo(Reply::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReplyLike extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['reply_id', 'liked_by'];

    public function reply()
    {
        return $this->belongsTo(Reply::class, 'reply_id');
    }

    public function likedBy()
    {

    }
}

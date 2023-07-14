<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReplyFile extends Model
{
    use HasFactory;

    protected $fillable = ['reply_id', 'file_attachment'];
    public $timestamps = false;

    public function reply()
    {
        return $this->belongsTo(Reply::class);
    }
}
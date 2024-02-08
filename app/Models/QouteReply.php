<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QouteReply extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['qouted_by', 'reply_id'];

    public function reply()
    {
        return $this->belongsTo(Reply::class, 'reply_id');
    }
}

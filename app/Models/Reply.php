<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory;

    protected $fillable = ['ticket_id', 'user_id', 'description'];

    public function fileAttachments()
    {
        return $this->hasMany(ReplyFile::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

     public function dateCreated()
    {
        return Carbon::parse($this->created_at)->format('M d, Y');
    }
}
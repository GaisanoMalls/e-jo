<?php

namespace App\Models;

use App\Http\Traits\Utils;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory, Utils;

    protected $fillable = ['ticket_id', 'user_id', 'description'];

    public function fileAttachments()
    {
        return $this->hasMany(ReplyFile::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dateCreated()
    {
        return $this->createdAt($this->created_at);
    }
}
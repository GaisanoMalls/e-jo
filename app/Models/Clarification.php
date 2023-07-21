<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clarification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ticket_id',
        'description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function fileAttachments()
    {
        return $this->hasMany(ClarificationFile::class);
    }
}
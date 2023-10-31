<?php

namespace App\Models;

use App\Http\Traits\Utils;
use App\Models\ReplyFile;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reply extends Model
{
    use HasFactory, Utils;

    protected $fillable = ['ticket_id', 'user_id', 'description'];

    public function fileAttachments(): HasMany
    {
        return $this->hasMany(ReplyFile::class);
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dateCreated(): string
    {
        return $this->createdAt($this->created_at);
    }
}

<?php

namespace App\Models;

use App\Http\Traits\Utils;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketCosting extends Model
{
    use HasFactory, Utils;

    protected $fillable = ['ticket_id', 'amount'];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function fileAttachments(): HasMany
    {
        return $this->hasMany(TicketCostingFile::class);
    }

    public function getAmount(): string
    {
        return number_format($this->amount, 2, '.', ',');
    }

    public function dateCreated(): string
    {
        return $this->createdAt($this->created_at);
    }
}

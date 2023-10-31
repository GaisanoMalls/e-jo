<?php

namespace App\Models;

use App\Http\Traits\Utils;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory, Utils;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'description',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function causer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function causerDetails(): string
    {
        return $this->user_id != auth()->user()->id
            ? $this->causer->profile->getFullName()
            : 'You';
    }

    public static function make(int $ticket_id, string $description): void
    {
        self::create([
            'ticket_id' => $ticket_id,
            'user_id' => auth()->user()->id,
            'description' => $description,
        ]);
    }

    public function dateCreated(): string
    {
        return Carbon::parse($this->created_at)->format('M d, Y | h:i A');
    }
}

<?php

namespace App\Models;

use App\Http\Traits\Utils;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory, Utils;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'log_name',
        'description'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function causer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function causerDetails()
    {
        $details = $this->user_id != auth()->user()->id
            ? $this->causer->profile->getFullName()
            : 'You';

        return $details;
    }

    public static function make(int $ticket, int $causer, string $description, string $logName = null)
    {
        self::create([
            'ticket_id' => $ticket,
            'user_id' => $causer,
            'log_name' => $logName,
            'description' => $description
        ]);
    }

    public function dateCreated()
    {
        return Carbon::parse($this->created_at)->format('M d, Y | h:i A');
    }
}
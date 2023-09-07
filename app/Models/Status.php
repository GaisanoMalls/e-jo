<?php

namespace App\Models;

use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'color', 'slug'];
    const OPEN = 1;
    const VIEWED = 2;
    const APPROVED = 3;
    const DISAPPROVED = 4;
    const CLAIMED = 5;
    const ON_PROCESS = 6;
    const OVERDUE = 7;
    const CLOSED = 8;

    const COLOR = [
        self::OPEN => '#3F993F',
        self::VIEWED => '#1F75CC',
        self::APPROVED => '#BD7332',
        self::DISAPPROVED => '#FF0000',
        self::CLAIMED => '#FF8B8B',
        self::ON_PROCESS => '#1E1C1D',
        self::OVERDUE => '#FD6852',
        self::CLOSED => '#7A7E87'
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function dateCreated()
    {
        return Carbon::parse($this->created_at)->format('M d, Y');
    }
}
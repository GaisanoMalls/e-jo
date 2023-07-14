<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'color', 'slug'];
    const OPEN = 1;
    const VIEWED = 2;
    const ON_HOLD = 3;
    const APPROVED = 4;
    const CLAIMED = 5;
    const ON_PROCESS = 6;
    const REOPENED = 7;
    const OVERDUE = 8;
    const CLOSED = 9;

    const COLOR = [
        self::OPEN => '#3F993F',
        self::VIEWED => '#1F75CC',
        self::ON_HOLD => '#408AA8',
        self::APPROVED => '#BD7332',
        self::CLAIMED => '#FF8B8B',
        self::ON_PROCESS => '#1E1C1D',
        self::REOPENED => '#309431',
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
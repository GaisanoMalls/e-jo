<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriorityLevel extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'color', 'slug'];
    public $timestamps = false;

    const LOW = 'Low';
    const MEDIUM = 'Medium';
    const HIGH = 'High';
    const URGENT = 'Urgent';
    const COLOR = [
        self::LOW => '#5A5A5A',
        self::MEDIUM => '#FFA500',
        self::HIGH => '#1E4620',
        self::URGENT => 'FF0000'
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
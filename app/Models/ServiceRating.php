<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRating extends Model
{
    use HasFactory;

    const TERRIBLE = 1;
    const BAD = 2;
    const GOOD = 3;
    const VERY_GOOD = 4;
    const EXCELLENT = 5;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suffix extends Model
{
    use HasFactory;

    protected $fillable = ['name'];
    public $timestamps = false;

    const JR = 'Jr';
    const SR = 'Sr';
    const III = 'III';
    const IV = 'IV';
    const V = 'I';
}
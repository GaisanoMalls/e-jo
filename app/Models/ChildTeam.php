<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildTeam extends Model
{
    use HasFactory;
    protected $fillable = ['team_id', 'name', 'slug'];
    public $timestamps = false;
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasingTeam extends Model
{
    use HasFactory;

    protected $table = 'purchasing_team';
    protected $fillable = ['agent_id'];

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }
}

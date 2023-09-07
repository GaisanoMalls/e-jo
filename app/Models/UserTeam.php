<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Role;

class UserTeam extends Model
{
    use HasFactory;

    protected $table = 'user_team';
    
    // Many-to-Many Relationship between Agent and Team
    protected $fillable = ['user_id', 'team_id'];

    public function agent()
    {
        return $this->belongsTo(User::class)
            ->whereHas('role', fn($agent) => $agent->where('role_id', Role::AGENT));
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
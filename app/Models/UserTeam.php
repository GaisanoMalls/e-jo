<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserTeam extends Model
{
    use HasFactory;

    protected $table = 'user_team';

    // Many-to-Many Relationship between Agent and Team
    protected $fillable = ['user_id', 'team_id'];

    public function agent(): BelongsTo|Builder
    {
        return $this->belongsTo(User::class)->role(Role::AGENT);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}

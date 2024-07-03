<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSubteam extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'subteam_id'];

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class)->role(Role::AGENT);
    }

    public function subteam(): BelongsTo
    {
        return $this->belongsTo(Subteam::class);
    }
}

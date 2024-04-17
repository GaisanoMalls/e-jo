<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSubteam extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'subteam_id'];

    public function agent()
    {
        return $this->belongsTo(User::class)->role(Role::AGENT);
    }

    public function subteam()
    {
        return $this->belongsTo(Subteam::class);
    }
}

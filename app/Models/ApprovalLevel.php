<?php

namespace App\Models;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalLevel extends Model
{
    use HasFactory;

    protected $fillable = ['value', 'description'];

    public function approvers()
    {
        return $this->belongsToMany(User::class, 'user_id')
            ->whereHas('role', fn($approver) => $approver->where('role_id', Role::APPROVER));
    }
}
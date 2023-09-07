<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Role;

class UserBranch extends Model
{
    use HasFactory;

    protected $table = 'user_branch';

    // Many-to-Many Relationship Approver and Branch
    protected $fillable = ['user_id', 'branch_id'];

    public function approver()
    {
        return $this->belongsTo(User::class)
            ->whereHas('role', fn($approver) => $approver->where('role_id', Role::APPROVER));
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
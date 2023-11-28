<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserBranch extends Model
{
    use HasFactory;

    protected $table = 'user_branch';

    // Many-to-Many Relationship - (Approver & Service Department Admin) and Branch
    protected $fillable = ['user_id', 'branch_id'];

    public function approver(): Builder|BelongsTo
    {
        return $this->belongsTo(User::class)->hasRole([Role::APPROVER, Role::SERVICE_DEPARTMENT_ADMIN]);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}

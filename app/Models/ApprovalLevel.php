<?php

namespace App\Models;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ApprovalLevel extends Model
{
    use HasFactory;

    protected $fillable = ['value', 'description'];

    public function approvers(): Builder|BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_id')
            ->whereHas('role', fn($approver) => $approver->where('role_id', Role::APPROVER));
    }
}

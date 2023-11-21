<?php

namespace App\Models;

use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDepartment extends Model
{
    use HasFactory;

    protected $table = 'user_department';

    // Many-to-Many Relationship Approver and BU/Department
    protected $fillable = ['user_id', 'department_id'];

    public function approver(): BelongsTo|Builder
    {
        return $this->belongsTo(User::class)->role(Role::APPROVER);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}

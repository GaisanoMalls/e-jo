<?php

namespace App\Models;

use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDepartment extends Model
{
    use HasFactory;

    protected $table = 'user_department';

    // Many-to-Many Relationship Approver and BU/Department
    protected $fillable = ['user_id', 'department_id'];

    public function approver()
    {
        return $this->belongsTo(User::class)
            ->whereHas('role', fn($approver) => $approver->where('role_id', Role::APPROVER));
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
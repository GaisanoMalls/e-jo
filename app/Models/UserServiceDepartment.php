<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserServiceDepartment extends Model
{
    use HasFactory;

    // Many-to-Many Relationship between Service Department Admin - Service Department
    protected $fillable = ['user_id', 'service_department_id'];
    protected $table = 'user_service_department';

    public function user()
    {
        return $this->belongsTo(User::class)->where(function ($query) {
            $query->where('role_id', Role::SERVICE_DEPARTMENT_ADMIN);
        });
    }

    public function serviceDepartment()
    {
        return $this->belongsTo(ServiceDepartment::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserServiceDepartment extends Model
{
    use HasFactory;

    protected $table = 'user_service_department';

    // Many-to-Many Relationship between Service Department Admin and Service Department
    protected $fillable = ['user_id', 'service_department_id'];

    public function serviceDepartmentAdmin(): BelongsTo|Builder
    {
        return $this->belongsTo(User::class)
            ->whereHas('role', fn($serviceDeptAdmin) => $serviceDeptAdmin->where('role_id', Role::SERVICE_DEPARTMENT_ADMIN));
    }

    public function serviceDepartment(): BelongsTo
    {
        return $this->belongsTo(ServiceDepartment::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ServiceDepartmentChildren extends Model
{
    use HasFactory;

    protected $table = 'service_department_children';

    protected $fillable = ['service_department_id', 'name'];

    public function serviceDepartment(): BelongsTo
    {
        return $this->belongsTo(ServiceDepartment::class);
    }

    public function team(): HasOne
    {
        return $this->hasOne(Team::class, 'team_service_department_children');
    }
}

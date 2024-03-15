<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceDepartmentChild extends Model
{
    use HasFactory;

    protected $fillable = ['service_department_id', 'name'];
    public $timestamps = false;

    public function serviceDepartment()
    {
        return $this->belongsTo(ServiceDepartment::class);
    }
}

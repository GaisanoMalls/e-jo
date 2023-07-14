<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceDepartmentBranch extends Model
{
    use HasFactory;
    protected $table = 'branch_service_department'; // ! DON'T REMOVE/RENAME TO AVOID ERRORS
    protected $fillable = ['service_department_id', 'branch_id'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function serviceDepartment()
    {
        return $this->belongsTo(ServiceDepartment::class);
    }

    public function dateCreated()
    {
        return Carbon::parse($this->created_at)->format('M d, Y');
    }

    public function dateUpdated()
    {
        $created_at = Carbon::parse($this->created_at)->isoFormat('MMM DD, YYYY HH:mm:ss');
        $updated_at = Carbon::parse($this->updated_at)->isoFormat('MMM DD, YYYY HH:mm:ss');
        return $updated_at === $created_at
        ? "----"
        : Carbon::parse($this->updated_at)->format('M d, Y @ h:i A');
    }
}

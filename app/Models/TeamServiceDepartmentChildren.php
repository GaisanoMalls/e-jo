<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamServiceDepartmentChildren extends Model
{
    use HasFactory;

    protected $table = 'team_service_department_children';

    protected $fillable = ['team_id', 'service_dept_child_id'];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function serviceDepartmentChild(): BelongsTo
    {
        return $this->belongsTo(ServiceDepartmentChildren::class);
    }
}

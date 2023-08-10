<?php

namespace App\Models;

use App\Http\Traits\TimeStamps;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentBranch extends Model
{
    use HasFactory, TimeStamps;

    protected $guarded = [];
    protected $table = 'department_branch';
    protected $fillable = ['department_id', 'branch_id'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function dateCreated()
    {
        return $this->createdAt($this->created_at);
    }

    public function dateUpdated()
    {
        return $this->updatedAt($this->created_at, $this->updated_at);
    }
}
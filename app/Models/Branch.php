<?php

namespace App\Models;

use App\Http\Traits\TimeStamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory, TimeStamps;

    protected $fillable = ['name', 'slug'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'branch_department');
    }

    public function serviceDepartments()
    {
        return $this->belongsToMany(ServiceDepartment::class);
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }

    public function dateCreated()
    {
        return self::createdAt($this->created_at);
    }

    public function dateUpdated()
    {
        return self::updatedAt($this->created_at, $this->updated_at);
    }
}
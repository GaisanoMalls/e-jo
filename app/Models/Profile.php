<?php

namespace App\Models;

use App\Http\Traits\TimeStamps;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory, TimeStamps;

    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'mobile_number',
        'department_phone_number',
        'picture',
        'slug'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFullName(): string
    {
        $middleInitial = $this->middle_name ? $this->middle_name[0] . '.' : '';
        $suffix = $this->suffix ?? '';

        return "{$this->first_name} {$middleInitial} {$this->last_name} {$suffix}";
    }

    public function getNameInitial(): string
    {
        return $this->nameInitials();
    }

    private function nameInitials(): string
    {
        return $this->first_name[0] . $this->last_name[0];
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
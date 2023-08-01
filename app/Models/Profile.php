<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

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
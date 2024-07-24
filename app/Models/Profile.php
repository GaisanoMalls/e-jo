<?php

namespace App\Models;

use App\Http\Traits\Utils;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    use HasFactory, Utils;

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFullName(): Attribute
    {
        $middleInitial = $this->middle_name ? $this->middle_name[0] . '.' : '';
        $suffix = $this->suffix ?? '';

        return Attribute::make(
            get: fn() => "$this->first_name $middleInitial $this->last_name $suffix"
        );
    }

    public function getNameInitial(): string
    {
        return $this->nameInitials();
    }

    private function nameInitials(): string
    {
        return $this->first_name[0] . $this->last_name[0];
    }

    public function dateCreated(): string
    {
        return $this->createdAt($this->created_at);
    }

    public function dateUpdated(): string
    {
        return $this->updatedAt($this->created_at, $this->updated_at);
    }
}

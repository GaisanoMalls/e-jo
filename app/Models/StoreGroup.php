<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\Utils;

class StoreGroup extends Model
{
    use HasFactory, Utils;

    protected $fillable = [
        'name',
    ];

    /**
     * Get the stores that belong to this group.
     */
    public function stores()
    {
        return $this->hasMany(Store::class);
    }

    /**
     * Get the formatted date created.
     */
    public function dateCreated()
    {
        return $this->created_at->format('M d, Y');
    }

    /**
     * Get the formatted date updated.
     */
    public function dateUpdated()
    {
        return $this->updated_at->format('M d, Y');
    }
}

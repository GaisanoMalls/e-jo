<?php

namespace App\Models;

use App\Http\Traits\Utils;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory, Utils;

    protected $fillable = ['store_code', 'store_name', 'store_group_id'];

    /**
     * Get the store group that owns the store.
     */
    public function storeGroup()
    {
        return $this->belongsTo(StoreGroup::class);
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

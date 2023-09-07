<?php

namespace App\Models;

use App\Models\Clarification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClarificationFile extends Model
{
    use HasFactory;

    protected $fillable = ['clarification_id', 'file_attachment'];
    public $timestamps = false;

    public function clarification()
    {
        return $this->belongsTo(Clarification::class);
    }
}
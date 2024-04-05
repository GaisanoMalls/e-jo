<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;

    protected $fillable = ['help_topic_id', 'name'];

    public function helpTopic()
    {
        return $this->belongsTo(HelpTopic::class);
    }

    public function fields()
    {
        return $this->hasMany(Field::class);
    }
}

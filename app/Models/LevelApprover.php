<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelApprover extends Model
{
    use HasFactory;

    protected $table = 'level_approver';
    protected $fillable = ['level_id', 'user_id', 'help_topic_id'];

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class);
    }

    public function helpTopic()
    {
        return $this->belongsTo(HelpTopic::class);
    }
}
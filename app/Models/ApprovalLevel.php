<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalLevel extends Model
{
    use HasFactory;

    protected $fillable = ['value', 'description'];

    public function approvers()
    {
        return $this->belongsToMany(User::class)->where('role_id', Role::APPROVER);
    }
}
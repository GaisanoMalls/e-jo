<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApproverBranch extends Model
{
    use HasFactory;

    protected $table = 'approver_branch';
    protected $fillable = ['approver_id', 'branch_id'];

    public function approver()
    {
        return $this->belongsTo(User::class)->where('role_id', Role::APPROVER);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
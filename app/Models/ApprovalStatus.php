<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalStatus extends Model
{
    use HasFactory;

    const FOR_APPROVAL = 'for_approval';
    const APPROVED = 'approved';
    const DISAPPROVED = 'disapproved';
}
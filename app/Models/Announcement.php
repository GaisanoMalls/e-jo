<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_department_id',
        'team_id',
        'title',
        'description',
        'is_important',
        'is_draft'
    ];

    public function serviceDepartment()
    {
        return $this->belongsTo(ServiceDepartment::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function isImportant()
    {
        return $this->is_important === 1;
    }

    public function isDraft()
    {
        return $this->is_draft === 1;
    }

    public function getDraftStatus()
    {
        return $this->isDraft() ? 'Draft' : '';
    }

    public function getImportanceStatus(): string
    {
        return $this->isImportant() ? 'Important' : '';
    }
}
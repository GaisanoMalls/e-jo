<?php

namespace App\Models;

use App\Http\Traits\TimeStamps;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamBranch extends Model
{
    use HasFactory, TimeStamps;

    protected $table = 'team_branch';
    protected $fillable = ['team_id', 'branch_id'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function dateCreated()
    {
        return $this->createdAt($this->created_at);
    }

    public function dateUpdated()
    {
        return $this->updatedAt($this->created_at, $this->updated_at);
    }

}
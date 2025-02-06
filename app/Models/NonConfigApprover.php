<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NonConfigApprover extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['ticket_id', 'approvers'];

    /**
     * Stores the approvers id and status as json object
     * @var $approvers
     * 
     * @example
     * {
     *  'approvers': {
     *      'id': {},
     *      'is_approved': true|false
     *  }
     * }
     */
    protected $casts = [
        'approvers' => AsArrayObject::class,
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}

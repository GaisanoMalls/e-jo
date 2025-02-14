<?php

namespace App\Models;

use App\Enums\TicketSlaExtensionStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketSlaExtension extends Model
{
    use HasFactory;

    protected $table = 'ticket_sla_extension';
    protected $fillable = [
        'ticket_id',
        'requested_by',
        'status',
        'is_new_sla_set'
    ];

    protected $casts = [
        'status' => TicketSlaExtensionStatusEnum::class
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by')
            ->with(['profile', 'buDepartments'])
            ->withWhereHas('roles', fn($query) => $query->where('roles.name', Role::AGENT));
    }
}

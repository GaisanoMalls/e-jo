<?php

namespace App\Enums;

enum TicketSlaExtensionStatusEnum: string
{
    case REQUESTING = 'Requesting';
    case APPROVED = 'Approved';

    public static function toArray(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
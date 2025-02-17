<?php

namespace App\Enums;

enum SubtaskStatusEnum: string
{
    case OPEN = 'Open';
    case DONE = 'Done';

    public static function toArray(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}